<?php

namespace App\Livewire\Leaves;

use App\Models\AuditLog;
use App\Models\LeaveRequest;
use App\Notifications\RequestStatusNotification;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Ruang Kerja Cuti')]
class LeavesIndex extends Component
{
    use WithFileUploads;

    public $step = 'index'; // 'index' or 'create'

    // Form fields
    public $type = 'annual';
    public $start_date;
    public $end_date;
    public $reason;
    public $attachment;

    // Approval action modal state
    public $showActionModal = false;
    public $actionId = null;
    public $actionStage = null; // 'manager' or 'hr'
    public $actionMode = null;  // 'approve' or 'reject'
    public $actionNotes = '';

    public function submitRequest()
    {
        $this->validate([
            'type' => 'required|in:annual,sick,special,unpaid',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
            // Medical certificate is mandatory for sick leave, optional otherwise.
            'attachment' => ($this->type === 'sick' ? 'required' : 'nullable') . '|file|max:2048|mimes:pdf,jpg,jpeg,png',
        ], [], [
            'attachment' => 'lampiran surat keterangan',
        ]);

        $start = \Carbon\Carbon::parse($this->start_date);
        $end = \Carbon\Carbon::parse($this->end_date);
        $totalDays = $start->diffInDays($end) + 1;

        $dbType = $this->type === 'special' ? 'custom' : $this->type;

        // Guard 1: prevent overlapping leave requests that are still active (not rejected).
        $overlap = LeaveRequest::where('user_id', auth()->id())
            ->where('status', '!=', 'rejected')
            ->whereDate('start_date', '<=', $this->end_date)
            ->whereDate('end_date', '>=', $this->start_date)
            ->exists();
        if ($overlap) {
            $this->addError('start_date', 'Anda sudah memiliki pengajuan cuti aktif yang beririsan dengan rentang tanggal ini.');
            return;
        }

        // Guard 2: enforce annual leave quota (reserve approved + in-flight requests).
        if ($dbType === 'annual') {
            $quota = auth()->user()->annual_leave_quota ?? 12;
            $reserved = LeaveRequest::where('user_id', auth()->id())
                ->where('leave_type', 'annual')
                ->whereIn('status', ['pending', 'manager_approved', 'hr_approved'])
                ->whereYear('start_date', $start->year)
                ->sum('total_days');

            if (($reserved + $totalDays) > $quota) {
                $remaining = max($quota - $reserved, 0);
                $this->addError('type', "Kuota cuti tahunan tidak mencukupi. Sisa kuota Anda {$remaining} hari, tetapi Anda mengajukan {$totalDays} hari.");
                return;
            }
        }

        $attachmentPath = null;
        if ($this->attachment) {
            $attachmentPath = $this->attachment->store('leave_attachments', 'public');
        }

        $leave = LeaveRequest::create([
            'user_id' => auth()->id(),
            'leave_type' => $dbType,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'total_days' => $totalDays,
            'reason' => $this->reason,
            'attachment_path' => $attachmentPath,
            'status' => 'pending',
        ]);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'leave.created',
            'model_type' => LeaveRequest::class,
            'model_id' => $leave->id,
            'new_values' => [
                'type' => $dbType,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'total_days' => $totalDays,
                'reason' => $this->reason,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', 'Permohonan cuti berhasil diajukan dan sedang menunggu persetujuan Manajer.');
        $this->step = 'index';
        $this->reset(['start_date', 'end_date', 'reason', 'type', 'attachment']);
    }

    /**
     * Manager (or super admin) approves the first stage: pending -> manager_approved.
     */
    public function managerApprove($id, $notes = '')
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['manager', 'super_admin'])) {
            session()->flash('error', 'Anda tidak memiliki wewenang untuk persetujuan tingkat Manajer.');
            return;
        }

        $leave = LeaveRequest::findOrFail($id);

        if ($leave->user_id === $user->id) {
            session()->flash('error', 'Keamanan Sistem: Anda tidak diizinkan menyetujui permohonan cuti Anda sendiri.');
            return;
        }
        if ($leave->status !== 'pending') {
            session()->flash('error', 'Permohonan ini sudah diproses dan tidak menunggu persetujuan Manajer lagi.');
            return;
        }

        $leave->update([
            'status' => 'manager_approved',
            'manager_id' => $user->id,
            'manager_approved_at' => now(),
            'manager_notes' => $notes ?: 'Disetujui oleh Manajer',
        ]);

        $this->writeAudit('leave.manager_approved', $leave, ['status' => 'manager_approved', 'notes' => $notes]);
        $leave->user->notify(new RequestStatusNotification(
            'Cuti disetujui Manajer',
            'Permohonan cuti Anda telah disetujui Manajer dan diteruskan ke HR untuk finalisasi.',
            route('leaves.index'),
            'info'
        ));
        session()->flash('success', "Permohonan cuti '{$leave->user->name}' disetujui di tingkat Manajer dan diteruskan ke HR.");
    }

    /**
     * HR (or super admin) finalizes: manager_approved -> hr_approved.
     */
    public function hrApprove($id, $notes = '')
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['hr_admin', 'super_admin'])) {
            session()->flash('error', 'Anda tidak memiliki wewenang untuk finalisasi HR.');
            return;
        }

        $leave = LeaveRequest::findOrFail($id);

        if ($leave->user_id === $user->id) {
            session()->flash('error', 'Keamanan Sistem: Anda tidak diizinkan menyetujui permohonan cuti Anda sendiri.');
            return;
        }
        if ($leave->status !== 'manager_approved') {
            session()->flash('error', 'Permohonan harus disetujui Manajer terlebih dahulu sebelum difinalisasi HR.');
            return;
        }

        $leave->update([
            'status' => 'hr_approved',
            'hr_id' => $user->id,
            'hr_approved_at' => now(),
            'hr_notes' => $notes ?: 'Disetujui & difinalisasi oleh HR',
        ]);

        $this->writeAudit('leave.hr_approved', $leave, ['status' => 'hr_approved', 'notes' => $notes]);
        $leave->user->notify(new RequestStatusNotification(
            'Cuti disetujui',
            'Selamat! Permohonan cuti Anda telah disetujui penuh oleh HR.',
            route('leaves.index'),
            'approved'
        ));
        session()->flash('success', "Permohonan cuti '{$leave->user->name}' telah disetujui penuh (final).");
    }

    /**
     * Either stage can reject. Records who rejected via the appropriate notes column.
     */
    public function rejectLeave($id, $notes = '')
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['manager', 'hr_admin', 'super_admin'])) {
            session()->flash('error', 'Anda tidak memiliki wewenang untuk menolak permohonan cuti.');
            return;
        }

        $leave = LeaveRequest::findOrFail($id);

        if ($leave->user_id === $user->id) {
            session()->flash('error', 'Keamanan Sistem: Anda tidak diizinkan menolak permohonan cuti Anda sendiri.');
            return;
        }
        if (in_array($leave->status, ['hr_approved', 'rejected'])) {
            session()->flash('error', 'Permohonan ini sudah final dan tidak dapat ditolak lagi.');
            return;
        }

        $isHrStage = $leave->status === 'manager_approved';
        $updateData = ['status' => 'rejected'];
        if ($isHrStage) {
            $updateData['hr_id'] = $user->id;
            $updateData['hr_approved_at'] = now();
            $updateData['hr_notes'] = $notes ?: 'Ditolak oleh HR';
        } else {
            $updateData['manager_id'] = $user->id;
            $updateData['manager_approved_at'] = now();
            $updateData['manager_notes'] = $notes ?: 'Ditolak oleh Manajer';
        }

        $leave->update($updateData);

        $this->writeAudit('leave.rejected', $leave, $updateData);
        $leave->user->notify(new RequestStatusNotification(
            'Cuti ditolak',
            'Mohon maaf, permohonan cuti Anda ditolak' . ($notes ? ': ' . $notes : '.'),
            route('leaves.index'),
            'rejected'
        ));
        session()->flash('success', "Permohonan cuti dari '{$leave->user->name}' telah ditolak.");
    }

    // ---- Action modal helpers ----

    public $selectedLeave = null;

    public function openAction($id, $stage)
    {
        $this->actionId = $id;
        $this->actionStage = $stage; // 'manager' | 'hr'
        $this->selectedLeave = LeaveRequest::with('user', 'manager')->find($id);
        $this->actionNotes = '';
        $this->showActionModal = true;
    }

    public function submitAction($mode)
    {
        $this->actionMode = $mode; // 'approve' | 'reject'
        
        // Rejection requires a reason; approval note is optional.
        if ($this->actionMode === 'reject') {
            $this->validate(['actionNotes' => 'required|string|max:500'], [], ['actionNotes' => 'catatan']);
        } else {
            $this->validate(['actionNotes' => 'nullable|string|max:500']);
        }

        if ($this->actionMode === 'reject') {
            $this->rejectLeave($this->actionId, $this->actionNotes);
        } elseif ($this->actionStage === 'manager') {
            $this->managerApprove($this->actionId, $this->actionNotes);
        } else {
            $this->hrApprove($this->actionId, $this->actionNotes);
        }

        $this->showActionModal = false;
        $this->reset(['actionId', 'actionStage', 'actionMode', 'actionNotes', 'selectedLeave']);
    }

    protected function writeAudit(string $action, LeaveRequest $leave, array $newValues): void
    {
        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => LeaveRequest::class,
            'model_id' => $leave->id,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function render()
    {
        $user = auth()->user();
        $isManager = $user->hasAnyRole(['manager', 'super_admin']);
        $isHr = $user->hasAnyRole(['hr_admin', 'super_admin']);
        $isAdmin = $isManager || $isHr;

        $currentYear = now()->year;

        $approvedAnnual = LeaveRequest::where('user_id', $user->id)
            ->where('leave_type', 'annual')
            ->where('status', 'hr_approved')
            ->whereYear('start_date', $currentYear)
            ->sum('total_days');

        $sickDays = LeaveRequest::where('user_id', $user->id)
            ->where('leave_type', 'sick')
            ->where('status', 'hr_approved')
            ->whereYear('start_date', $currentYear)
            ->sum('total_days');

        $specialDays = LeaveRequest::where('user_id', $user->id)
            ->where('leave_type', 'custom')
            ->where('status', 'hr_approved')
            ->whereYear('start_date', $currentYear)
            ->sum('total_days');

        $quota = $user->annual_leave_quota ?? 12;
        $annualBalance = max($quota - $approvedAnnual, 0);

        $myLeaves = LeaveRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Stage 1 queue: awaiting manager approval.
        $managerQueue = $isManager
            ? LeaveRequest::with('user')->where('status', 'pending')->orderBy('created_at', 'asc')->get()
            : collect([]);

        // Stage 2 queue: awaiting HR finalization.
        $hrQueue = $isHr
            ? LeaveRequest::with(['user', 'manager'])->where('status', 'manager_approved')->orderBy('manager_approved_at', 'asc')->get()
            : collect([]);

        return view('livewire.leaves.leaves-index', [
            'annualBalance' => $annualBalance,
            'sickDays' => $sickDays,
            'specialDays' => $specialDays,
            'myLeaves' => $myLeaves,
            'managerQueue' => $managerQueue,
            'hrQueue' => $hrQueue,
            'isManager' => $isManager,
            'isHr' => $isHr,
            'isAdmin' => $isAdmin,
        ]);
    }
}
