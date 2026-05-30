<?php

namespace App\Livewire\Permissions;

use App\Models\PermissionRequest;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Izin Kerja')]
class PermissionsIndex extends Component
{
    use WithFileUploads, WithPagination;

    public $step = 'index'; // 'index' or 'create'
    public $activeTab = 'my'; // 'my' or 'review'
    
    // Form fields
    public $type = 'ijin_datang_terlambat';
    public $date;
    public $start_time;
    public $end_time;
    public $reason;
    public $attachment;

    // Filters for "My Permissions"
    public $statusFilter = 'all';

    // Filters for "Employee Reviews"
    public $reviewSearch = '';
    public $reviewTypeFilter = 'all';
    public $reviewStatusFilter = 'pending'; // 'pending', 'approved', 'rejected', 'all'

    // Modal Actions State
    public $showRejectionModal = false;
    public $showApprovalModal = false;
    public $showDetailModal = false;
    
    public $selectedRequestIdForAction = null;
    public $selectedRequestForDetail = null;
    public $actionNotes = '';
    public $actionType = 'dept_head'; // 'dept_head' or 'hr'

    public function mount()
    {
        $this->date = now()->format('Y-m-d');
    }

    public function updating($property)
    {
        if (in_array($property, ['statusFilter'])) {
            $this->resetPage('myPage');
        }
        if (in_array($property, ['reviewSearch', 'reviewTypeFilter', 'reviewStatusFilter'])) {
            $this->resetPage('reviewPage');
        }
    }

    public function updatedType($value)
    {
        if ($value === 'ijin_tidak_masuk') {
            $this->reset(['start_time', 'end_time']);
        }
    }

    public function clearMyFilters()
    {
        $this->reset(['statusFilter']);
        $this->resetPage('myPage');
    }

    public function clearReviewFilters()
    {
        $this->reset(['reviewSearch', 'reviewTypeFilter', 'reviewStatusFilter']);
        $this->resetPage('reviewPage');
    }

    public function submitRequest()
    {
        $rules = [
            'type' => 'required|in:ijin_datang_terlambat,ijin_pulang_awal,ijin_tidak_masuk,ijin_setengah_hari',
            'date' => 'required|date',
            'reason' => 'required|string|max:500',
            'attachment' => 'nullable|file|max:2048|mimes:pdf,jpg,jpeg,png',
        ];

        if ($this->type !== 'ijin_tidak_masuk') {
            $rules['start_time'] = 'required';
            $rules['end_time'] = 'required';
        } else {
            $rules['start_time'] = 'nullable';
            $rules['end_time'] = 'nullable';
        }

        $this->validate($rules);

        // Date-range checks (optional safeguard)
        $parsedDate = \Carbon\Carbon::parse($this->date);
        if ($parsedDate->diffInDays(now(), false) > 30) {
            $this->addError('date', 'Anda tidak dapat mengajukan izin untuk tanggal lebih dari 30 hari yang lalu.');
            return;
        }

        // Duplicate submission prevention rules
        // 1. Prevent duplicate submission of same type on same day
        $existsSameType = PermissionRequest::where('user_id', auth()->id())
            ->where('date', $this->date)
            ->where('type', $this->type)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
        if ($existsSameType) {
            $this->addError('date', 'Anda sudah memiliki pengajuan izin aktif dengan tipe yang sama pada tanggal ini.');
            return;
        }

        // 2. Prevent other submissions if a full-day permission is active/pending
        $existsFullDay = PermissionRequest::where('user_id', auth()->id())
            ->where('date', $this->date)
            ->where('type', 'ijin_tidak_masuk')
            ->whereIn('status', ['pending', 'approved'])
            ->exists();
        if ($existsFullDay) {
            $this->addError('date', 'Anda tidak dapat mengajukan izin lain karena sudah memiliki izin tidak masuk (penuh) pada tanggal ini.');
            return;
        }

        // 3. Prevent full-day permission if any other permission is active/pending
        if ($this->type === 'ijin_tidak_masuk') {
            $existsAny = PermissionRequest::where('user_id', auth()->id())
                ->where('date', $this->date)
                ->whereIn('status', ['pending', 'approved'])
                ->exists();
            if ($existsAny) {
                $this->addError('date', 'Anda tidak dapat mengajukan izin tidak masuk karena sudah memiliki pengajuan izin dispensasi lain pada tanggal ini.');
                return;
            }
        }

        // Auto validation check based on admin config limits
        if ($this->type !== 'ijin_tidak_masuk' && $this->start_time && $this->end_time) {
            $start = strtotime($this->start_time);
            $end = strtotime($this->end_time);
            $durationHours = ($end - $start) / 3600;

            if ($durationHours <= 0) {
                $this->addError('end_time', 'Waktu selesai harus lebih lambat dari waktu mulai.');
                return;
            }

            if ($this->type === 'ijin_datang_terlambat') {
                $maxHours = (float) cache()->get('settings.permission_max_late_hours', 2.0);
                if ($durationHours > $maxHours) {
                    $this->addError('end_time', "Durasi izin datang terlambat tidak boleh lebih dari {$maxHours} jam.");
                    return;
                }
            } elseif ($this->type === 'ijin_pulang_awal') {
                $maxHours = (float) cache()->get('settings.permission_max_early_hours', 2.0);
                if ($durationHours > $maxHours) {
                    $this->addError('end_time', "Durasi izin pulang awal tidak boleh lebih dari {$maxHours} jam.");
                    return;
                }
            } elseif ($this->type === 'ijin_setengah_hari') {
                $maxHours = (float) cache()->get('settings.permission_max_half_day_hours', 4.0);
                if ($durationHours > $maxHours) {
                    $this->addError('end_time', "Durasi izin setengah hari tidak boleh lebih dari {$maxHours} jam.");
                    return;
                }
            }
        }

        $attachmentPath = null;
        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('permissions_attachments', 'public');
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
        $this->resetPage('myPage');
    }

