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

    public static function getNationalHolidays($year)
    {
        if ($year == 2026) {
            return [
                '2026-01-01' => 'Tahun Baru Masehi',
                '2026-01-18' => "Isra Mi'raj Nabi Muhammad SAW",
                '2026-02-17' => 'Tahun Baru Imlek',
                '2026-03-19' => 'Hari Suci Nyepi',
                '2026-03-20' => 'Hari Raya Idul Fitri 1447 H',
                '2026-03-21' => 'Hari Raya Idul Fitri 1447 H',
                '2026-04-03' => 'Wafat Yesus Kristus',
                '2026-04-05' => 'Hari Paskah',
                '2026-05-01' => 'Hari Buruh Internasional',
                '2026-05-14' => 'Hari Raya Waisak',
                '2026-05-21' => 'Kenaikan Yesus Kristus',
                '2026-05-27' => 'Hari Raya Idul Adha 1447 H',
                '2026-06-01' => 'Hari Lahir Pancasila',
                '2026-06-17' => 'Tahun Baru Islam 1448 H',
                '2026-08-17' => 'Hari Kemerdekaan RI',
                '2026-08-26' => 'Maulid Nabi Muhammad SAW',
                '2026-12-25' => 'Hari Raya Natal',
            ];
        }

        return [
            "$year-01-01" => 'Tahun Baru Masehi',
            "$year-05-01" => 'Hari Buruh Internasional',
            "$year-06-01" => 'Hari Lahir Pancasila',
            "$year-08-17" => 'Hari Kemerdekaan RI',
            "$year-12-25" => 'Hari Raya Natal',
        ];
    }
}
