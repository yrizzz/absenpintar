<?php

namespace App\Livewire\Leaves;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Ruang Kerja Cuti')]
class LeavesIndex extends Component
{
    public $step = 'index'; // 'index' or 'create'
    public $type = 'annual';
    public $start_date;
    public $end_date;
    public $reason;

    public function submitRequest()
    {
        $this->validate([
            'type' => 'required|in:annual,sick,special,unpaid',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:500',
        ]);

        $start = \Carbon\Carbon::parse($this->start_date);
        $end = \Carbon\Carbon::parse($this->end_date);
        $totalDays = $start->diffInDays($end) + 1;

        $dbType = $this->type;
        if ($dbType === 'special') {
            $dbType = 'custom';
        }

        \App\Models\LeaveRequest::create([
            'user_id' => auth()->id(),
            'leave_type' => $dbType,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'total_days' => $totalDays,
            'reason' => $this->reason,
            'status' => 'pending',
        ]);

        // WRITE AUDIT LOG
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'leave.created',
            'model_type' => \App\Models\LeaveRequest::class,
            'new_values' => [
                'type' => $this->type,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'total_days' => $totalDays,
                'reason' => $this->reason,
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', 'Permohonan cuti berhasil diajukan dan sedang menunggu tinjauan HR.');
        $this->step = 'index';
        $this->reset(['start_date', 'end_date', 'reason', 'type']);
    }

    public function approveLeave($id)
    {
        $leave = \App\Models\LeaveRequest::findOrFail($id);
        $leave->update([
            'status' => 'hr_approved',
            'hr_id' => auth()->id(),
            'hr_approved_at' => now(),
            'hr_notes' => 'Disetujui secara instan oleh HR/Admin',
        ]);

        // WRITE AUDIT LOG
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'leave.approved',
            'model_type' => \App\Models\LeaveRequest::class,
            'model_id' => $leave->id,
            'new_values' => ['status' => 'hr_approved'],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', "Permohonan cuti dari '{$leave->user->name}' berhasil disetujui.");
    }

    public function rejectLeave($id)
    {
        $leave = \App\Models\LeaveRequest::findOrFail($id);
        $leave->update([
            'status' => 'rejected',
            'hr_id' => auth()->id(),
            'hr_approved_at' => now(),
            'hr_notes' => 'Ditolak oleh HR/Admin',
        ]);

        // WRITE AUDIT LOG
        \App\Models\AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'leave.rejected',
            'model_type' => \App\Models\LeaveRequest::class,
            'model_id' => $leave->id,
            'new_values' => ['status' => 'rejected'],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        session()->flash('success', "Permohonan cuti dari '{$leave->user->name}' telah ditolak.");
    }

    public function render()
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('hr_admin') || $user->hasRole('super_admin');

        // Compute balances based on actual records for the current calendar year
        $currentYear = now()->year;
        
        $approvedAnnual = \App\Models\LeaveRequest::where('user_id', $user->id)
            ->where('leave_type', 'annual')
            ->where('status', 'hr_approved')
            ->whereYear('start_date', $currentYear)
            ->sum('total_days');

        $sickDays = \App\Models\LeaveRequest::where('user_id', $user->id)
            ->where('leave_type', 'sick')
            ->where('status', 'hr_approved')
            ->whereYear('start_date', $currentYear)
            ->sum('total_days');

        $specialDays = \App\Models\LeaveRequest::where('user_id', $user->id)
            ->where('leave_type', 'custom')
            ->where('status', 'hr_approved')
            ->whereYear('start_date', $currentYear)
            ->sum('total_days');

        // Dynamic quota per employee, defaults to 12
        $quota = $user->annual_leave_quota ?? 12;
        $annualBalance = max($quota - $approvedAnnual, 0);

        // Fetch user's own history
        $myLeaves = \App\Models\LeaveRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Fetch all pending requests for HR/Admin approval
        $pendingLeaves = $isAdmin 
            ? \App\Models\LeaveRequest::with('user')->where('status', 'pending')->orderBy('created_at', 'asc')->get()
            : collect([]);

        return view('livewire.leaves.leaves-index', [
            'annualBalance' => $annualBalance,
            'sickDays' => $sickDays,
            'specialDays' => $specialDays,
            'myLeaves' => $myLeaves,
            'pendingLeaves' => $pendingLeaves,
            'isAdmin' => $isAdmin,
        ]);
    }
}
