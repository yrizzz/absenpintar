<?php

namespace App\Livewire;

use App\Models\AttendanceLog;
use App\Models\SuspiciousEvent;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public $todayAttendance;
    public $stats = [];
    public $isAdmin = false;

    // Logged-in user's attendance status for hero actions
    public $hasCheckIn = false;
    public $hasCheckOut = false;
    public $hasTidakMasuk = false;

    // Analytics for the dashboard charts
    public $weekSeries = [];
    public $distribution = [];
    public $spark = [];
    public $metrics = [];
    public $upcoming = [];

    public function mount()
    {
        $user = Auth::user();
        $this->isAdmin = $user->hasAnyRole(['super_admin', 'hr_admin']);
        $this->loadData();
    }

    public function loadData()
    {
        $user = Auth::user();

        // Calculate logged-in user's attendance status for hero actions
        $myTodayLogs = AttendanceLog::where('user_id', $user->id)
            ->whereDate('timestamp', today())
            ->get();
        
        $this->hasCheckIn = $myTodayLogs->where('type', 'checkin')->isNotEmpty();
        $this->hasCheckOut = $myTodayLogs->where('type', 'checkout')->isNotEmpty();
        $this->hasTidakMasuk = \App\Models\PermissionRequest::where('user_id', $user->id)
            ->whereDate('date', today())
            ->where('type', 'ijin_tidak_masuk')
            ->where('status', 'approved')
            ->exists();
        
        if ($this->isAdmin) {
            // HR / Admin: get today's attendance for ALL employees
            $this->todayAttendance = AttendanceLog::with('user', 'branch')
                ->whereDate('timestamp', today())
                ->orderBy('timestamp', 'desc')
                ->get();

            // Calculate company-wide stats
            $this->stats = [
                'total_attendance' => AttendanceLog::whereMonth('timestamp', now()->month)
                    ->where('type', 'checkin')
                    ->count(),
                'on_time' => AttendanceLog::whereMonth('timestamp', now()->month)
                    ->where('type', 'checkin')
                    ->where('is_late', false)
                    ->count(),
                'late' => AttendanceLog::whereMonth('timestamp', now()->month)
                    ->where('type', 'checkin')
                    ->where('is_late', true)
                    ->count(),
                'suspicious_events' => SuspiciousEvent::whereMonth('created_at', now()->month)
                    ->where('status', 'pending')
                    ->count(),
            ];
        } else {
            // Regular Employee: get today's attendance only for themselves
            $this->todayAttendance = AttendanceLog::with('branch')
                ->where('user_id', $user->id)
                ->whereDate('timestamp', today())
                ->orderBy('timestamp', 'desc')
                ->get();

            // Calculate self stats
            $this->stats = [
                'total_attendance' => AttendanceLog::where('user_id', $user->id)
                    ->whereMonth('timestamp', now()->month)
                    ->where('type', 'checkin')
                    ->count(),
                'on_time' => AttendanceLog::where('user_id', $user->id)
                    ->whereMonth('timestamp', now()->month)
                    ->where('type', 'checkin')
                    ->where('is_late', false)
                    ->count(),
                'late' => AttendanceLog::where('user_id', $user->id)
                    ->whereMonth('timestamp', now()->month)
                    ->where('type', 'checkin')
                    ->where('is_late', true)
                    ->count(),
                'suspicious_events' => SuspiciousEvent::where('user_id', $user->id)
                    ->whereMonth('created_at', now()->month)
                    ->where('status', 'pending')
                    ->count(),
            ];
        }

        $this->buildAnalytics();
    }

    /**
     * Build chart/analytics data from real attendance records.
     * Scope is company-wide for admins, otherwise personal.
     */
    protected function buildAnalytics(): void
    {
        $user = Auth::user();
        $tz = cache()->get('settings.timezone', 'Asia/Jakarta');
        $scope = fn ($q) => $this->isAdmin ? $q : $q->where('user_id', $user->id);

        $employeeCount = max(1, \App\Models\User::count());
        $end = now()->copy()->endOfDay();
        $start14 = now()->copy()->subDays(13)->startOfDay();

        // One pass over the last 14 days of check-ins.
        $checkins = $scope(AttendanceLog::query())
            ->where('type', 'checkin')
            ->whereBetween('timestamp', [$start14, $end])
            ->get(['timestamp', 'is_late', 'user_id']);

        $agg = []; // date => ['on'=>, 'late'=>, 'users'=>[]]
        foreach ($checkins as $log) {
            $d = $log->timestamp->timezone($tz)->toDateString();
            $agg[$d]['on'] = ($agg[$d]['on'] ?? 0) + ($log->is_late ? 0 : 1);
            $agg[$d]['late'] = ($agg[$d]['late'] ?? 0) + ($log->is_late ? 1 : 0);
            $agg[$d]['users'][$log->user_id] = true;
        }
        $presentOf = fn ($d) => isset($agg[$d]['users'])
            ? count($agg[$d]['users'])
            : (($agg[$d]['on'] ?? 0) + ($agg[$d]['late'] ?? 0));

        // 14 ordered date keys (oldest -> newest).
        $days = [];
        for ($i = 13; $i >= 0; $i--) {
            $days[] = now()->copy()->subDays($i)->toDateString();
        }

        // Weekly series = last 7 days. Absent is only meaningful on realised
        // working days (weekday, past/today, with company activity).
        $week = [];
        $sumOn = $sumLate = $sumAbsent = 0;
        $bestDay = '-';
        $bestCount = -1;
        foreach (array_slice($days, 7) as $key) {
            $day = \Carbon\Carbon::parse($key);
            $on = $agg[$key]['on'] ?? 0;
            $late = $agg[$key]['late'] ?? 0;
            $present = $presentOf($key);
            $absent = ($this->isAdmin && $present > 0) ? max(0, $employeeCount - $present) : 0;

            $week[] = [
                'label' => $day->translatedFormat('D'),
                'date' => $day->format('j/n'),
                'on_time' => $on,
                'late' => $late,
                'absent' => $absent,
            ];
            $sumOn += $on;
            $sumLate += $late;
            $sumAbsent += $absent;
            if ($on > $bestCount) {
                $bestCount = $on;
                $bestDay = $day->translatedFormat('l');
            }
        }
        $this->weekSeries = $week;

        // Distribution (this week).
        $total = $sumOn + $sumLate + $sumAbsent;
        $pct = fn ($v) => $total > 0 ? round($v / $total * 100, 1) : 0;
        $this->distribution = [
            'on_time' => $sumOn,
            'late' => $sumLate,
            'absent' => $sumAbsent,
            'total' => $total,
            'on_time_pct' => $pct($sumOn),
            'late_pct' => $pct($sumLate),
            'absent_pct' => $pct($sumAbsent),
        ];

        // Attendance rate over realised working days (excludes weekends, today,
        // future and holidays). Admin = avg present share; employee = attended/expected.
        $rateFor = function (array $range) use ($presentOf, $employeeCount) {
            $accum = 0;
            $count = 0;
            foreach ($range as $key) {
                $day = \Carbon\Carbon::parse($key);
                if ($day->isWeekend() || $day->isToday() || $day->isFuture()) {
                    continue;
                }
                $present = $presentOf($key);
                if ($this->isAdmin) {
                    if ($present <= 0) {
                        continue;
                    }
                    $count++;
                    $accum += $present / $employeeCount;
                } else {
                    $count++;
                    $accum += $present > 0 ? 1 : 0;
                }
            }
            return $count > 0 ? $accum / $count * 100 : 0;
        };
        $rateThis = $rateFor(array_slice($days, 7));
        $rateLast = $rateFor(array_slice($days, 0, 7));

        // Total worked hours this week: pair each check-out with its preceding
        // check-in per user (sequentially), capping at a sane 16h shift.
        $sequence = $scope(AttendanceLog::query())
            ->whereIn('type', ['checkin', 'checkout'])
            ->whereBetween('timestamp', [\Carbon\Carbon::parse($days[7])->startOfDay(), $end])
            ->orderBy('timestamp')
            ->get(['type', 'timestamp', 'user_id'])
            ->groupBy('user_id');
        $minutes = 0;
        foreach ($sequence as $logs) {
            $openIn = null;
            foreach ($logs as $l) {
                if ($l->type === 'checkin') {
                    $openIn = $l->timestamp;
                } elseif ($openIn) {
                    $dur = $openIn->diffInMinutes($l->timestamp);
                    if ($dur > 0 && $dur <= 16 * 60) {
                        $minutes += $dur;
                    }
                    $openIn = null;
                }
            }
        }

        $this->metrics = [
            'attendance_rate' => (int) round($rateThis),
            'attendance_delta' => (int) round($rateThis - $rateLast),
            'best_day' => $bestDay,
            'best_day_count' => max(0, $bestCount),
            'total_hours' => intdiv($minutes, 60).'j '.($minutes % 60).'m',
            'compliance' => ($sumOn + $sumLate) > 0 ? (int) round($sumOn / ($sumOn + $sumLate) * 100) : 100,
        ];

        // Sparklines over the full 14 days.
        $sOn = $sLate = $sTotal = [];
        foreach ($days as $d) {
            $sOn[] = $agg[$d]['on'] ?? 0;
            $sLate[] = $agg[$d]['late'] ?? 0;
            $sTotal[] = ($agg[$d]['on'] ?? 0) + ($agg[$d]['late'] ?? 0);
        }
        $sus = [];
        $events = $scope(SuspiciousEvent::query())
            ->whereBetween('created_at', [$start14, $end])
            ->get(['created_at']);
        $susByDate = [];
        foreach ($events as $e) {
            $k = $e->created_at->timezone($tz)->toDateString();
            $susByDate[$k] = ($susByDate[$k] ?? 0) + 1;
        }
        foreach ($days as $d) {
            $sus[] = $susByDate[$d] ?? 0;
        }
        $this->spark = ['total' => $sTotal, 'on_time' => $sOn, 'late' => $sLate, 'suspicious' => $sus];

        // Upcoming approved permissions (Tidak Masuk only).
        $up = [];
        $permQ = \App\Models\PermissionRequest::with('user')
            ->where('type', 'ijin_tidak_masuk')
            ->where('status', 'approved')
            ->whereDate('date', '>=', today());
        if (! $this->isAdmin) {
            $permQ->where('user_id', $user->id);
        }
        foreach ($permQ->orderBy('date')->limit(6)->get() as $pm) {
            $up[] = [
                'tone' => 'warning',
                'title' => 'Tidak Masuk – '.($pm->user->name ?? '—'),
                'subtitle' => \Carbon\Carbon::parse($pm->date)->translatedFormat('l, j M Y'),
                'sort' => \Carbon\Carbon::parse($pm->date)->toDateString(),
            ];
        }
        usort($up, fn ($a, $b) => strcmp($a['sort'], $b['sort']));
        $this->upcoming = array_slice($up, 0, 5);
    }

    /**
     * Listen to Laravel Echo broadcast events dynamically.
     */
    public function getListeners()
    {
        $userId = Auth::id();
        
        if ($this->isAdmin) {
            return [
                "echo-private:dashboard.admin,AttendanceLogged" => 'handleAttendanceLogged',
            ];
        }

        return [
            "echo-private:dashboard.user.{$userId},AttendanceLogged" => 'handleAttendanceLogged',
        ];
    }

    /**
     * Handle the real-time AttendanceLogged event.
     */
    public function handleAttendanceLogged($event)
    {
        $this->loadData();
        
        // Dispatch a browser event to show a toast notification
        $this->dispatch('attendance-updated', [
            'type' => $event['type'] ?? 'checkin',
            'userId' => $event['user_id'] ?? null
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

        $this->loadData();
        
        // Dispatch session flash
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

        $this->loadData();

        // Dispatch session flash
        session()->flash('success', 'Absensi ' . ($log->user->name ?? 'Karyawan') . ' berhasil ditolak.');
    }

    public function render()
    {
        return view('livewire.dashboard');
    }
}
