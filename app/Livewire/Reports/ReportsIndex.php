<?php

namespace App\Livewire\Reports;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceLogsExport;

#[Layout('layouts.app')]
#[Title('Laporan & Telemetri Kehadiran')]
class ReportsIndex extends Component
{
    use WithPagination;

    public $report_period = 'monthly';
    public $report_type = 'presence_summary';

    // View Mode: grid (matrix) or list
    public $view_mode = 'grid';
    public $device_view_mode = 'list';
    public $matrix_month;
    public $matrix_year;

    // Absence entry modal properties
    public $showAbsenceModal = false;
    public $selectedAbsenceUserId;
    public $selectedAbsenceUserName;
    public $selectedAbsenceDate;
    public $absenceReason = '';
    public $absenceNotes = '';

    // Interactive recap filters
    public $filter_user_id = '';
    public $filter_branch_id = '';
    public $filter_start_date;
    public $filter_end_date;
    public $filter_type = '';
    public $filter_risk = '';
    public $filter_status = '';
    public $search = '';

    // Free sorting + pagination
    public $sortField = 'timestamp';
    public $sortDirection = 'desc';
    public $perPage = 15;

    // Checklist selection state
    public $selectedLogs = [];
    public $selectAll = false;

    protected array $sortable = ['timestamp', 'accuracy', 'risk_level', 'status', 'type', 'is_late'];

    public function mount()
    {
        $this->matrix_month = (string) now()->month;
        $this->matrix_year = (string) now()->year;
        $this->filter_start_date = now()->startOfMonth()->toDateString();
        $this->filter_end_date = now()->endOfMonth()->toDateString();
    }

    public function updated($name)
    {
        if (str_starts_with($name, 'filter_') || $name === 'search' || $name === 'perPage') {
            $this->resetPage();
            $this->selectAll = false;
        }
    }

