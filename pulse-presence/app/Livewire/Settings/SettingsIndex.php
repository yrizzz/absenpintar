<?php

namespace App\Livewire\Settings;

use App\Models\User;
use App\Models\Branch;
use App\Models\AttendanceLog;
use App\Models\SuspiciousEvent;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

#[Layout('layouts.app')]
#[Title('Settings Workspace')]
class SettingsIndex extends Component
{
    public $activeTab = 'security'; // 'security', 'branches', 'roles'

    // Geofencing parameters
    public $radius = 200;
    public $gps_margin = 15;
    public $biometric_liveness_threshold = 0.95;
    public $require_mfa = true;

    // Working Hour and Overtime parameters
    public $work_hour_start = '08:00';
    public $work_hour_end = '17:00';
    public $grace_period = 15;
    public $timezone = 'Asia/Jakarta';
    public $overtime_min_hours = 1.0;
    public $overtime_full_day_hours = 8.0;

    // Permission rules parameters
    public $permission_max_late_hours = 2.0;
    public $permission_max_early_hours = 2.0;
    public $permission_max_half_day_hours = 4.0;

    // Search and filters
    public $search = '';
    public $statusFilter = 'all'; // 'all', 'registered', 'pending'
    public $branchFilter = 'all'; // 'all', branch_id

    // Registration Modal properties
    public $showRegisterModal = false;
    public $new_name = '';
    public $new_email = '';
    public $new_employee_id = '';
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
    public $edit_password = '';
    public $edit_branch_id = '';
    public $edit_work_mode = 'wfo';
    public $edit_role = 'employee';
    public $edit_is_active = true;
    public $edit_date_of_birth = '';
    public $edit_joined_at = '';
    public $edit_annual_leave_quota = 12;
    public $userDevices = [];

    // Branch CRUD Modal properties
    public $showBranchModal = false;
    public $selectedBranchId = null;
    public $branch_name = '';
    public $branch_code = '';
    public $branch_address = '';
    public $branch_latitude = '';
    public $branch_longitude = '';
    public $branch_radius = 200;
    public $branch_is_active = true;

    public function mount()
    {
        // Enforce strict administrative authorization control
        if (!auth()->check() || !auth()->user()->hasAnyRole(['super_admin', 'hr_admin'])) {
            abort(403, 'Unauthorized access: Gated by HR administrative policy.');
        }

        $this->radius = cache()->get('settings.radius', 200);
        $this->gps_margin = cache()->get('settings.gps_margin', 15);
        $this->biometric_liveness_threshold = cache()->get('settings.biometric_liveness_threshold', 0.95);
        $this->require_mfa = cache()->get('settings.require_mfa', true);

        $this->work_hour_start = cache()->get('settings.work_hour_start', '08:00');
        $this->work_hour_end = cache()->get('settings.work_hour_end', '17:00');
        $this->grace_period = cache()->get('settings.grace_period', 15);
        $this->timezone = cache()->get('settings.timezone', 'Asia/Jakarta');
        $this->overtime_min_hours = cache()->get('settings.overtime_min_hours', 1.0);
        $this->overtime_full_day_hours = cache()->get('settings.overtime_full_day_hours', 8.0);

        $this->permission_max_late_hours = cache()->get('settings.permission_max_late_hours', 2.0);
        $this->permission_max_early_hours = cache()->get('settings.permission_max_early_hours', 2.0);
        $this->permission_max_half_day_hours = cache()->get('settings.permission_max_half_day_hours', 4.0);
    }