    public function approveDeptHead($id, $notes = '')
    {
        $permission = PermissionRequest::findOrFail($id);
        
        if ($permission->user_id === auth()->id()) {
            session()->flash('error', 'Keamanan Sistem: Anda tidak diizinkan menyetujui permohonan izin Anda sendiri.');
            return;
        }

        $updateData = [
            'status_dept_head' => 'approved',
            'dept_head_id' => auth()->id(),
            'dept_head_approved_at' => now(),
        ];

        if (!empty($notes)) {
            $existingNotes = $permission->approval_notes ? $permission->approval_notes . ' | ' : '';
            $updateData['approval_notes'] = $existingNotes . 'Kadiv: ' . $notes;
        }

        $permission->update($updateData);
        $this->checkOverallStatus($permission);

        // WRITE AUDIT LOG
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'permission.dept_head_approved',
            'model_type' => PermissionRequest::class,
            'model_id' => $permission->id,
            'new_values' => $updateData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Refresh detail modal if open
        if ($this->showDetailModal && $this->selectedRequestForDetail && $this->selectedRequestForDetail->id === $permission->id) {
            $this->selectedRequestForDetail = PermissionRequest::with(['user', 'deptHead', 'hr'])->find($permission->id);
        }

        session()->flash('success', 'Izin disetujui sebagai Kepala Divisi.');
    }

    public function approveHr($id, $notes = '')
    {
        $permission = PermissionRequest::findOrFail($id);
        
        if ($permission->user_id === auth()->id()) {
            session()->flash('error', 'Keamanan Sistem: Anda tidak diizinkan menyetujui permohonan izin Anda sendiri.');
            return;
        }

        $updateData = [
            'status_hr' => 'approved',
            'hr_id' => auth()->id(),
            'hr_approved_at' => now(),
        ];

        if (!empty($notes)) {
            $existingNotes = $permission->approval_notes ? $permission->approval_notes . ' | ' : '';
            $updateData['approval_notes'] = $existingNotes . 'HRD: ' . $notes;
        }

        $permission->update($updateData);
        $this->checkOverallStatus($permission);

        // WRITE AUDIT LOG
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'permission.hr_approved',
            'model_type' => PermissionRequest::class,
            'model_id' => $permission->id,
            'new_values' => $updateData,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Refresh detail modal if open
        if ($this->showDetailModal && $this->selectedRequestForDetail && $this->selectedRequestForDetail->id === $permission->id) {
            $this->selectedRequestForDetail = PermissionRequest::with(['user', 'deptHead', 'hr'])->find($permission->id);
        }

        session()->flash('success', 'Izin disetujui sebagai HR Manager.');
    }

    public function rejectRequest($id, $notes = 'Ditolak')
    {
        $permission = PermissionRequest::findOrFail($id);

        if ($permission->user_id === auth()->id()) {
            session()->flash('error', 'Keamanan Sistem: Anda tidak diizinkan menolak permohonan izin Anda sendiri.');
            return;
        }

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

        // Refresh detail modal if open
        if ($this->showDetailModal && $this->selectedRequestForDetail && $this->selectedRequestForDetail->id === $permission->id) {
            $this->selectedRequestForDetail = PermissionRequest::with(['user', 'deptHead', 'hr'])->find($permission->id);
        }

        session()->flash('success', 'Pengajuan izin telah ditolak.');
    }

