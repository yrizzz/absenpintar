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

    public function mount()
    {
        $user = Auth::user();
        $this->isAdmin = $user->hasAnyRole(['super_admin', 'hr_admin']);
        $this->loadData();
    }

    public function loadData()
    {
        $user = Auth::user();
        
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

    public function render()
    {
        return view('livewire.dashboard');
    }
}