    public function sortBy($field)
    {
        if (!in_array($field, $this->sortable)) {
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

    public function updatedSelectAll($value)
    {
        if ($value) {
            $query = \App\Models\AttendanceLog::query();
            $this->applyFilters($query);
            $this->selectedLogs = $query->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedLogs = [];
        }
    }

    public function generateReport()
    {
        session()->flash('success', 'Data laporan telemetri berhasil disusun! Silakan klik tombol di bawah untuk mengunduh Spreadsheet Excel (.xlsx).');
    }

    public function downloadExcel()
    {
        $selectedIds = $this->selectedLogs;
        $reportType = $this->report_type;
        $title = 'Laporan Kehadiran';

        if ($reportType === 'presence_summary') {
            $query = \App\Models\AttendanceLog::with('user')->orderBy('timestamp', 'desc');
            if (!empty($selectedIds)) {
                $query->whereIn('id', $selectedIds);
            } else {
                $this->applyFilters($query);
            }
            $logs = $query->get();
            $title = 'Ringkasan Kehadiran';
        } elseif ($reportType === 'coordinates_log') {
            $query = \App\Models\AttendanceLog::with(['user', 'branch'])->orderBy('timestamp', 'desc');
            if (!empty($selectedIds)) {
                $query->whereIn('id', $selectedIds);
            } else {
                $this->applyFilters($query);
            }
            $logs = $query->get();
            $title = 'Log Koordinat Geofence';
        } elseif ($reportType === 'leaves_audit') {
            $query = \App\Models\LeaveRequest::with('user')->orderBy('start_date', 'desc');
            if (!empty($selectedIds)) {
                $query->whereIn('id', $selectedIds);
            } else {
                if ($this->filter_user_id) {
                    $query->where('user_id', $this->filter_user_id);
                }
                if ($this->filter_branch_id) {
                    $query->whereHas('user', function ($q) {
                        $q->where('branch_id', $this->filter_branch_id);
                    });
                }
                if ($this->filter_start_date) {
                    $query->whereDate('start_date', '>=', $this->filter_start_date);
                }
                if ($this->filter_end_date) {
                    $query->whereDate('end_date', '<=', $this->filter_end_date);
                }
            }
            $logs = $query->get();
            $title = 'Audit Ledger Cuti';
        } else { // system_logs
            $query = \App\Models\DeviceFingerprint::with('user')->orderBy('last_used_at', 'desc');
            if (!empty($selectedIds)) {
                $query->whereIn('id', $selectedIds);
            } else {
                if ($this->filter_user_id) {
                    $query->where('user_id', $this->filter_user_id);
                }
                if ($this->filter_branch_id) {
                    $query->whereHas('user', function ($q) {
                        $q->where('branch_id', $this->filter_branch_id);
                    });
                }
                if ($this->filter_start_date) {
                    $query->whereDate('last_used_at', '>=', $this->filter_start_date);
                }
                if ($this->filter_end_date) {
                    $query->whereDate('last_used_at', '<=', $this->filter_end_date);
                }
            }
            $logs = $query->get();
            $title = 'Audit Perangkat Tepercaya';
        }

        return Excel::download(
            new AttendanceLogsExport($logs, $reportType, $title),
            "Laporan_" . $reportType . "_" . date('Ymd_His') . ".xlsx"
        );
    }

    private function applyFilters($query)
    {
        if ($this->filter_user_id) {
            $query->where('user_id', $this->filter_user_id);
        }
        if ($this->filter_branch_id) {
            $query->where('branch_id', $this->filter_branch_id);
        }
        if ($this->filter_start_date) {
            $query->whereDate('timestamp', '>=', $this->filter_start_date);
        }
        if ($this->filter_end_date) {
            $query->whereDate('timestamp', '<=', $this->filter_end_date);
        }
        if ($this->filter_type) {
            $query->where('type', $this->filter_type);
        }
        if ($this->filter_risk) {
            $query->where('risk_level', $this->filter_risk);
        }
        if ($this->filter_status) {
            $query->where('status', $this->filter_status);
        }
        if ($this->search) {
            $term = $this->search;
            $query->whereHas('user', function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('employee_id', 'like', "%{$term}%");
            });
        }
    }

    public function resetFilters()
    {
        $this->reset([
            'filter_user_id', 'filter_branch_id', 'filter_type', 'filter_risk', 'filter_status', 'search', 'selectedLogs', 'selectAll',
        ]);
        $this->matrix_month = (string) now()->month;
        $this->matrix_year = (string) now()->year;
        $this->filter_start_date = now()->startOfMonth()->toDateString();
        $this->filter_end_date = now()->endOfMonth()->toDateString();
        $this->resetPage();
    }

    public function approveAttendance($logId)
    {
        $log = \App\Models\AttendanceLog::findOrFail($logId);
        $log->status = 'approved';
        $log->save();

        \App\Models\SuspiciousEvent::where('attendance_log_id', $log->id)
            ->update(['status' => 'resolved']);

        session()->flash('success', 'Absensi ' . ($log->user->name ?? 'Karyawan') . ' berhasil disetujui.');
    }

    public function rejectAttendance($logId)
    {
        $log = \App\Models\AttendanceLog::findOrFail($logId);
        $log->status = 'rejected';
        $log->save();

        \App\Models\SuspiciousEvent::where('attendance_log_id', $log->id)
            ->update(['status' => 'rejected']);

        session()->flash('success', 'Absensi ' . ($log->user->name ?? 'Karyawan') . ' berhasil ditolak.');
    }

    public function render()
    {
        $A = \App\Models\AttendanceLog::class;

        // KPIs
        $avgAccuracy = $A::avg('accuracy') ?? 8.2;
        $totalPresenceLogs = $A::where('type', 'checkin')->count();
        $riskEvents = $A::whereIn('risk_level', ['medium', 'high'])->count();
        $overtimeCount = $A::where('type', 'overtime_start')->count();
        $overtimeHours = $overtimeCount * 2.5;

        // Weekly histogram (actual counts)
        $daysOfWeek = ['Monday' => 0, 'Tuesday' => 0, 'Wednesday' => 0, 'Thursday' => 0, 'Friday' => 0];
        $weeklyLogs = $A::where('type', 'checkin')
            ->whereBetween('timestamp', [now()->startOfWeek(), now()->endOfWeek()])
            ->get();
        foreach ($weeklyLogs as $l) {
            $dayName = \Carbon\Carbon::parse($l->timestamp)->format('l');
            if (isset($daysOfWeek[$dayName])) {
                $daysOfWeek[$dayName]++;
            }
        }

        // Risk distribution (donut)
        $riskMedium = $A::where('risk_level', 'medium')->count();
        $riskHigh = $A::where('risk_level', 'high')->count();
        $riskLow = max($A::count() - $riskMedium - $riskHigh, 0);

        // On-time vs late (donut)
        $onTime = $A::where('type', 'checkin')->where('is_late', false)->count();
        $late = $A::where('type', 'checkin')->where('is_late', true)->count();

        $latestDevices = \App\Models\DeviceFingerprint::with('user')->latest()->take(8)->get();
        $employees = \App\Models\User::orderBy('name')->get();
        $branches = \App\Models\Branch::orderBy('name')->get();

        // Matrix calculation logic
        $matrixMonth = (int) ($this->matrix_month ?: now()->month);
        $matrixYear = (int) ($this->matrix_year ?: now()->year);

        $holidays = self::getNationalHolidays($matrixYear);

        $daysInMonth = \Carbon\Carbon::create($matrixYear, $matrixMonth, 1)->daysInMonth;
        $matrixDays = [];
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = \Carbon\Carbon::create($matrixYear, $matrixMonth, $d);
            $dateString = $date->toDateString();
            $holidayName = $holidays[$dateString] ?? null;

            $matrixDays[] = [
                'day' => $d,
                'date_string' => $dateString,
                'is_sunday' => $date->isSunday(),
                'is_holiday' => !empty($holidayName),
                'holiday_name' => $holidayName,
                'day_name' => $date->translatedFormat('D'),
            ];
        }

        $startOfMonth = \Carbon\Carbon::create($matrixYear, $matrixMonth, 1)->startOfMonth();
        $endOfMonth = \Carbon\Carbon::create($matrixYear, $matrixMonth, 1)->endOfMonth();

        // Group attendance logs by user_id and date
        $logQuery = \App\Models\AttendanceLog::whereBetween('timestamp', [$startOfMonth, $endOfMonth])
            ->where('type', 'checkin');
        if ($this->filter_branch_id) {
            $logQuery->where('branch_id', $this->filter_branch_id);
        }
        if ($this->filter_user_id) {
            $logQuery->where('user_id', $this->filter_user_id);
        }
        $matrixLogs = $logQuery->get()->groupBy(function($log) {
            return $log->user_id . '_' . \Carbon\Carbon::parse($log->timestamp)->toDateString();
        });

        // Fetch active leaves within this month
        $matrixLeaves = [];
        $leavesQuery = \App\Models\LeaveRequest::where(function($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                  ->orWhereBetween('end_date', [$startOfMonth, $endOfMonth])
                  ->orWhere(function($sub) use ($startOfMonth, $endOfMonth) {
                      $sub->where('start_date', '<=', $startOfMonth)
                          ->where('end_date', '>=', $endOfMonth);
                  });
            })
            ->where('status', 'approved');
        if ($this->filter_user_id) {
            $leavesQuery->where('user_id', $this->filter_user_id);
        }
        $leavesList = $leavesQuery->get();

        foreach ($leavesList as $leave) {
            $start = \Carbon\Carbon::parse($leave->start_date);
            $end = \Carbon\Carbon::parse($leave->end_date);
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                if ($date->between($startOfMonth, $endOfMonth)) {
                    $matrixLeaves[$leave->user_id . '_' . $date->toDateString()] = $leave->type;
                }
            }
        }