    protected function checkOverallStatus(PermissionRequest $permission)
    {
        if ($permission->status_dept_head === 'approved' && $permission->status_hr === 'approved') {
            $permission->update(['status' => 'approved']);
        }
    }

    // Modal triggers
    public function openRejectionModal($id)
    {
        $this->selectedRequestIdForAction = $id;
        $this->actionNotes = '';
        $this->showRejectionModal = true;
    }

    public function submitRejection()
    {
        $this->validate([
            'actionNotes' => 'required|string|max:500',
        ]);

        $this->rejectRequest($this->selectedRequestIdForAction, $this->actionNotes);
        
        $this->showRejectionModal = false;
        $this->reset(['selectedRequestIdForAction', 'actionNotes']);
    }

    public function openApprovalModal($id, $roleType)
    {
        $this->selectedRequestIdForAction = $id;
        $this->actionType = $roleType; // 'dept_head' or 'hr'
        $this->actionNotes = '';
        $this->showApprovalModal = true;
    }

    public function submitApproval()
    {
        $this->validate([
            'actionNotes' => 'nullable|string|max:500',
        ]);

        if ($this->actionType === 'dept_head') {
            $this->approveDeptHead($this->selectedRequestIdForAction, $this->actionNotes);
        } else {
            $this->approveHr($this->selectedRequestIdForAction, $this->actionNotes);
        }
        
        $this->showApprovalModal = false;
        $this->reset(['selectedRequestIdForAction', 'actionNotes', 'actionType']);
    }

    public function viewDetail($id)
    {
        $this->selectedRequestForDetail = PermissionRequest::with(['user.branch', 'user.roles', 'deptHead', 'hr'])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function render()
    {
        $user = auth()->user();
        $isManager = $user->hasRole('manager') || $user->hasRole('super_admin');
        $isHr = $user->hasRole('hr_admin') || $user->hasRole('super_admin');
        $isAdmin = $isManager || $isHr;

        // 1. Fetch employee's own permission logs (Paginated)
        $myQuery = PermissionRequest::where('user_id', $user->id)->orderBy('created_at', 'desc');
        if ($this->statusFilter !== 'all') {
            $myQuery->where('status', $this->statusFilter);
        }
        $myPermissions = $myQuery->paginate(6, pageName: 'myPage');

        // 2. Fetch pending/all requests for managers/HR to review (Paginated with search & filters)
        $reviewRequests = collect([]);
        if ($isAdmin) {
            $reviewQuery = PermissionRequest::with(['user', 'deptHead', 'hr']);
            
            // Filter by review tab status
            if ($this->reviewStatusFilter === 'pending') {
                $reviewQuery->where('status', 'pending');
                
                // Specific role assignment queues
                if ($user->hasRole('manager') && !$user->hasRole('super_admin')) {
                    $reviewQuery->where('status_dept_head', 'pending');
                } elseif ($user->hasRole('hr_admin') && !$user->hasRole('super_admin')) {
                    $reviewQuery->where('status_hr', 'pending');
                }
            } elseif ($this->reviewStatusFilter === 'approved') {
                $reviewQuery->where('status', 'approved');
            } elseif ($this->reviewStatusFilter === 'rejected') {
                $reviewQuery->where('status', 'rejected');
            }

            // Filter by type
            if ($this->reviewTypeFilter !== 'all') {
                $reviewQuery->where('type', $this->reviewTypeFilter);
            }

            // Search by employee name or ID
            if (!empty($this->reviewSearch)) {
                $reviewQuery->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->reviewSearch . '%')
                      ->orWhere('employee_id', 'like', '%' . $this->reviewSearch . '%');
                });
            }

            $reviewRequests = $reviewQuery->orderBy('created_at', 'desc')->paginate(10, pageName: 'reviewPage');
        }

        // Count actual raw pending requests for badge indicator
        $badgeQuery = PermissionRequest::where('status', 'pending');
        if ($user->hasRole('manager') && !$user->hasRole('super_admin')) {
            $badgeQuery->where('status_dept_head', 'pending');
        } elseif ($user->hasRole('hr_admin') && !$user->hasRole('super_admin')) {
            $badgeQuery->where('status_hr', 'pending');
        }
        $rawPendingCount = $isAdmin ? $badgeQuery->count() : 0;

        return view('livewire.permissions.permissions-index', [
            'myPermissions' => $myPermissions,
            'reviewRequests' => $reviewRequests,
            'rawPendingCount' => $rawPendingCount,
            'isManager' => $isManager,
            'isHr' => $isHr,
            'isAdmin' => $isAdmin,
        ]);
    }
}
