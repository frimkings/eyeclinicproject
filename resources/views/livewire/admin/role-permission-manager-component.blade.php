<div> {{-- single Livewire root required by Livewire 2 --}}

<div class="container-fluid py-4">

    {{-- Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col">
            <h4 class="font-weight-bold text-primary mb-1">Roles &amp; Permissions</h4>
            <p class="text-muted small mb-0">Create custom roles, define permissions, and control what each role can access.</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body py-2">
            <div class="btn-group btn-group-sm">
                <button wire:click="$set('activeTab', 'roles')"
                    class="btn {{ $activeTab === 'roles' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    <i class="fas fa-user-tag mr-1"></i> Roles
                    <span class="badge badge-{{ $activeTab === 'roles' ? 'light text-primary' : 'secondary' }} ml-1">{{ $roles->count() }}</span>
                </button>
                <button wire:click="$set('activeTab', 'permissions')"
                    class="btn {{ $activeTab === 'permissions' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    <i class="fas fa-key mr-1"></i> Permissions
                    <span class="badge badge-{{ $activeTab === 'permissions' ? 'light text-primary' : 'secondary' }} ml-1">{{ $permissions->count() }}</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ══ ROLES TAB ══════════════════════════════════════════════════════════ --}}
    @if($activeTab === 'roles')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
            <span class="font-weight-bold text-dark">All Roles</span>
            <button wire:click="openCreateRole" class="btn btn-sm btn-primary shadow-none">
                <i class="fas fa-plus mr-1"></i> New Role
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-uppercase font-weight-bold text-muted">
                        <th class="pl-4 border-0">Role Name</th>
                        <th class="border-0">Permissions</th>
                        <th class="border-0">Users</th>
                        <th class="border-0 text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td class="pl-4 py-3">
                                <div class="d-flex align-items-center">
                                    @php
                                        $roleColor = match($role->name) {
                                            'Super Admin' => '#6610f2',
                                            'Manager'     => '#fd7e14',
                                            'Doctor'      => '#0dcaf0',
                                            default       => '#0d6efd',
                                        };
                                    @endphp
                                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0"
                                         style="width:36px;height:36px;background:{{ $roleColor }}22;">
                                        <i class="fas fa-user-tag" style="color:{{ $roleColor }};font-size:0.8rem;"></i>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold">{{ $role->name }}</div>
                                        @if(in_array($role->name, ['Super Admin']))
                                            <span class="badge badge-pill" style="background:#6610f222;color:#6610f2;font-size:0.7rem;">Protected</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($role->permissions_count > 0)
                                    <span class="badge badge-primary badge-pill px-2">{{ $role->permissions_count }} permission{{ $role->permissions_count !== 1 ? 's' : '' }}</span>
                                @else
                                    <span class="text-muted small">None assigned</span>
                                @endif
                            </td>
                            <td>
                                @if($role->users_count > 0)
                                    <span class="badge badge-success badge-pill px-2">{{ $role->users_count }} user{{ $role->users_count !== 1 ? 's' : '' }}</span>
                                @else
                                    <span class="text-muted small">No users</span>
                                @endif
                            </td>
                            <td class="pr-4 text-right text-nowrap">
                                <button wire:click="openEditRole({{ $role->id }})"
                                        class="btn btn-sm btn-outline-primary shadow-none mr-1"
                                        title="Edit role &amp; permissions">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                                @if(!in_array($role->name, ['Super Admin']))
                                    <button onclick="confirmDeleteRole({{ $role->id }}, {{ json_encode($role->name) }}, {{ $role->users_count }})"
                                            class="btn btn-sm btn-outline-danger shadow-none"
                                            title="Delete role">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary shadow-none" disabled title="Protected role">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-user-tag fa-2x mb-2 d-block opacity-50"></i>
                                No roles found. Create one to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- ══ PERMISSIONS TAB ════════════════════════════════════════════════════ --}}
    @if($activeTab === 'permissions')
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between py-3">
            <div>
                <span class="font-weight-bold text-dark">All Permissions</span>
                <div class="text-muted small mt-1">Use lowercase with spaces, e.g. <code>manage users</code>, <code>view reports</code></div>
            </div>
            <button wire:click="openCreatePermission" class="btn btn-sm btn-primary shadow-none">
                <i class="fas fa-plus mr-1"></i> New Permission
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="small text-uppercase font-weight-bold text-muted">
                        <th class="pl-4 border-0">Permission</th>
                        <th class="border-0">Assigned to Roles</th>
                        <th class="border-0 text-right pr-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($permissions as $perm)
                        <tr>
                            <td class="pl-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center mr-3 flex-shrink-0"
                                         style="width:36px;height:36px;background:#19875422;">
                                        <i class="fas fa-key" style="color:#198754;font-size:0.8rem;"></i>
                                    </div>
                                    <code class="text-dark" style="font-size:0.85rem;">{{ $perm->name }}</code>
                                </div>
                            </td>
                            <td>
                                @forelse($perm->roles as $r)
                                    <span class="badge badge-secondary badge-pill px-2 mr-1">{{ $r->name }}</span>
                                @empty
                                    <span class="text-muted small">Not assigned</span>
                                @endforelse
                            </td>
                            <td class="pr-4 text-right text-nowrap">
                                <button wire:click="openEditPermission({{ $perm->id }})"
                                        class="btn btn-sm btn-outline-primary shadow-none mr-1"
                                        title="Rename permission">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                                <button onclick="confirmDeletePermission({{ $perm->id }}, {{ json_encode($perm->name) }}, {{ $perm->roles->count() }})"
                                        class="btn btn-sm btn-outline-danger shadow-none"
                                        title="Delete permission">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="fas fa-key fa-2x mb-2 d-block opacity-50"></i>
                                No permissions defined yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>{{-- /.container-fluid --}}

