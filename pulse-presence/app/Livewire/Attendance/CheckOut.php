<?php

namespace App\Livewire\Attendance;

use App\Services\AttendanceService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Check Out')]
class CheckOut extends Component
{
    public $latitude;
    public $longitude;
    public $accuracy;
    public $selfieData;
    public $deviceFingerprint = [];
    
    public $step = 1;
    public $locationGranted = false;
    public $cameraGranted = false;

    protected AttendanceService $attendanceService;

    public function boot(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    public function mount()
    {
        // Enforce strict face biometric gating
        if (!Auth::user()->hasRegisteredFace()) {
            session()->flash('error', 'Kunci biometrik wajah Anda belum terdaftar. Anda wajib mendaftarkan diri di menu profil terlebih dahulu atau hubungi HR untuk mendaftarkannya.');
            return redirect()->route('profile');
        }

        // Check if not checked in today
        if (!$this->attendanceService->hasCheckedInToday(Auth::user())) {
            session()->flash('error', 'Anda harus melakukan absen masuk terlebih dahulu.');
            return redirect()->route('dashboard');
        }

        // Check if already checked out today
        if ($this->attendanceService->hasCheckedOutToday(Auth::user())) {
            session()->flash('error', 'Anda sudah melakukan absen keluar hari ini.');
            return redirect()->route('dashboard');
        }
    }

    public function captureLocation($latitude, $longitude, $accuracy)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->accuracy = $accuracy;
        $this->locationGranted = true;
        $this->step = 2;
    }

    public function captureSelfie($imageData)
    {
        $this->selfieData = $imageData;
        $this->cameraGranted = true;
        $this->step = 3;
    }

    public function captureDeviceFingerprint($fingerprint)
    {
        $this->deviceFingerprint = $fingerprint;
    }

    public function submit()
    {
        try {
            if (!$this->latitude || !$this->longitude || !$this->selfieData) {
                session()->flash('error', 'Silakan selesaikan semua langkah terlebih dahulu.');
                return;
            }

            // Save selfie image
            $imageData = str_replace('data:image/jpeg;base64,', '', $this->selfieData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageDecoded = base64_decode($imageData);
            
            $fileName = 'selfies/' . Auth::id() . '/' . now()->format('Y-m-d_His') . '_checkout.jpg';
            Storage::disk('local')->put($fileName, $imageDecoded);

            // Prepare attendance data
            $data = [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'accuracy' => $this->accuracy,
                'ip_address' => request()->ip(),
                'selfie_path' => $fileName,
                'device_fingerprint' => $this->deviceFingerprint,
                'shift_id' => Auth::user()->shifts()->first()?->id,
            ];

            // Process check-out
            $attendance = $this->attendanceService->checkOut(Auth::user(), $data);

            $durationStr = $attendance->metadata['working_duration'] ?? 'Belum terhitung';
            $overtimeLabel = $attendance->metadata['overtime']['overtime_label'] ?? null;
            $flashMsg = "Absen Keluar Berhasil! Durasi Kerja Hari Ini: {$durationStr}.";
            if ($overtimeLabel) {
                $flashMsg .= " ({$overtimeLabel})";
            }
            session()->flash('success', $flashMsg);
            
            if ($attendance->risk_level === 'high') {
                session()->flash('warning', 'Absensi Anda ditandai untuk ditinjau karena skor risiko terdeteksi tinggi.');
            }

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            session()->flash('error', 'Gagal melakukan absen keluar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.attendance.check-out');
    }
}
