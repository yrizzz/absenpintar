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
    public $sortField = 'timestamp';
    public $sortDirection = 'desc';
    public $viewMode = 'table';

    // Matrix view
    public $matrix_month;
    public $matrix_year;

    protected array $sortable = ['timestamp', 'type', 'status', 'is_late', 'risk_level', 'accuracy'];

    public function sortBy($field)
    {
        if (!in_array($field, $this->sortable, true)) {
            return;
        }
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function mount()
    {
        $this->filterMonth = now()->format('Y-m');
        $this->isAdmin = Auth::user()->hasAnyRole(['super_admin', 'hr_admin']);
        $this->matrix_month = (string) now()->month;
        $this->matrix_year  = (string) now()->year;
    }

    public function updating($property)
    {
        if (in_array($property, ['filterMonth', 'filterType', 'filterStatus', 'searchEmployee', 'viewMode'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        // ── Table view query ─────────────────────────────────────
        if ($this->isAdmin) {
            $query = AttendanceLog::with(['user', 'branch', 'shift', 'deviceFingerprint']);
            if ($this->searchEmployee) {
                $query->whereHas('user', function($q) {
                    $q->where('name', 'like', '%' . $this->searchEmployee . '%')
                      ->orWhere('employee_id', 'like', '%' . $this->searchEmployee . '%');
                });
            }
        } else {
            $query = AttendanceLog::where('user_id', Auth::id())
                ->with(['branch', 'shift', 'deviceFingerprint']);
        }

        if ($this->filterMonth) {
            $query->whereYear('timestamp', substr($this->filterMonth, 0, 4))
                  ->whereMonth('timestamp', substr($this->filterMonth, 5, 2));
        }
        if ($this->filterType)   { $query->where('type',   $this->filterType); }
        if ($this->filterStatus) { $query->where('status', $this->filterStatus); }

        $sortField  = in_array($this->sortField, $this->sortable, true) ? $this->sortField : 'timestamp';
        $attendances = $query->orderBy($sortField, $this->sortDirection === 'asc' ? 'asc' : 'desc')->paginate(20);

        // ── Matrix view data ─────────────────────────────────────
        $matrixMonth = (int) ($this->matrix_month ?: now()->month);
        $matrixYear  = (int) ($this->matrix_year  ?: now()->year);

        $daysInMonth = \Carbon\Carbon::create($matrixYear, $matrixMonth, 1)->daysInMonth;
        $holidays    = \App\Livewire\Reports\ReportsIndex::getNationalHolidays($matrixYear);
        $matrixDays  = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date       = \Carbon\Carbon::create($matrixYear, $matrixMonth, $d);
            $dateString = $date->toDateString();
            $matrixDays[] = [
                'day'          => $d,
                'date_string'  => $dateString,
                'is_sunday'    => $date->isSunday(),
                'is_holiday'   => !empty($holidays[$dateString]),
                'holiday_name' => $holidays[$dateString] ?? null,
                'day_name'     => $date->translatedFormat('D'),
            ];
        }

        $startOfMonth = \Carbon\Carbon::create($matrixYear, $matrixMonth, 1)->startOfMonth();
        $endOfMonth   = \Carbon\Carbon::create($matrixYear, $matrixMonth, 1)->endOfMonth();

        $logQuery = AttendanceLog::whereBetween('timestamp', [$startOfMonth, $endOfMonth])
            ->where('type', 'checkin');
        if (!$this->isAdmin) {
            $logQuery->where('user_id', Auth::id());
        } elseif ($this->searchEmployee) {
            $logQuery->whereHas('user', function($q) {
                $q->where('name', 'like', '%' . $this->searchEmployee . '%')
                  ->orWhere('employee_id', 'like', '%' . $this->searchEmployee . '%');
            });
        }
        $tz = cache()->get('settings.timezone', 'Asia/Jakarta');
        $matrixLogs = $logQuery->get()->groupBy(function($log) use ($tz) {
            return $log->user_id . '_' . \Carbon\Carbon::parse($log->timestamp)->timezone($tz)->toDateString();
        });

        $leavesQuery = \App\Models\LeaveRequest::where(function($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                  ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                  ->orWhere(function($sub) use ($startOfMonth, $endOfMonth) {
                      $sub->where('start_date', '<=', $startOfMonth)
                          ->where('end_date', '>=', $endOfMonth);
                  });
            })
            ->whereIn('status', ['approved', 'hr_approved']);
        if (!$this->isAdmin) {
            $leavesQuery->where('user_id', Auth::id());
        }
        $matrixLeaves = [];
        foreach ($leavesQuery->get() as $leave) {
            $start = \Carbon\Carbon::parse($leave->start_date);
            $end   = \Carbon\Carbon::parse($leave->end_date);
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                if ($date->between($startOfMonth, $endOfMonth)) {
                    $matrixLeaves[$leave->user_id . '_' . $date->toDateString()] = $leave->type;
                }
            }
        }

        // Users for matrix rows
        $matrixUserQuery = \App\Models\User::query();
        if (!$this->isAdmin) {
            $matrixUserQuery->where('id', Auth::id());
        } elseif ($this->searchEmployee) {
            $term = $this->searchEmployee;
            $matrixUserQuery->where(function($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('employee_id', 'like', "%{$term}%");
            });
        }
        $matrixUsers = $matrixUserQuery->orderBy('name')->get();

        return view('livewire.attendance.history', [
            'attendances'  => $attendances,
            'matrixDays'   => $matrixDays,
            'matrixLogs'   => $matrixLogs,
            'matrixLeaves' => $matrixLeaves,
            'matrixUsers'  => $matrixUsers,
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