    public function saveSettings()
    {
        cache()->forever('settings.radius', $this->radius);
        cache()->forever('settings.gps_margin', $this->gps_margin);
        cache()->forever('settings.biometric_liveness_threshold', $this->biometric_liveness_threshold);
        cache()->forever('settings.require_mfa', $this->require_mfa);

        cache()->forever('settings.work_hour_start', $this->work_hour_start);
        cache()->forever('settings.work_hour_end', $this->work_hour_end);
        cache()->forever('settings.grace_period', $this->grace_period);
        cache()->forever('settings.timezone', $this->timezone);
        cache()->forever('settings.overtime_min_hours', $this->overtime_min_hours);
        cache()->forever('settings.overtime_full_day_hours', $this->overtime_full_day_hours);

        cache()->forever('settings.permission_max_late_hours', $this->permission_max_late_hours);
        cache()->forever('settings.permission_max_early_hours', $this->permission_max_early_hours);
        cache()->forever('settings.permission_max_half_day_hours', $this->permission_max_half_day_hours);

        // WRITE AUTOMATED AUDIT LOG FOR CONFIGURATION CHANGES
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'settings.updated',
            'model_type' => null,
            'model_id' => null,
            'new_values' => [
                'radius' => $this->radius,
                'gps_margin' => $this->gps_margin,
                'biometric_liveness_threshold' => $this->biometric_liveness_threshold,
                'require_mfa' => $this->require_mfa,
                'work_hour_start' => $this->work_hour_start,
                'work_hour_end' => $this->work_hour_end,
                'grace_period' => $this->grace_period,
                'timezone' => $this->timezone,
                'overtime_min_hours' => $this->overtime_min_hours,
                'overtime_full_day_hours' => $this->overtime_full_day_hours,
                'permission_max_late_hours' => $this->permission_max_late_hours,
                'permission_max_early_hours' => $this->permission_max_early_hours,
                'permission_max_half_day_hours' => $this->permission_max_half_day_hours,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', 'Konfigurasi sistem dan batasan keamanan berhasil disimpan.');
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
                if (\Illuminate\Support\Facades\Storage::disk('local')->exists($file)) {
                    \Illuminate\Support\Facades\Storage::disk('local')->delete($file);
                }
            }

            // WRITE AUTOMATED AUDIT LOG FOR ADMIN REVOCATION
            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'action' => 'biometrics.revoked',
                'model_type' => \App\Models\User::class,
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

            \Illuminate\Support\Facades\Storage::disk('local')->put('master_face/user_' . $user->id . '_front.jpg', $imageDecoded);
            \Illuminate\Support\Facades\Storage::disk('local')->put('master_face/user_' . $user->id . '.jpg', $imageDecoded);

            // Write audit log
            \App\Models\AuditLog::create([
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
            'new_password' => 'required|string|min:6',
            'new_branch_id' => 'required|exists:branches,id',
            'new_work_mode' => 'required|in:wfo,wfh,hybrid',
            'new_role' => 'required|in:employee,manager,hr_admin',
        ]);

        $newUser = User::create([
            'name' => $this->new_name,
            'email' => $this->new_email,
            'employee_id' => $this->new_employee_id,
            'password' => bcrypt($this->new_password),
            'branch_id' => $this->new_branch_id,
            'work_mode' => $this->new_work_mode,
            'is_active' => true,
        ]);

        // Assign Spatie Role
        $newUser->assignRole($this->new_role);

        // WRITE AUTOMATED AUDIT LOG FOR NEW USER CREATION
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user.created',
            'model_type' => User::class,
            'model_id' => $newUser->id,
            'new_values' => [
                'name' => $newUser->name,
                'employee_id' => $newUser->employee_id,
                'email' => $newUser->email,
                'branch' => $newUser->branch->name ?? 'HQ Branch',
                'work_mode' => $newUser->work_mode,
                'role' => $this->new_role,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', "Akun karyawan '{$newUser->name}' berhasil didaftarkan.");

        // Reset variables
        $this->reset(['new_name', 'new_email', 'new_employee_id', 'new_branch_id', 'new_work_mode', 'new_role']);
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
        $this->edit_password = '';
        $this->edit_branch_id = $user->branch_id;
        $this->edit_work_mode = $user->work_mode ?? 'wfo';
        $this->edit_is_active = (bool) ($user->is_active ?? true);
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
        $device = \App\Models\DeviceFingerprint::findOrFail($deviceId);
        $device->trusted = !$device->trusted;
        $device->save();

        // Refresh user devices list
        if ($this->selectedUserId) {
            $user = User::findOrFail($this->selectedUserId);
            $this->userDevices = $user->deviceFingerprints()->get()->toArray();
        }

        // Add a system audit log for security purposes
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'device.trust_toggled',
            'model_type' => \App\Models\DeviceFingerprint::class,
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
            'branch_id' => $this->edit_branch_id,
            'work_mode' => $this->edit_work_mode,
            'is_active' => $this->edit_is_active,
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
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'user.updated_by_admin',
            'model_type' => User::class,
            'model_id' => $user->id,
            'new_values' => [
                'name' => $user->name,
                'email' => $user->email,
                'employee_id' => $user->employee_id,
                'branch' => $user->branch->name ?? 'N/A',
                'work_mode' => $user->work_mode,
                'is_active' => $user->is_active,
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
        \App\Models\AuditLog::create([
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


    // ==========================================
    // BRANCH CRUD MANAGEMENT
    // ==========================================

    public function openBranchModal($branchId = null)
    {
        $this->resetValidation();
        $this->selectedBranchId = $branchId;

        if ($branchId) {
            $branch = Branch::findOrFail($branchId);
            $this->branch_name = $branch->name;
            $this->branch_code = $branch->code;
            $this->branch_address = $branch->address;
            $this->branch_latitude = $branch->latitude;
            $this->branch_longitude = $branch->longitude;
            $this->branch_radius = $branch->radius;
            $this->branch_is_active = (bool) $branch->is_active;
        } else {
            $this->reset(['branch_name', 'branch_code', 'branch_address', 'branch_latitude', 'branch_longitude', 'branch_radius', 'branch_is_active']);
            $this->branch_radius = 200;
            $this->branch_is_active = true;
        }

        $this->showBranchModal = true;
    }

    public function saveBranch()
    {
        $rules = [
            'branch_name' => 'required|string|max:255',
            'branch_code' => 'required|string|max:50|unique:branches,code,' . $this->selectedBranchId,
            'branch_address' => 'required|string',
            'branch_latitude' => 'required|numeric|between:-90,90',
            'branch_longitude' => 'required|numeric|between:-180,180',
            'branch_radius' => 'required|integer|min:10',
        ];

        $this->validate($rules);

        $data = [
            'name' => $this->branch_name,
            'code' => $this->branch_code,
            'address' => $this->branch_address,
            'latitude' => $this->branch_latitude,
            'longitude' => $this->branch_longitude,
            'radius' => $this->branch_radius,
            'is_active' => $this->branch_is_active,
        ];

        if ($this->selectedBranchId) {
            $branch = Branch::findOrFail($this->selectedBranchId);
            $branch->update($data);
            $action = 'branch.updated';
            $msg = "Cabang '{$branch->name}' berhasil diperbarui.";
        } else {
            $branch = Branch::create($data);
            $action = 'branch.created';
            $msg = "Cabang '{$branch->name}' berhasil didaftarkan.";
        }

        // WRITE AUDIT LOG
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => Branch::class,
            'model_id' => $branch->id,
            'new_values' => $data,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', $msg);
        $this->showBranchModal = false;
        $this->reset(['branch_name', 'branch_code', 'branch_address', 'branch_latitude', 'branch_longitude', 'branch_radius', 'branch_is_active', 'selectedBranchId']);
    }

    public function deleteBranch($branchId)
    {
        $branch = Branch::findOrFail($branchId);
        $branch->delete();

        // WRITE AUDIT LOG
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'branch.deleted',
            'model_type' => Branch::class,
            'model_id' => $branchId,
            'old_values' => ['name' => $branch->name],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', "Cabang '{$branch->name}' berhasil dihapus.");
    }

    // ==========================================
    // SPATIE ROLE / PERMISSION TOGGLE
    // ==========================================

    public function togglePermission($roleId, $permissionName)
    {
        // Enforce Super Admin only for editing dynamic permissions to avoid lockdown
        if (!auth()->user()->hasRole('super_admin')) {
            session()->flash('error', 'Hanya Super Admin yang dapat mengubah peran & izin keamanan secara dinamis.');
            return;
        }

        $role = Role::findById($roleId);
        if ($role->hasPermissionTo($permissionName)) {
            $role->revokePermissionTo($permissionName);
            $action = 'role.permission.revoked';
            $msg = "Mencabut izin '{$permissionName}' dari peran '{$role->name}'.";
        } else {
            $role->givePermissionTo($permissionName);
            $action = 'role.permission.granted';
            $msg = "Memberikan izin '{$permissionName}' ke peran '{$role->name}'.";
        }

        // Forget spatie permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // WRITE AUDIT LOG
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'new_values' => [
                'role' => $role->name,
                'permission' => $permissionName,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', $msg);
    }

    // ==========================================
    // SYSTEM RESET (SUPER ADMIN ONLY)
    // ==========================================

    public function resetAttendanceToday()
    {
        if (!auth()->user()->hasRole('super_admin')) {
            session()->flash('error', 'Akses ditolak. Hanya Super Admin yang dapat mereset data sistem.');
            return;
        }

        $today = now()->toDateString();
        $logs = AttendanceLog::withTrashed()->whereDate('timestamp', $today)->get();
        $count = $logs->count();

        foreach ($logs as $log) {
            if ($log->selfie_path) Storage::disk('public')->delete($log->selfie_path);
            if ($log->selfie_compressed_path) Storage::disk('public')->delete($log->selfie_compressed_path);
            if ($log->selfie_watermarked_path) Storage::disk('public')->delete($log->selfie_watermarked_path);
            $log->suspiciousEvents()->delete();
            $log->forceDelete();
        }

        \App\Models\AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'system.reset_attendance_today',
            'new_values' => ['deleted_count' => $count, 'date' => $today],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', "Reset berhasil. {$count} data presensi hari ini ({$today}) telah dihapus permanen.");
    }

    public function resetAttendanceAll()
    {
        if (!auth()->user()->hasRole('super_admin')) {
            session()->flash('error', 'Akses ditolak. Hanya Super Admin yang dapat mereset data sistem.');
            return;
        }

        $logs = AttendanceLog::withTrashed()->get();
        $count = $logs->count();

        foreach ($logs as $log) {
            if ($log->selfie_path) Storage::disk('public')->delete($log->selfie_path);
            if ($log->selfie_compressed_path) Storage::disk('public')->delete($log->selfie_compressed_path);
            if ($log->selfie_watermarked_path) Storage::disk('public')->delete($log->selfie_watermarked_path);
            $log->suspiciousEvents()->delete();
            $log->forceDelete();
        }

        \App\Models\AuditLog::create([
            'user_id'    => auth()->id(),
            'action'     => 'system.reset_attendance_all',
            'new_values' => ['deleted_count' => $count],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', "Reset total berhasil. {$count} seluruh data presensi telah dihapus permanen dari sistem.");
    }

    public function render()
    {
        $branches = Branch::all();
        $query = User::query();

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
            if (\Illuminate\Support\Facades\Storage::disk('local')->exists('master_face/user_' . $u->id . '_front.jpg') || \Illuminate\Support\Facades\Storage::disk('local')->exists('master_face/user_' . $u->id . '.jpg')) {
                $angles++;
            }
            if (\Illuminate\Support\Facades\Storage::disk('local')->exists('master_face/user_' . $u->id . '_left.jpg')) {
                $angles++;
            }
            if (\Illuminate\Support\Facades\Storage::disk('local')->exists('master_face/user_' . $u->id . '_right.jpg')) {
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

        // Stats calculations
        $totalEmployees = User::count();
        $enrolledCount = 0;
        foreach (User::all() as $u) {
            if (\Illuminate\Support\Facades\Storage::disk('local')->exists('master_face/user_' . $u->id . '_front.jpg') || \Illuminate\Support\Facades\Storage::disk('local')->exists('master_face/user_' . $u->id . '.jpg')) {
                $enrolledCount++;
            }
        }
        $pendingCount = $totalEmployees - $enrolledCount;
        $enforcementRate = $totalEmployees > 0 ? round(($enrolledCount / $totalEmployees) * 100) : 0;

        // Fetch Spatie Roles & Permissions for administration view
        $roles = Role::with('permissions')->get();
        $allPermissions = Permission::all();

        return view('livewire.settings.settings-index', [
            'users' => $allUsers,
            'branches' => $branches,
            'roles' => $roles,
            'allPermissions' => $allPermissions,
            'stats' => [
                'total' => $totalEmployees,
                'enrolled' => $enrolledCount,
                'pending' => $pendingCount,
                'rate' => $enforcementRate
            ]
        ]);
    }
}