{{-- ══ ROLE MODAL ══════════════════════════════════════════════════════════════ --}}
{{-- wire:ignore.self: Bootstrap controls the modal's own attributes (display, aria-*) --}}
{{-- All interactive buttons use onclick + @this.call() — wire:click is unreliable    --}}
{{-- after Bootstrap moves the modal element to <body>.                               --}}
<div wire:ignore.self class="modal fade" id="roleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="roleModalTitle">
                    <i class="fas fa-user-tag mr-2"></i> New Role
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label class="font-weight-bold small">Role Name <span class="text-danger">*</span></label>
                    <input wire:model.defer="roleName"
                           id="roleNameInput"
                           type="text"
                           class="form-control @error('roleName') is-invalid @enderror"
                           placeholder="e.g. Pharmacist, Lab Technician, Receptionist">
                    @error('roleName')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="font-weight-bold small">
                        Dashboard After Login
                        <span class="text-muted font-weight-normal">— where this role lands on login</span>
                    </label>
                    <select wire:model.defer="dashboardRoute" class="form-control">
                        <option value="">— User Profile (default fallback) —</option>
                        <option value="admin.dashboard">Admin Dashboard</option>
                        <option value="doctor.dashboard">Doctor Dashboard</option>
                        <option value="secretary.dashboard">Secretary Dashboard</option>
                        <option value="cashier.seller-desk">Cashier POS Desk</option>
                        <option value="user.profile">User Profile Page</option>
                    </select>
                    <small class="text-muted">Only applies to custom roles; built-in roles always use their own dashboard.</small>
                </div>

                <div class="form-group mb-0">
                    <label class="font-weight-bold small d-block mb-2">
                        Permissions
                        <span class="text-muted font-weight-normal">— select what this role can do</span>
                    </label>

                    @if($permissions->isEmpty())
                        <div class="alert alert-info border-0 small mb-0">
                            <i class="fas fa-info-circle mr-1"></i>
                            No permissions exist yet. Create some in the <strong>Permissions</strong> tab first.
                        </div>
                    @else
                        <div class="row">
                            @foreach($permissions as $perm)
                                <div class="col-md-6 col-lg-4">
                                    <label class="d-flex align-items-center p-2 rounded mb-1"
                                           style="cursor:pointer;"
                                           onmouseover="this.style.background='#f0f4ff'"
                                           onmouseout="this.style.background=''">
                                        <input type="checkbox"
                                               wire:model="selectedPermissions"
                                               value="{{ $perm->id }}"
                                               class="mr-2"
                                               style="width:16px;height:16px;flex-shrink:0;">
                                        <code class="small">{{ $perm->name }}</code>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-2 text-muted small">
                            <i class="fas fa-info-circle mr-1"></i>
                            <strong>Super Admin</strong> always has full access regardless of this list.
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="roleSaveBtn" onclick="submitRole()">
                    <i class="fas fa-save mr-1"></i>
                    <span id="roleSaveBtnText">Create Role</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══ PERMISSION MODAL ════════════════════════════════════════════════════════ --}}
