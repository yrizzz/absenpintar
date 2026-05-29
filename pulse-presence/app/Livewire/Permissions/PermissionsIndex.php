<?php

namespace App\Livewire\Permissions;

use App\Models\PermissionRequest;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Izin Kerja')]
class PermissionsIndex extends Component
{
    use WithFileUploads;

    public $step = 'index'; // 'index' or 'create'
    public $activeTab = 'my'; // 'my' or 'review'
    
    // Form fields
    public $type = 'ijin_datang_terlambat';
    public $date;
    public $start_time;
    public $end_time;
    public $reason;
    public $attachment;

    // Filters
    public $statusFilter = 'all';

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
    }

    public function submitRequest()
    {
        $this->validate([
            'type' => 'required|in:ijin_datang_terlambat,ijin_pulang_awal,ijin_tidak_masuk,ijin_setengah_hari',
            'date' => 'required|date',
            'start_time' => 'nullable|required_unless:type,ijin_tidak_masuk',
            'end_time' => 'nullable|required_unless:type,ijin_tidak_masuk',
            'reason' => 'required|string|max:500',
            'attachment' => 'nullable|file|max:2048', // 2MB max
        ]);

        // Auto validation check based on admin config limits
        if ($this->type !== 'ijin_tidak_masuk' && $this->start_time && $this->end_time) {
            $start = strtotime($this->start_time);
            $end = strtotime($this->end_time);
            $durationHours = ($end - $start) / 3600;

            if ($durationHours <= 0) {
                session()->flash('error', 'Waktu selesai harus lebih lambat dari waktu mulai.');
                return;
            }

            if ($this->type === 'ijin_datang_terlambat') {
                $maxHours = (float) cache()->get('settings.permission_max_late_hours', 2.0);
                if ($durationHours > $maxHours) {
                    session()->flash('error', "Durasi izin datang terlambat tidak boleh lebih dari {$maxHours} jam.");
                    return;
                }
            } elseif ($this->type === 'ijin_pulang_awal') {
                $maxHours = (float) cache()->get('settings.permission_max_early_hours', 2.0);
                if ($durationHours > $maxHours) {
                    session()->flash('error', "Durasi izin pulang awal tidak boleh lebih dari {$maxHours} jam.");
                    return;
                }
            } elseif ($this->type === 'ijin_setengah_hari') {
                $maxHours = (float) cache()->get('settings.permission_max_half_day_hours', 4.0);
                if ($durationHours > $maxHours) {
                    session()->flash('error', "Durasi izin setengah hari tidak boleh lebih dari {$maxHours} jam.");
                    return;
                }
            }
        }

        $attachmentPath = null;
        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('permissions_attachments', 'local');
        }

        $permission = PermissionRequest::create([
            'user_id' => auth()->id(),
            'type' => $this->type,
            'date' => $this->date,
            'start_time' => $this->start_time ?: null,
            'end_time' => $this->end_time ?: null,
            'reason' => $this->reason,
            'attachment_path' => $attachmentPath,
            'status_dept_head' => 'pending',
            'status_hr' => 'pending',
            'status' => 'pending',
        ]);

        // WRITE AUDIT LOG
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'permission.created',
            'model_type' => PermissionRequest::class,
            'model_id' => $permission->id,
            'new_values' => [
                'type' => $this->type,
                'date' => $this->date,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'reason' => $this->reason,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', 'Pengajuan izin kerja berhasil dikirim. Menunggu persetujuan Kepala Divisi dan HR.');
        $this->step = 'index';
        $this->reset(['type', 'start_time', 'end_time', 'reason', 'attachment']);
    }

    public function approveDeptHead($id)
    {
        $permission = PermissionRequest::findOrFail($id);
        
        $permission->update([
            'status_dept_head' => 'approved',
            'dept_head_id' => auth()->id(),
            'dept_head_approved_at' => now(),
        ]);

        $this->checkOverallStatus($permission);

        // WRITE AUDIT LOG
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'permission.dept_head_approved',
            'model_type' => PermissionRequest::class,
            'model_id' => $permission->id,
            'new_values' => ['status_dept_head' => 'approved'],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', 'Izin disetujui sebagai Kepala Divisi.');
    }

    public function approveHr($id)
    {
        $permission = PermissionRequest::findOrFail($id);
        
        $permission->update([
            'status_hr' => 'approved',
            'hr_id' => auth()->id(),
            'hr_approved_at' => now(),
        ]);

        $this->checkOverallStatus($permission);

        // WRITE AUDIT LOG
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'permission.hr_approved',
            'model_type' => PermissionRequest::class,
            'model_id' => $permission->id,
            'new_values' => ['status_hr' => 'approved'],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', 'Izin disetujui sebagai HR.');
    }

    public function rejectRequest($id, $notes = 'Ditolak')
    {
        $permission = PermissionRequest::findOrFail($id);

        $user = auth()->user();
        $isManager = $user->hasRole('manager');
        $isHr = $user->hasRole('hr_admin');
        $isSuper = $user->hasRole('super_admin');

        $updateData = [
            'status' => 'rejected',
            'approval_notes' => $notes,
        ];

        if ($isSuper || $isManager) {
            $updateData['status_dept_head'] = 'rejected';
            $updateData['dept_head_id'] = $user->id;
            $updateData['dept_head_approved_at'] = now();
        }

        if ($isSuper || $isHr) {
            $updateData['status_hr'] = 'rejected';
            $updateData['hr_id'] = $user->id;
            $updateData['hr_approved_at'] = now();
        }

        $permission->update($updateData);

        // WRITE AUDIT LOG
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'permission.rejected',
            'model_type' => PermissionRequest::class,
            'model_id' => $permission->id,
            'new_values' => $updateData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', 'Pengajuan izin telah ditolak.');
    }

    protected function checkOverallStatus(PermissionRequest $permission)
    {
        if ($permission->status_dept_head === 'approved' && $permission->status_hr === 'approved') {
            $permission->update(['status' => 'approved']);
        }
    }

    public function render()
    {
        $user = auth()->user();
        $isManager = $user->hasRole('manager') || $user->hasRole('super_admin');
        $isHr = $user->hasRole('hr_admin') || $user->hasRole('super_admin');
        $isAdmin = $isManager || $isHr;

        // Fetch user's own permission logs
        $query = PermissionRequest::where('user_id', $user->id)->orderBy('created_at', 'desc');
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }
        $myPermissions = $query->take(10)->get();

        // Fetch pending requests for managers/HR to approve/reject
        $pendingRequests = collect([]);
        if ($isAdmin) {
            $pendingQuery = PermissionRequest::with('user')->where('status', 'pending');
            
            // Managers see requests pending their signature
            // HR see requests pending their signature
            if ($user->hasRole('manager') && !$user->hasRole('super_admin')) {
                $pendingQuery->where('status_dept_head', 'pending');
            } elseif ($user->hasRole('hr_admin') && !$user->hasRole('super_admin')) {
                $pendingQuery->where('status_hr', 'pending');
            }

            $pendingRequests = $pendingQuery->orderBy('created_at', 'asc')->get();
        }

        return view('livewire.permissions.permissions-index', [
            'myPermissions' => $myPermissions,
            'pendingRequests' => $pendingRequests,
            'isManager' => $isManager,
            'isHr' => $isHr,
            'isAdmin' => $isAdmin,
        ]);
    }
}
