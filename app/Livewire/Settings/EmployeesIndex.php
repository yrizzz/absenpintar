<?php

namespace App\Livewire\Settings;

use App\Models\User;
use App\Models\Branch;
use App\Models\AuditLog;
use App\Models\DeviceFingerprint;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

#[Layout('layouts.app')]
#[Title('Kelola Karyawan')]
class EmployeesIndex extends Component
{
    use WithPagination;

    // Search and filters (employees table)
    public $search = '';
    public $statusFilter = 'all'; // 'all', 'registered', 'pending'
    public $branchFilter = 'all'; // 'all', branch_id
    public $userSortField = 'name';
    public $userSortDir = 'asc';

    // Registration Modal properties
    public $showRegisterModal = false;
    public $new_name = '';
    public $new_email = '';
    public $new_employee_id = '';
    public $new_phone = '';
    public $new_password = 'Password123!';
    public $new_branch_id = '';
    public $new_work_mode = 'wfo';
    public $new_role = 'employee';

    // Employee Detail & Edit Modal properties
    public $showUserEditModal = false;
    public $selectedUserId = null;
    public $edit_name = '';
    public $edit_email = '';
    public $edit_employee_id = '';
    public $edit_phone = '';
    public $edit_password = '';
    public $edit_branch_id = '';
    public $edit_work_mode = 'wfo';
    public $edit_role = 'employee';
    public $edit_is_active = true;
    public $edit_date_of_birth = '';
    public $edit_joined_at = '';
    public $edit_annual_leave_quota = 12;
    public $userDevices = [];

    // Reset pagination when filters change
    public function updatedSearch() { $this->resetPage('usersPage'); }
    public function updatedStatusFilter() { $this->resetPage('usersPage'); }
    public function updatedBranchFilter() { $this->resetPage('usersPage'); }

    public function sortUsers($field)
    {
        if ($this->userSortField === $field) {
            $this->userSortDir = $this->userSortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->userSortField = $field;
            $this->userSortDir = 'asc';
        }
        $this->resetPage('usersPage');
    }

    public function mount()
    {
        // Enforce strict administrative authorization control
        if (!auth()->check() || !auth()->user()->hasAnyRole(['super_admin', 'hr_admin'])) {
            abort(403, 'Unauthorized access: Gated by HR administrative policy.');
        }
    }

    public function revokeBiometrics($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $files = [
                'master_face/user_' . $user->id . '.jpg',
                'master_face/user_' . $user->id . '_front.jpg',
                'master_face/user_' . $user->id . '_left.jpg',
                'master_face/user_' . $user->id . '_right.jpg',
            ];
            foreach ($files as $file) {
                if (Storage::disk('local')->exists($file)) {
                    Storage::disk('local')->delete($file);
                }
            }

            // WRITE AUTOMATED AUDIT LOG FOR ADMIN REVOCATION
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'biometrics.revoked',
                'model_type' => User::class,
                'model_id' => $user->id,
                'old_values' => [
                    'revoked_employee' => $user->name,
                    'revoked_employee_id' => $user->employee_id,
                ],
                'metadata' => [
                    'mode' => 'admin_force_purge',
                    'timestamp' => now()->toIso8601String(),
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            session()->flash('success', "Kunci biometrik untuk karyawan '{$user->name}' berhasil dicabut dan dihapus.");
        }
    }

