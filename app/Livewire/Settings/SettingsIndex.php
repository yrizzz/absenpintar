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
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

#[Layout('layouts.app')]
#[Title('Settings Workspace')]
class SettingsIndex extends Component
{
    use WithPagination;

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

    // Company identity (printed on official letters)
    public $company_name = 'PT PresensiKu Indonesia';
    public $company_address = 'Jl. Teknologi No. 1, Jakarta Selatan';
    public $company_phone = '(021) 123-4567';
    public $company_email = 'hrd@presensiku.com';

    // Branch table search & sort
    public $branchSearch = '';
    public $branchSortField = 'name';
    public $branchSortDir = 'asc';

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

    public function updatedBranchSearch() { $this->resetPage('branchesPage'); }

    public function sortBranches($field)
    {
        if ($this->branchSortField === $field) {
            $this->branchSortDir = $this->branchSortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->branchSortField = $field;
            $this->branchSortDir = 'asc';
        }
        $this->resetPage('branchesPage');
    }

    public function mount()
    {
        // Enforce strict administrative authorization control
        if (!auth()->check() || !auth()->user()->hasAnyRole(['super_admin', 'hr_admin'])) {
            abort(403, 'Unauthorized access: Gated by HR administrative policy.');
        }

        if (request()->query('tab') === 'branches') {
            $this->activeTab = 'branches';
        } elseif (request()->query('tab') === 'roles') {
            $this->activeTab = 'roles';
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

        $this->company_name = cache()->get('settings.company_name', $this->company_name);
        $this->company_address = cache()->get('settings.company_address', $this->company_address);
        $this->company_phone = cache()->get('settings.company_phone', $this->company_phone);
        $this->company_email = cache()->get('settings.company_email', $this->company_email);
    }

    public function saveSettings()
    {
        $this->validate([
            'radius' => 'required|numeric|min:10|max:5000',
            'gps_margin' => 'required|numeric|min:0|max:1000',
            'biometric_liveness_threshold' => 'required|numeric|min:0|max:1',
            'work_hour_start' => 'required|date_format:H:i',
            'work_hour_end' => 'required|date_format:H:i|after:work_hour_start',
            'grace_period' => 'required|integer|min:0|max:120',
            'timezone' => 'required|string|max:64',
            'overtime_min_hours' => 'required|numeric|min:0|max:24',
            'overtime_full_day_hours' => 'required|numeric|min:0|max:24',
            'permission_max_late_hours' => 'required|numeric|min:0|max:24',
            'permission_max_early_hours' => 'required|numeric|min:0|max:24',
            'permission_max_half_day_hours' => 'required|numeric|min:0|max:24',
            'company_name' => 'required|string|max:150',
            'company_address' => 'required|string|max:255',
            'company_phone' => 'required|string|max:50',
            'company_email' => 'required|email|max:150',
        ]);

        // Persist to DB + mirror into cache (survives `cache:clear`). Cast scalars
        $values = [
            'radius' => (float) $this->radius,
            'gps_margin' => (float) $this->gps_margin,
            'biometric_liveness_threshold' => (float) $this->biometric_liveness_threshold,
            'require_mfa' => (bool) $this->require_mfa,
            'work_hour_start' => $this->work_hour_start,
            'work_hour_end' => $this->work_hour_end,
            'grace_period' => (int) $this->grace_period,
            'timezone' => $this->timezone,
            'overtime_min_hours' => (float) $this->overtime_min_hours,
            'overtime_full_day_hours' => (float) $this->overtime_full_day_hours,
            'permission_max_late_hours' => (float) $this->permission_max_late_hours,
            'permission_max_early_hours' => (float) $this->permission_max_early_hours,
            'permission_max_half_day_hours' => (float) $this->permission_max_half_day_hours,
            'company_name' => $this->company_name,
            'company_address' => $this->company_address,
            'company_phone' => $this->company_phone,
            'company_email' => $this->company_email,
        ];

        foreach ($values as $key => $value) {
            \App\Models\Setting::put($key, $value);
        }

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
                'company_name' => $this->company_name,
                'company_address' => $this->company_address,
                'company_phone' => $this->company_phone,
                'company_email' => $this->company_email,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', 'Konfigurasi sistem dan batasan keamanan berhasil disimpan.');
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
        $branches = Branch::orderBy('name')->get();

        $branchSortAllowed = ['name', 'code', 'radius', 'is_active'];
        $bSort = in_array($this->branchSortField, $branchSortAllowed, true) ? $this->branchSortField : 'name';
        $branchesTable = Branch::query()
            ->when($this->branchSearch !== '', function ($q) {
                $s = $this->branchSearch;
                $q->where(function ($w) use ($s) {
                    $w->where('name', 'like', "%{$s}%")
                      ->orWhere('code', 'like', "%{$s}%")
                      ->orWhere('address', 'like', "%{$s}%");
                });
            })
            ->orderBy($bSort, $this->branchSortDir === 'desc' ? 'desc' : 'asc')
            ->paginate(6, ['*'], 'branchesPage');

        $roles = Role::with('permissions')->get();
        $allPermissions = Permission::all();

        return view('livewire.settings.settings-index', [
            'branches' => $branches,
            'branchesTable' => $branchesTable,
            'roles' => $roles,
            'allPermissions' => $allPermissions,
        ]);
    }
}
