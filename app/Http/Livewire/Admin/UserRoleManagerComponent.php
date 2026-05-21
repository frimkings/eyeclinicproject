<?php

namespace App\Http\Livewire\Admin;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;

class UserRoleManagerComponent extends Component
{
    use WithPagination, WithFileUploads;

    public $name, $email, $password, $userId;
    public $selectedRoles = [];
    public $isOpen = false;
    public $isEdit = false;

    // Extended profile fields (admin-managed)
    public string $phone        = '';
    public string $staff_id     = '';
    public string $gender       = '';
    public string $date_of_birth= '';
    public string $department   = '';
    public string $hire_date    = '';

    // Admin password reset
    public $resetUserId;
    public $resetUserName;
    public $newPassword;
    public $newPasswordConfirmation;
    public $isResetOpen = false;

    // CSV import
    public $importFile = null;
    public $isImportOpen = false;
    public array $importResults = [];
    
    // Enhanced Search & Filter Properties
    public $search = '';
    public $roleSearch = '';
    public $filterRole = '';
    public $filterStatus = '';
    public $filterEmailVerified = '';
    public $filterDateFrom = '';
    public $filterDateTo = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $showFilters = false;

    private const SORT_WHITELIST = ['name', 'email', 'created_at', 'is_active', 'staff_id', 'id'];

    public function mount(): void
    {
        abort_if(!auth()->user()?->hasRole('Super Admin'), 403);
    }

    protected function rules(): array
    {
        return [
            'name'           => 'required|min:3',
            'email'          => 'required|email|unique:users,email,' . $this->userId,
            'password'       => $this->isEdit ? 'nullable|min:6' : 'required|min:6',
            'selectedRoles'  => 'required|array|min:1',
            'phone'          => 'nullable|string|max:25',
            'staff_id'       => 'nullable|string|max:30|unique:users,staff_id,' . $this->userId,
            'gender'         => 'nullable|in:Male,Female,Other',
            'date_of_birth'  => 'nullable|date|before:today',
            'department'     => 'nullable|string|max:100',
            'hire_date'      => 'nullable|date',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function updatingFilterEmailVerified()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field): void
    {
        if (!in_array($field, self::SORT_WHITELIST, true)) {
            return;
        }

        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFilters()
    {
        $this->reset(['search', 'filterRole', 'filterStatus', 'filterEmailVerified', 'filterDateFrom', 'filterDateTo']);
        $this->resetPage();
    }

    public function render()
    {
        $users = User::query()
            ->with(['roles', 'latestLogin'])
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterRole, function($query) {
                $query->whereHas('roles', function($q) {
                    $q->where('name', $this->filterRole);
                });
            })
            ->when($this->filterStatus !== '', function($query) {
                $query->where('is_active', $this->filterStatus);
            })
            ->when($this->filterEmailVerified !== '', function($query) {
                if ($this->filterEmailVerified === '1') {
                    $query->whereNotNull('email_verified_at');
                } else {
                    $query->whereNull('email_verified_at');
                }
            })
            ->when($this->filterDateFrom, function($query) {
                $query->whereDate('created_at', '>=', $this->filterDateFrom);
            })
            ->when($this->filterDateTo, function($query) {
                $query->whereDate('created_at', '<=', $this->filterDateTo);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $allRoles = Role::where('name', 'like', '%' . $this->roleSearch . '%')->get();
        $availableRoles = Role::all();

        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'verified' => User::whereNotNull('email_verified_at')->count(),
        ];

        return view('livewire.admin.user-role-manager-component', [
            'users' => $users,
            'allRoles' => $allRoles,
            'availableRoles' => $availableRoles,
            'stats' => $stats,
// dd(auth()->user()->getRoleNames()),

        ])->layout('layouts.admin.admin-layout');
        // Add this to UserRoleManagerComponent.php render() to test:

    }


    public function create()
    {
        $this->resetInputFields();
        $this->isEdit = false;
        $this->isOpen = true;
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->userId        = $id;
        $this->name          = $user->name;
        $this->email         = $user->email;
        $this->phone         = $user->phone        ?? '';
        $this->staff_id      = $user->staff_id     ?? '';
        $this->gender        = $user->gender        ?? '';
        $this->date_of_birth = $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '';
        $this->department    = $user->department    ?? '';
        $this->hire_date     = $user->hire_date     ? $user->hire_date->format('Y-m-d') : '';
        $this->selectedRoles = $user->roles->pluck('name')->toArray();
        $this->isEdit        = true;
        $this->isOpen        = true;
    }

    public function store()
    {
        abort_if(!auth()->user()?->hasRole('Super Admin'), 403);
        $this->validate();

        $user = User::updateOrCreate(['id' => $this->userId], [
            'name'          => $this->name,
            'email'         => $this->email,
            'password'      => $this->password ? Hash::make($this->password) : User::find($this->userId)?->password,
            'phone'         => $this->phone        ?: null,
            'staff_id'      => $this->staff_id     ?: null,
            'gender'        => $this->gender        ?: null,
            'date_of_birth' => $this->date_of_birth ?: null,
            'department'    => $this->department    ?: null,
            'hire_date'     => $this->hire_date     ?: null,
        ]);

        $user->syncRoles($this->selectedRoles);
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => $this->userId ? 'Staff updated successfully.' : 'New staff member registered.']);
        $this->closeModal();
    }

