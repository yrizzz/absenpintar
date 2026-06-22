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
    }

    public function updating($property)
    {
        if (in_array($property, ['filterMonth', 'filterType', 'filterStatus', 'searchEmployee', 'viewMode'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        if ($this->viewMode === 'checklist') {
            $usersQuery = \App\Models\User::query();

            if (!$this->isAdmin) {
                $usersQuery->where('id', Auth::id());
            } else {
                if ($this->searchEmployee) {
                    $usersQuery->where(function($q) {
                        $q->where('name', 'like', '%' . $this->searchEmployee . '%')
                          ->orWhere('employee_id', 'like', '%' . $this->searchEmployee . '%');
                    });
                }
            }

            // Paginate users in checklist view
            $users = $usersQuery->orderBy('name', 'asc')->paginate(20);

            // Get user IDs for the current page
            $userIds = $users->pluck('id')->toArray();

            // Load attendance logs for these users for the selected month/year
            $logsQuery = AttendanceLog::whereIn('user_id', $userIds)
                ->with(['branch', 'shift', 'deviceFingerprint']);

            if ($this->filterMonth) {
                $logsQuery->whereYear('timestamp', substr($this->filterMonth, 0, 4))
                          ->whereMonth('timestamp', substr($this->filterMonth, 5, 2));
            }

            if ($this->filterType) {
                $logsQuery->where('type', $this->filterType);
            }

            if ($this->filterStatus) {
                $logsQuery->where('status', $this->filterStatus);
            }

            $logs = $logsQuery->orderBy('timestamp', 'asc')->get();

            // Map logs to employee and day of the month
            $attendanceMatrix = [];
            $tzSetting = cache()->get('settings.timezone', 'Asia/Jakarta');
            $tzLabel = 'WIB';
            if ($tzSetting === 'Asia/Makassar') $tzLabel = 'WITA';
            if ($tzSetting === 'Asia/Jayapura') $tzLabel = 'WIT';

            foreach ($logs as $log) {
                $day = (int)$log->timestamp->format('d');
                $attendanceMatrix[$log->user_id][$day][] = [
                    'id' => $log->id,
                    'type' => $log->type === 'checkin' ? 'Absen Masuk' : ($log->type === 'checkout' ? 'Absen Keluar' : $log->type),
                    'timestamp' => $log->timestamp->timezone($tzSetting)->translatedFormat('H:i:s, d F Y') . ' ' . $tzLabel,
                    'latitude' => $log->latitude,
                    'longitude' => $log->longitude,
                    'accuracy' => $log->accuracy,
                    'ip_address' => $log->ip_address,
                    'work_mode' => strtoupper($log->work_mode ?? 'office'),
                    'risk_score' => $log->risk_score ?? 0,
                    'risk_level' => $log->risk_level === 'high' ? 'Tinggi' : ($log->risk_level === 'medium' ? 'Sedang' : 'Rendah'),
                    'risk_class' => $log->risk_level,
                    'status' => $log->status === 'approved' ? 'Disetujui' : ($log->status === 'flagged' ? 'Dicurigai' : 'Diproses'),
                    'status_class' => $log->status,
                    'is_late' => $log->is_late,
                    'selfie_url' => $log->selfie_path ? asset('storage/' . $log->selfie_path) : null,
                    'notes' => $log->notes ?? 'Tidak ada catatan tambahan.',
                    'branch_name' => $log->branch->name ?? 'HQ Workspace',
                    'device_hash' => substr(md5($log->device_fingerprint_id ?? 'default_fingerprint'), 0, 16),
                    'employee_name' => $log->user->name ?? 'Karyawan',
                    'resolved_address' => $log->metadata['resolved_address'] ?? null
                ];
            }

            // Calculate month days
            $selectedDate = $this->filterMonth ? \Carbon\Carbon::parse($this->filterMonth . '-01') : now();
            $daysInMonth = $selectedDate->daysInMonth;
            $daysList = [];
            for ($d = 1; $d <= $daysInMonth; $d++) {
                $dateObj = $selectedDate->copy()->day($d);
                $daysList[] = [
                    'day' => $d,
                    'isSunday' => $dateObj->isSunday(),
                    'dayName' => ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'][$dateObj->dayOfWeek],
                ];
            }

            return view('livewire.attendance.history', [
                'users' => $users,
                'attendanceMatrix' => $attendanceMatrix,
                'daysList' => $daysList,
                'daysInMonth' => $daysInMonth,
            ]);
        }
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

        $sortField = in_array($this->sortField, $this->sortable, true) ? $this->sortField : 'timestamp';
        $attendances = $query->orderBy($sortField, $this->sortDirection === 'asc' ? 'asc' : 'desc')->paginate(20);

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