        // Also fetch active approved PermissionRequests of type ijin_tidak_masuk within this month
        $permissionsQuery = \App\Models\PermissionRequest::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('type', 'ijin_tidak_masuk')
            ->where('status', 'approved');
        if ($this->filter_user_id) {
            $permissionsQuery->where('user_id', $this->filter_user_id);
        }
        $permissionsList = $permissionsQuery->get();

        foreach ($permissionsList as $perm) {
            $matrixLeaves[$perm->user_id . '_' . $perm->date->toDateString()] = 'tidak_masuk';
        }

        // Paginate users for the matrix view
        $userQuery = \App\Models\User::query();
        if ($this->search) {
            $term = $this->search;
            $userQuery->where(function($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('employee_id', 'like', "%{$term}%");
            });
        }
        if ($this->filter_branch_id) {
            $userQuery->where('branch_id', $this->filter_branch_id);
        }
        if ($this->filter_user_id) {
            $userQuery->where('id', $this->filter_user_id);
        }
        $matrixUsers = $userQuery->orderBy('name')->paginate($this->perPage);

        // Recap table — filtered, sorted, paginated
        $sortField = in_array($this->sortField, $this->sortable) ? $this->sortField : 'timestamp';
        $sortDir = $this->sortDirection === 'asc' ? 'asc' : 'desc';
        $recapQuery = $A::with(['user', 'branch']);
        $this->applyFilters($recapQuery);
        $recapLogs = $recapQuery->orderBy($sortField, $sortDir)->paginate($this->perPage);

