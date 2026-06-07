<?php

namespace App\Livewire\Attendance;

use App\Models\AttendanceLog;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Attendance History')]
class History extends Component
{
    use WithPagination;

    public $filterMonth;
    public $filterType = '';
    public $filterStatus = '';
    public $searchEmployee = '';
    public $isAdmin = false;

    public function mount()
    {
        $this->filterMonth = now()->format('Y-m');
        $this->isAdmin = Auth::user()->hasAnyRole(['super_admin', 'hr_admin']);
    }

    public function updating($property)
    {
        if (in_array($property, ['filterMonth', 'filterType', 'filterStatus', 'searchEmployee'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        if ($this->isAdmin) {
            // HR / Admin: can query all employees
            $query = AttendanceLog::with(['user', 'branch', 'shift', 'deviceFingerprint']);

            // Filter by employee search
            if ($this->searchEmployee) {
                $query->whereHas('user', function($q) {
                    $q->where('name', 'like', '%' . $this->searchEmployee . '%')
                      ->orWhere('employee_id', 'like', '%' . $this->searchEmployee . '%');
                });
            }
        } else {
            // Regular Employee: restricted to their own logs only
            $query = AttendanceLog::where('user_id', Auth::id())
                ->with(['branch', 'shift', 'deviceFingerprint']);
        }

        // Filter by month
        if ($this->filterMonth) {
            $query->whereYear('timestamp', substr($this->filterMonth, 0, 4))
                  ->whereMonth('timestamp', substr($this->filterMonth, 5, 2));
        }

        // Filter by type
        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        // Filter by status
        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $attendances = $query->orderBy('timestamp', 'desc')->paginate(20);

        return view('livewire.attendance.history', [
            'attendances' => $attendances,
        ]);
    }

    public function approveAttendance($logId)
    {
        if (!$this->isAdmin) {
            abort(403, 'Unauthorized.');
        }

        $log = AttendanceLog::findOrFail($logId);
        $log->status = 'approved';
        $log->save();

        // Also update any linked pending suspicious events
        \App\Models\SuspiciousEvent::where('attendance_log_id', $log->id)
            ->update(['status' => 'resolved']);
        
        session()->flash('success', 'Absensi ' . ($log->user->name ?? 'Karyawan') . ' berhasil disetujui.');
    }

    public function rejectAttendance($logId)
    {
        if (!$this->isAdmin) {
            abort(403, 'Unauthorized.');
        }

        $log = AttendanceLog::findOrFail($logId);
        $log->status = 'rejected';
        $log->save();

        // Also update any linked pending suspicious events
        \App\Models\SuspiciousEvent::where('attendance_log_id', $log->id)
            ->update(['status' => 'rejected']);

        session()->flash('success', 'Absensi ' . ($log->user->name ?? 'Karyawan') . ' berhasil ditolak.');
    }
}
