<?php

namespace App\Http\Livewire\Admin;

use App\Models\AuditTrail;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionManagerComponent extends Component
{
    public string $activeTab = 'roles';

    // ── Role form ──────────────────────────────────────────────────────────────
    public ?int  $editingRoleId        = null;
    public string $roleName            = '';
    public string $dashboardRoute      = '';
    public array  $selectedPermissions = [];

    // ── Permission form ────────────────────────────────────────────────────────
    public ?int  $editingPermissionId = null;
    public string $permissionName     = '';

    // These roles cannot be renamed or deleted
    protected array $protectedRoles = ['Super Admin'];

    public function mount(): void
    {
        abort_if(!auth()->user()?->hasRole('Super Admin'), 403);
    }

    // ── Roles ──────────────────────────────────────────────────────────────────

    public function openCreateRole(): void
    {
        $this->editingRoleId        = null;
        $this->roleName             = '';
        $this->dashboardRoute       = '';
        $this->selectedPermissions  = [];
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('show-roleModal', ['isEdit' => false]);
    }

    public function openEditRole(int $id): void
    {
        $role = Role::with('permissions')->findOrFail($id);
        $this->editingRoleId        = $id;
        $this->roleName             = $role->name;
        $this->dashboardRoute       = $role->dashboard_route ?? '';
        $this->selectedPermissions  = $role->permissions->pluck('id')->map(fn ($i) => (string) $i)->toArray();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('show-roleModal', ['isEdit' => true]);
    }

    public function saveRole(): void
    {
        $uniqueRule = 'unique:roles,name' . ($this->editingRoleId ? ",{$this->editingRoleId}" : '');
        $this->validate(['roleName' => "required|string|max:64|{$uniqueRule}"]);

        $perms = Permission::whereIn('id', $this->selectedPermissions)->get();

        if ($this->editingRoleId) {
            $role    = Role::with('permissions')->findOrFail($this->editingRoleId);
            $oldName = $role->name;
            $oldPerms = $role->permissions->pluck('name')->toArray();

            if (in_array($role->name, $this->protectedRoles) && $role->name !== $this->roleName) {
                $this->addError('roleName', 'The name of a protected role cannot be changed.');
                return;
            }

            $role->update([
                'name'            => $this->roleName,
                'dashboard_route' => $this->dashboardRoute ?: null,
            ]);
            $role->syncPermissions($perms);

            AuditTrail::record(
                'role.updated',
                "Updated role \"{$oldName}\"" . ($oldName !== $role->name ? " → \"{$role->name}\"" : '') . '.',
                $role,
                ['name' => $oldName, 'permissions' => $oldPerms],
                ['name' => $role->name, 'permissions' => $perms->pluck('name')->toArray(),
                 'dashboard_route' => $role->dashboard_route]
            );
            $msg = "Role \"{$role->name}\" updated.";
        } else {
            $role = Role::create([
                'name'            => $this->roleName,
                'guard_name'      => 'web',
                'dashboard_route' => $this->dashboardRoute ?: null,
            ]);
            $role->syncPermissions($perms);

            AuditTrail::record(
                'role.created',
                "Created role \"{$role->name}\".",
                $role,
                [],
                ['name' => $role->name, 'permissions' => $perms->pluck('name')->toArray(),
                 'dashboard_route' => $role->dashboard_route]
            );
            $msg = "Role \"{$role->name}\" created.";
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->dispatchBrowserEvent('hide-roleModal');
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => $msg]);
    }

    public function deleteRole(int $id): void
    {
        abort_if(!auth()->user()?->hasRole('Super Admin'), 403);
        $role = Role::withCount('users')->findOrFail($id);

        if (in_array($role->name, $this->protectedRoles)) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => "\"{$role->name}\" is a protected role and cannot be deleted."]);
            return;
        }

        if ($role->users_count > 0) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => "Cannot delete \"{$role->name}\" — {$role->users_count} user(s) still assigned. Re-assign them first."]);
            return;
        }

        $name = $role->name;
        $role->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        AuditTrail::record('role.deleted', "Deleted role \"{$name}\".");
        $this->dispatchBrowserEvent('notify', ['type' => 'info', 'message' => "Role \"{$name}\" deleted."]);
    }

    // ── Permissions ────────────────────────────────────────────────────────────

    public function openCreatePermission(): void
    {
        $this->editingPermissionId = null;
        $this->permissionName      = '';
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('show-permissionModal', ['isEdit' => false]);
    }

    public function openEditPermission(int $id): void
    {
        $perm = Permission::findOrFail($id);
        $this->editingPermissionId = $id;
        $this->permissionName      = $perm->name;
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('show-permissionModal', ['isEdit' => true]);
    }

    public function savePermission(): void
    {
        $uniqueRule = 'unique:permissions,name' . ($this->editingPermissionId ? ",{$this->editingPermissionId}" : '');
        $this->validate(['permissionName' => "required|string|max:128|{$uniqueRule}"]);

        if ($this->editingPermissionId) {
            $perm = Permission::findOrFail($this->editingPermissionId);
            $old  = $perm->name;
            $perm->update(['name' => $this->permissionName]);
            AuditTrail::record(
                'permission.updated',
                "Renamed permission \"{$old}\" to \"{$perm->name}\".",
                $perm,
                ['name' => $old],
                ['name' => $perm->name]
            );
            $msg = "Permission \"{$old}\" renamed to \"{$perm->name}\".";
        } else {
            $perm = Permission::create(['name' => $this->permissionName, 'guard_name' => 'web']);
            AuditTrail::record(
                'permission.created',
                "Created permission \"{$perm->name}\".",
                $perm, [], ['name' => $perm->name]
            );
            $msg = "Permission \"{$perm->name}\" created.";
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $this->dispatchBrowserEvent('hide-permissionModal');
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => $msg]);
    }

    public function deletePermission(int $id): void
    {
        abort_if(!auth()->user()?->hasRole('Super Admin'), 403);
        $perm = Permission::findOrFail($id);
        $name = $perm->name;
        $perm->delete();
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        AuditTrail::record('permission.deleted', "Deleted permission \"{$name}\".");
        $this->dispatchBrowserEvent('notify', ['type' => 'info', 'message' => "Permission \"{$name}\" deleted and removed from all roles."]);
    }

    // ── Render ─────────────────────────────────────────────────────────────────

    public function render()
    {
        return view('livewire.admin.role-permission-manager-component', [
            'roles'       => Role::withCount(['users', 'permissions'])->orderBy('name')->get(),
            'permissions' => Permission::with('roles')->orderBy('name')->get(),
        ])->layout('layouts.admin.admin-layout');
    }
}