<div wire:ignore.self class="modal fade" id="permissionModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="permissionModalTitle">
                    <i class="fas fa-key mr-2"></i> New Permission
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-2">
                    <label class="font-weight-bold small">Permission Name <span class="text-danger">*</span></label>
                    <input wire:model.defer="permissionName"
                           id="permissionNameInput"
                           type="text"
                           class="form-control @error('permissionName') is-invalid @enderror"
                           placeholder="e.g. manage billing, view reports, process refunds">
                    @error('permissionName')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="small text-muted">
                    <i class="fas fa-lightbulb mr-1 text-warning"></i>
                    Use lowercase with spaces. Assign it to roles via the <strong>Edit Role</strong> dialog.
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="permissionSaveBtn" onclick="submitPermission()">
                    <i class="fas fa-save mr-1"></i>
                    <span id="permissionSaveBtnText">Create Permission</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // ── Modal open/close events ───────────────────────────────────────────────
    // isEdit is passed in event.detail from the PHP component, not from a Blade expression,
    // so this listener only runs once on page-load and always reads fresh data from the event.
    window.addEventListener('show-roleModal', function (e) {
        var isEdit = e.detail && e.detail.isEdit;
        document.getElementById('roleModalTitle').innerHTML =
            '<i class="fas fa-user-tag mr-2"></i> ' + (isEdit ? 'Edit Role' : 'New Role');
        document.getElementById('roleSaveBtnText').textContent = isEdit ? 'Save Changes' : 'Create Role';
        var saveBtn = document.getElementById('roleSaveBtn');
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="fas fa-save mr-1"></i> <span id="roleSaveBtnText">' +
            (isEdit ? 'Save Changes' : 'Create Role') + '</span>';
        $('#roleModal').modal('show');
    });

    window.addEventListener('hide-roleModal', function () { $('#roleModal').modal('hide'); });

    window.addEventListener('show-permissionModal', function (e) {
        var isEdit = e.detail && e.detail.isEdit;
        document.getElementById('permissionModalTitle').innerHTML =
            '<i class="fas fa-key mr-2"></i> ' + (isEdit ? 'Edit Permission' : 'New Permission');
        var saveBtn = document.getElementById('permissionSaveBtn');
        saveBtn.disabled = false;
        saveBtn.innerHTML = '<i class="fas fa-save mr-1"></i> <span id="permissionSaveBtnText">' +
            (isEdit ? 'Save Changes' : 'Create Permission') + '</span>';
        $('#permissionModal').modal('show');
    });

    window.addEventListener('hide-permissionModal', function () { $('#permissionModal').modal('hide'); });

    // ── Save actions (called from modal buttons) ──────────────────────────────
    function submitRole() {
        var btn = document.getElementById('roleSaveBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving…';
        @this.call('saveRole').then(function () {
            // On validation error Livewire resolves the promise without hiding the modal;
            // re-enable the button so the user can correct and retry.
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save mr-1"></i> Save';
        });
    }

    function submitPermission() {
        var btn = document.getElementById('permissionSaveBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving…';
        @this.call('savePermission').then(function () {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-save mr-1"></i> Save';
        });
    }

    // ── Delete confirmations ──────────────────────────────────────────────────
    function confirmDeleteRole(roleId, roleName, userCount) {
        if (userCount > 0) {
            Swal.fire({
                title: 'Cannot Delete Role',
                html: '<p>The role <strong>' + roleName + '</strong> is assigned to <strong>' + userCount + '</strong> user(s).</p>' +
                      '<p class="mb-0">Re-assign those users to a different role before deleting.</p>',
                icon: 'error',
                confirmButtonColor: '#3085d6',
            });
            return;
        }
        Swal.fire({
            title: 'Delete Role?',
            html: 'The role <strong>' + roleName + '</strong> will be permanently removed.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then(function (result) {
            if (result.isConfirmed) { @this.call('deleteRole', roleId); }
        });
    }

    function confirmDeletePermission(permId, permName, roleCount) {
        var note = roleCount > 0
            ? '<p class="text-warning mt-2 mb-0"><i class="fas fa-exclamation-triangle mr-1"></i>' +
              'Assigned to <strong>' + roleCount + '</strong> role(s) — it will be removed from all of them.</p>'
            : '';
        Swal.fire({
            title: 'Delete Permission?',
            html: '<p>Permission <code>' + permName + '</code> will be permanently deleted.</p>' + note,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
        }).then(function (result) {
            if (result.isConfirmed) { @this.call('deletePermission', permId); }
        });
    }
</script>

</div>{{-- end single Livewire root --}}