    public function toggleStatus($id)
    {
        if (auth()->id() === $id) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Security check: You cannot deactivate your own account.']);
            return;
        }
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'User status updated successfully.']);
    }

    public function export()
    {
        $fileName = 'clinic_staff_' . now()->format('Y-m-d_His') . '.csv';
        
        $users = User::query()
            ->with(['roles', 'latestLogin'])
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterRole, function($query) {
                $query->whereHas('roles', function($q) {
                    $q->where('name', $this->filterRole);
                });
            })
            ->when($this->filterStatus !== '', function($query) {
                $query->where('is_active', $this->filterStatus);
            })
            ->when($this->filterEmailVerified !== '', function($query) {
                if ($this->filterEmailVerified === '1') {
                    $query->whereNotNull('email_verified_at');
                } else {
                    $query->whereNull('email_verified_at');
                }
            })
            ->when($this->filterDateFrom, function($query) {
                $query->whereDate('created_at', '>=', $this->filterDateFrom);
            })
            ->when($this->filterDateTo, function($query) {
                $query->whereDate('created_at', '<=', $this->filterDateTo);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        return response()->stream(function() use($users) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, ['ID', 'Staff ID', 'Name', 'Email', 'Phone', 'Gender', 'DOB', 'Department', 'Hire Date', 'Roles', 'Status', 'Last Login', 'Last IP', 'Member Since']);

            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->staff_id      ?? '—',
                    $user->name,
                    $user->email,
                    $user->phone         ?? '—',
                    $user->gender        ?? '—',
                    $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '—',
                    $user->department    ?? '—',
                    $user->hire_date     ? $user->hire_date->format('Y-m-d') : '—',
                    $user->roles->pluck('name')->implode(' | '),
                    $user->is_active ? 'Active' : 'Inactive',
                    $user->latestLogin ? $user->latestLogin->login_at->toDateTimeString() : 'Never',
                    $user->latestLogin ? $user->latestLogin->ip_address : 'N/A',
                    $user->created_at->toDateTimeString(),
                ]);
            }
            fclose($file);
        }, 200, $headers);
    }

    public function delete($id)
    {
        abort_if(!auth()->user()?->hasRole('Super Admin'), 403);
        if (auth()->id() === $id) {
            $this->dispatchBrowserEvent('notify', ['type' => 'error', 'message' => 'Action denied: Cannot delete self.']);
            return;
        }
        User::findOrFail($id)->delete();
        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Staff member deleted.']);
    }

    public function closeModal() 
    { 
        $this->isOpen = false; 
        $this->resetInputFields(); 
    }

    private function resetInputFields(): void
    {
        $this->reset([
            'name', 'email', 'password', 'userId', 'selectedRoles', 'roleSearch',
            'phone', 'staff_id', 'gender', 'date_of_birth', 'department', 'hire_date',
        ]);
    }

    public function openResetPassword($id)
    {
        $user = User::findOrFail($id);
        $this->resetUserId = $id;
        $this->resetUserName = $user->name;
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
        $this->isResetOpen = true;
    }

    public function doResetPassword()
    {
        abort_if(!auth()->user()?->hasRole('Super Admin'), 403);
        $this->validate([
            'newPassword'             => 'required|min:8|same:newPasswordConfirmation',
            'newPasswordConfirmation' => 'required',
        ], [
            'newPassword.same' => 'Passwords do not match.',
        ]);

        User::findOrFail($this->resetUserId)->update([
            'password' => Hash::make($this->newPassword),
        ]);

        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => "Password for {$this->resetUserName} has been reset successfully."]);
        $this->closeResetModal();
    }

    public function closeResetModal()
    {
        $this->isResetOpen = false;
        $this->reset(['resetUserId', 'resetUserName', 'newPassword', 'newPasswordConfirmation']);
    }

    // ── CSV Import ──────────────────────────────────────────────────────────────

    public function openImport(): void
    {
        $this->importFile    = null;
        $this->importResults = [];
        $this->isImportOpen  = true;
    }

    public function closeImportModal(): void
    {
        $this->isImportOpen  = false;
        $this->importFile    = null;
        $this->importResults = [];
    }

    public function downloadTemplate()
    {
        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=staff_import_template.csv',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->stream(function () {
            $f = fopen('php://output', 'w');
            fputcsv($f, ['name', 'email', 'password', 'role', 'phone', 'staff_id', 'gender', 'date_of_birth', 'department', 'hire_date']);
            fputcsv($f, ['Jane Doe', 'jane@clinic.com', 'secret123', 'Cashier', '+233 50 123 4567', 'EMP-001', 'Female', '1990-05-15', 'Billing', '2024-01-01']);
            fclose($f);
        }, 200, $headers);
    }

    public function importCsv(): void
    {
        abort_if(!auth()->user()?->hasRole('Super Admin'), 403);
        $this->validate(['importFile' => 'required|file|mimes:csv,txt|max:2048']);

        $path    = $this->importFile->getRealPath();
        $lines   = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $rows    = array_map('str_getcsv', $lines);
        $header  = array_shift($rows); // discard header row

        $validRoles = Role::pluck('name')->map(fn ($n) => strtolower($n))->flip()->toArray();
        $results    = ['created' => 0, 'skipped' => 0, 'errors' => []];

        foreach ($rows as $i => $row) {
            $rowNum = $i + 2;
            $row    = array_pad(array_map('trim', $row), 10, '');

            [$name, $email, $password, $roleInput, $phone, $staffId, $gender, $dob, $department, $hireDate] = $row;

            if ($name === '' && $email === '') {
                continue; // blank row
            }

            if ($name === '' || $email === '' || $password === '' || $roleInput === '') {
                $results['errors'][] = "Row {$rowNum}: name, email, password, and role are all required.";
                $results['skipped']++;
                continue;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $results['errors'][] = "Row {$rowNum}: \"{$email}\" is not a valid email address.";
                $results['skipped']++;
                continue;
            }

            if (User::where('email', $email)->exists()) {
                $results['errors'][] = "Row {$rowNum}: email \"{$email}\" already exists — skipped.";
                $results['skipped']++;
                continue;
            }

            $roleKey = strtolower($roleInput);
            if (!isset($validRoles[$roleKey])) {
                $results['errors'][] = "Row {$rowNum}: role \"{$roleInput}\" not found. Available: " . Role::pluck('name')->implode(', ') . '.';
                $results['skipped']++;
                continue;
            }

            $roleName = Role::whereRaw('LOWER(name) = ?', [$roleKey])->value('name');

            try {
                $user = User::create([
                    'name'          => $name,
                    'email'         => $email,
                    'password'      => Hash::make($password),
                    'phone'         => $phone ?: null,
                    'staff_id'      => $staffId ?: null,
                    'gender'        => in_array($gender, ['Male', 'Female', 'Other']) ? $gender : null,
                    'date_of_birth' => $dob ?: null,
                    'department'    => $department ?: null,
                    'hire_date'     => $hireDate ?: null,
                ]);
                $user->assignRole($roleName);
                $results['created']++;
            } catch (\Exception $e) {
                \Log::error("CSV import row {$rowNum} failed", ['email' => $email, 'error' => $e->getMessage()]);
                $results['errors'][] = "Row {$rowNum}: failed to create user — check server log for details.";
                $results['skipped']++;
            }
        }

        $this->importFile    = null;
        $this->importResults = $results;

        if ($results['created'] > 0) {
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'success',
                'message' => "{$results['created']} staff member(s) imported successfully." .
                             ($results['skipped'] > 0 ? " {$results['skipped']} row(s) skipped." : ''),
            ]);
        } else {
            $this->dispatchBrowserEvent('notify', [
                'type'    => 'warning',
                'message' => 'No users were imported. Check the errors below.',
            ]);
        }
    }
}