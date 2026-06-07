<?php

namespace App\Livewire\Attendance;

use App\Models\User;
use App\Services\AttendanceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Biometric Live Scanner Demo')]
class BiometricDemo extends Component
{
    public $selectedUserId;
    public $similarity = 0.0;
    public $distance = 1.0;
    public $verified = false;
    public $statusMessage = 'Menunggu umpan kamera...';
    public $scanLatency = 0;
    public $lastScanTime = null;
    
    protected AttendanceService $attendanceService;

    public function boot(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function mount()
    {
        $this->selectedUserId = Auth::id();
    }

    public function compareLiveFace($imageData)
    {
        try {
            $startTime = microtime(true);
            
            if (!$this->selectedUserId) {
                $this->statusMessage = 'Silakan pilih user terlebih dahulu.';
                return;
            }

            $user = User::find($this->selectedUserId);
            if (!$user || !$user->hasRegisteredFace()) {
                $this->similarity = 0.0;
                $this->verified = false;
                $this->statusMessage = 'User tidak memiliki Kunci Induk Wajah yang terdaftar.';
                return;
            }

            // Decode base64 image
            $rawImg = str_replace('data:image/jpeg;base64,', '', $imageData);
            $rawImg = str_replace(' ', '+', $rawImg);
            $imageDecoded = base64_decode($rawImg);

            $tempPath = 'selfies/' . $user->id . '/live_demo_temp.jpg';
            Storage::disk('public')->put($tempPath, $imageDecoded);

            $masterPath = 'master_face/user_' . $user->id . '.jpg';
            
            // Get current calibrated threshold from settings
            $settingThreshold = (float) cache()->get('settings.biometric_liveness_threshold', 0.95);
            $calibratedThreshold = $settingThreshold * 0.70;

            $verification = $this->attendanceService->compareFaceSimilarity($masterPath, $tempPath, $calibratedThreshold);

            // Clean up
            Storage::disk('public')->delete($tempPath);

            $this->similarity = round($verification['similarity'], 1);
            $this->distance = round(1.0 - ($this->similarity / 100.0), 4);
            $this->verified = $verification['verified'];
            $this->statusMessage = $verification['message'];
            $this->scanLatency = round((microtime(true) - $startTime) * 1000);
            $this->lastScanTime = now()->format('H:i:s');

        } catch (\Exception $e) {
            $this->statusMessage = 'Error: ' . $e->getMessage();
        }
    }

    public function render()
    {
        $users = User::all()->filter(function($u) {
            return $u->hasRegisteredFace();
        });

        return view('livewire.attendance.biometric-demo', [
            'users' => $users,
            'selectedUser' => User::find($this->selectedUserId)
        ]);
    }
}