        return view('livewire.reports.reports-index', [
            'avg_accuracy' => round($avgAccuracy, 1),
            'total_presence_logs' => $totalPresenceLogs,
            'risk_events' => $riskEvents,
            'overtime_hours' => $overtimeHours ?: 12.5,
            'weeklyCounts' => array_values($daysOfWeek),
            'riskDistribution' => [$riskLow, $riskMedium, $riskHigh],
            'onTime' => $onTime,
            'late' => $late,
            'latest_devices' => $latestDevices,
            'employees' => $employees,
            'branches' => $branches,
            'recapLogs' => $recapLogs,
            // Matrix additions
            'matrixUsers' => $matrixUsers,
            'matrixDays' => $matrixDays,
            'matrixLogs' => $matrixLogs,
            'matrixLeaves' => $matrixLeaves,
        ]);
    }

    /**
     * National holidays for a given year as ['Y-m-d' => name].
     *
     * Combines the five fixed-date holidays (same date every year) — produced
     * automatically here — with the variable-date and custom holidays managed by
     * admins in Settings → Hari Libur (stored in the `holidays` table). DB entries
     * win on date collisions. Cached per year so the matrix views stay cheap.
     */
    public static function getNationalHolidays($year)
    {
        return \Illuminate\Support\Facades\Cache::remember(
            "holidays.{$year}",
            now()->addHours(12),
            function () use ($year) {
                $holidays = self::fixedNationalHolidays($year);

                if (\Illuminate\Support\Facades\Schema::hasTable('holidays')) {
                    \App\Models\Holiday::whereYear('date', $year)
                        ->orderBy('date')
                        ->get()
                        ->each(function ($holiday) use (&$holidays) {
                            $holidays[$holiday->date->toDateString()] = $holiday->name;
                        });
                }

                ksort($holidays);

                return $holidays;
            }
        );
    }

    /**
     * The five holidays whose date never changes from year to year.
     */
    public static function fixedNationalHolidays($year): array
    {
        return [
            "$year-01-01" => 'Tahun Baru Masehi',
            "$year-05-01" => 'Hari Buruh Internasional',
            "$year-06-01" => 'Hari Lahir Pancasila',
            "$year-08-17" => 'Hari Kemerdekaan RI',
            "$year-12-25" => 'Hari Raya Natal',
        ];
    }

    public function initAbsenceAction($userId, $dateString)
    {
        $user = \App\Models\User::findOrFail($userId);
        $this->selectedAbsenceUserId = $userId;
        $this->selectedAbsenceUserName = $user->name;
        $this->selectedAbsenceDate = $dateString;
        $this->absenceReason = 'Izin Tidak Masuk';
        $this->absenceNotes = '';
        $this->showAbsenceModal = true;
    }

    public function closeAbsenceModal()
    {
        $this->showAbsenceModal = false;
        $this->reset(['selectedAbsenceUserId', 'selectedAbsenceUserName', 'selectedAbsenceDate', 'absenceReason', 'absenceNotes']);
    }

    public function saveAbsence()
    {
        $this->validate([
            'selectedAbsenceUserId' => 'required|exists:users,id',
            'selectedAbsenceDate' => 'required|date',
            'absenceReason' => 'required|string|max:250',
            'absenceNotes' => 'nullable|string|max:500',
        ]);

        // Remove any existing permission requests for the same day to avoid duplicate entries
        \App\Models\PermissionRequest::where('user_id', $this->selectedAbsenceUserId)
            ->where('date', $this->selectedAbsenceDate)
            ->delete();

        // Create an approved PermissionRequest representing this absence/leave
        \App\Models\PermissionRequest::create([
            'user_id' => $this->selectedAbsenceUserId,
            'type' => 'ijin_tidak_masuk',
            'date' => $this->selectedAbsenceDate,
            'reason' => $this->absenceReason,
            'status' => 'approved',
            'status_dept_head' => 'approved',
            'status_hr' => 'approved',
            'dept_head_id' => auth()->id(),
            'hr_id' => auth()->id(),
            'dept_head_approved_at' => now(),
            'hr_approved_at' => now(),
            'approval_notes' => $this->absenceNotes ?: 'Dicatat langsung oleh Admin/HRD.',
        ]);

        session()->flash('success', 'Keterangan tidak masuk untuk ' . $this->selectedAbsenceUserName . ' pada ' . \Carbon\Carbon::parse($this->selectedAbsenceDate)->translatedFormat('d F Y') . ' berhasil disimpan.');
        $this->closeAbsenceModal();
    }
}