    public function enrollUserFace($userId, $base64Data)
    {
        try {
            $user = User::findOrFail($userId);

            if (empty($base64Data)) {
                throw new \Exception('Data gambar wajah tidak valid.');
            }

            // Save master face
            $dataFace = str_replace('data:image/jpeg;base64,', '', $base64Data);
            $dataFace = str_replace('data:image/png;base64,', '', $dataFace);
            $dataFace = str_replace(' ', '+', $dataFace);
            $imageDecoded = base64_decode($dataFace);

            Storage::disk('local')->put('master_face/user_' . $user->id . '_front.jpg', $imageDecoded);
            Storage::disk('local')->put('master_face/user_' . $user->id . '.jpg', $imageDecoded);

            // Write audit log
            AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'biometrics.enrolled_by_admin',
                'model_type' => User::class,
                'model_id' => $user->id,
                'new_values' => [
                    'registered_by' => auth()->user()->name,
                    'is_complete' => true
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            session()->flash('success', "Kunci biometrik wajah untuk '{$user->name}' berhasil didaftarkan oleh HR.");
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal mendaftarkan wajah: ' . $e->getMessage());
        }
    }

    public function registerUser()
    {
        $this->validate([
            'new_name' => 'required|string|max:255',
            'new_email' => 'required|email|unique:users,email',
            'new_employee_id' => 'required|string|unique:users,employee_id|max:50',
            'new_phone' => 'nullable|string|max:20',
            'new_password' => 'required|string|min:6',
            'new_branch_id' => 'required|exists:branches,id',
            'new_work_mode' => 'required|in:wfo,wfh,hybrid',
            'new_role' => 'required|in:employee,manager,hr_admin',
        ]);

        $newUser = User::create([
            'name' => $this->new_name,
            'email' => $this->new_email,
            'employee_id' => $this->new_employee_id,
            'phone' => $this->new_phone,
            'password' => bcrypt($this->new_password),
            'branch_id' => $this->new_branch_id,
            'work_mode' => $this->new_work_mode,
            'status' => 'active',
        ]);

        // Assign Spatie Role
        $newUser->assignRole($this->new_role);

        // WRITE AUTOMATED AUDIT LOG FOR NEW USER CREATION
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user.created',
            'model_type' => User::class,
            'model_id' => $newUser->id,
            'new_values' => [
                'name' => $newUser->name,
                'employee_id' => $newUser->employee_id,
                'email' => $newUser->email,
                'phone' => $newUser->phone,
                'branch' => $newUser->branch->name ?? 'HQ Branch',
                'work_mode' => $newUser->work_mode,
                'role' => $this->new_role,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', "Akun karyawan '{$newUser->name}' berhasil didaftarkan.");

        // Reset variables
        $this->reset(['new_name', 'new_email', 'new_employee_id', 'new_phone', 'new_branch_id', 'new_work_mode', 'new_role']);
        $this->new_password = 'Password123!';
        $this->showRegisterModal = false;
    }

    public function openUserEditModal($userId)
    {
        $this->resetValidation();
        $this->selectedUserId = $userId;
        
        $user = User::findOrFail($userId);
        $this->edit_name = $user->name;
        $this->edit_email = $user->email;
        $this->edit_employee_id = $user->employee_id;
        $this->edit_phone = $user->phone ?? '';
        $this->edit_password = '';
        $this->edit_branch_id = $user->branch_id;
        $this->edit_work_mode = $user->work_mode ?? 'wfo';
        $this->edit_is_active = $user->status === 'active';
        $this->edit_role = $user->roles->first()?->name ?? 'employee';
        $this->edit_date_of_birth = $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '';
        $this->edit_joined_at = $user->joined_at ? $user->joined_at->format('Y-m-d') : '';
        $this->edit_annual_leave_quota = $user->annual_leave_quota ?? 12;
        
        // Fetch devices
        $this->userDevices = $user->deviceFingerprints()->get()->toArray();

        $this->showUserEditModal = true;
    }

    public function toggleDeviceTrust($deviceId)
    {
        $device = DeviceFingerprint::findOrFail($deviceId);
        $device->trusted = !$device->trusted;
        $device->save();

        // Refresh user devices list
        if ($this->selectedUserId) {
            $user = User::findOrFail($this->selectedUserId);
            $this->userDevices = $user->deviceFingerprints()->get()->toArray();
        }

        // Add a system audit log for security purposes
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'device.trust_toggled',
            'model_type' => DeviceFingerprint::class,
            'model_id' => $device->id,
            'new_values' => [
                'device_hash' => $device->device_hash,
                'trusted' => $device->trusted,
                'employee' => $device->user->name
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', $device->trusted ? 'Perangkat berhasil disetujui (Trusted).' : 'Status persetujuan perangkat berhasil dicabut.');
    }

    public function saveUser()
    {
        $this->validate([
            'edit_name' => 'required|string|max:255',
            'edit_email' => 'required|email|unique:users,email,' . $this->selectedUserId,
            'edit_employee_id' => 'required|string|unique:users,employee_id,' . $this->selectedUserId . '|max:50',
            'edit_phone' => 'nullable|string|max:20',
            'edit_password' => 'nullable|string|min:6',
            'edit_branch_id' => 'required|exists:branches,id',
            'edit_work_mode' => 'required|in:wfo,wfh,hybrid',
            'edit_role' => 'required|in:employee,manager,hr_admin,super_admin',
            'edit_date_of_birth' => 'nullable|date',
            'edit_joined_at' => 'nullable|date',
            'edit_annual_leave_quota' => 'required|integer|min:0|max:100',
        ]);

        $user = User::findOrFail($this->selectedUserId);
        
        $data = [
            'name' => $this->edit_name,
            'email' => $this->edit_email,
            'employee_id' => $this->edit_employee_id,
            'phone' => $this->edit_phone ?: null,
            'branch_id' => $this->edit_branch_id,
            'work_mode' => $this->edit_work_mode,
            'status' => $this->edit_is_active ? 'active' : 'inactive',
            'date_of_birth' => $this->edit_date_of_birth ?: null,
            'joined_at' => $this->edit_joined_at ?: null,
            'annual_leave_quota' => $this->edit_annual_leave_quota,
        ];

        if (!empty($this->edit_password)) {
            $data['password'] = bcrypt($this->edit_password);
        }

        $user->update($data);

        // Sync Spatie role
        $user->syncRoles([$this->edit_role]);

        // WRITE AUDIT LOG
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user.updated_by_admin',
            'model_type' => User::class,
            'model_id' => $user->id,
            'new_values' => [
                'name' => $user->name,
                'email' => $user->email,
                'employee_id' => $user->employee_id,
                'phone' => $user->phone,
                'branch' => $user->branch->name ?? 'N/A',
                'work_mode' => $user->work_mode,
                'status' => $user->status,
                'role' => $this->edit_role,
                'date_of_birth' => $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : null,
                'joined_at' => $user->joined_at ? $user->joined_at->format('Y-m-d') : null,
                'annual_leave_quota' => $user->annual_leave_quota,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', "Data karyawan '{$user->name}' berhasil diperbarui.");
        $this->showUserEditModal = false;
    }

    public function deleteUser($userId)
    {
        if ($userId == auth()->id()) {
            session()->flash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            return;
        }

        $user = User::findOrFail($userId);
        $user->delete();

        // WRITE AUDIT LOG
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user.deleted_by_admin',
            'model_type' => User::class,
            'model_id' => $userId,
            'old_values' => [
                'name' => $user->name,
                'email' => $user->email,
                'employee_id' => $user->employee_id,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', "Akun karyawan '{$user->name}' berhasil dihapus permanen dari sistem.");
        $this->showUserEditModal = false;
    }

    public function render()
    {
        $branches = Branch::orderBy('name')->get();

        $query = User::query()->with(['branch', 'roles']);

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('employee_id', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->branchFilter) && $this->branchFilter !== 'all') {
            $query->where('branch_id', $this->branchFilter);
        }

        $allUsers = $query->get()->map(function($u) {
            $angles = 0;
            if (Storage::disk('local')->exists('master_face/user_' . $u->id . '_front.jpg') || Storage::disk('local')->exists('master_face/user_' . $u->id . '.jpg')) {
                $angles++;
            }
            if (Storage::disk('local')->exists('master_face/user_' . $u->id . '_left.jpg')) {
                $angles++;
            }
            if (Storage::disk('local')->exists('master_face/user_' . $u->id . '_right.jpg')) {
                $angles++;
            }
            $u->registered_angles = $angles;
            $u->is_registered = $angles > 0;
            return $u;
        });

        // Filter by registration status
        if ($this->statusFilter === 'registered') {
            $allUsers = $allUsers->filter(fn($u) => $u->is_registered);
        } elseif ($this->statusFilter === 'pending') {
            $allUsers = $allUsers->filter(fn($u) => !$u->is_registered);
        }

        // Sort collection
        $allUsers = $allUsers->sortBy(function ($u) {
            return match ($this->userSortField) {
                'employee_id' => $u->employee_id,
                'email' => mb_strtolower($u->email),
                'branch' => mb_strtolower($u->branch->name ?? ''),
                'is_registered' => $u->is_registered ? 1 : 0,
                default => mb_strtolower($u->name),
            };
        }, SORT_REGULAR, $this->userSortDir === 'desc')->values();

        // Manual pagination
        $userPerPage = 10;
        $userPage = max(1, (int) ($this->paginators['usersPage'] ?? request()->query('usersPage', 1)));
        $usersPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $allUsers->forPage($userPage, $userPerPage)->values(),
            $allUsers->count(),
            $userPerPage,
            $userPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(), 'pageName' => 'usersPage']
        );

        // Stats calculations
        $totalEmployees = User::count();
        $enrolledCount = 0;
        foreach (User::all() as $u) {
            if (Storage::disk('local')->exists('master_face/user_' . $u->id . '_front.jpg') || Storage::disk('local')->exists('master_face/user_' . $u->id . '.jpg')) {
                $enrolledCount++;
            }
        }
        $pendingCount = $totalEmployees - $enrolledCount;
        $enforcementRate = $totalEmployees > 0 ? round(($enrolledCount / $totalEmployees) * 100) : 0;

        return view('livewire.settings.employees-index', [
            'users' => $usersPaginated,
            'branches' => $branches,
            'stats' => [
                'total' => $totalEmployees,
                'enrolled' => $enrolledCount,
                'pending' => $pendingCount,
                'rate' => $enforcementRate
            ]
        ]);
    }
}
