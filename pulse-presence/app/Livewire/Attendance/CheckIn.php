<?php

namespace App\Livewire\Attendance;

use App\Services\AttendanceService;
use App\Services\GeoValidationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Check In')]
class CheckIn extends Component
{
    // GPS Coordinates
    public $latitude;
    public $longitude;
    public $accuracy;
    public $locationValid = false;
    public $locationMessage = 'Melacak koordinat GPS...';
    public $distanceFromBranch = 0.0;
    public $maxRadius = 200;
    public $branchLatitude;
    public $branchLongitude;
    public $resolvedAddress = '';

    // Biometric Face
    public $selfieData;
    public $faceSimilarity = 0.0;
    public $faceValid = false;
    public $faceStatusMessage = 'Menunggu umpan kamera...';

    // Device Fingerprint
    public $deviceFingerprint = [];
    public $isSubmitting = false;

    protected AttendanceService $attendanceService;
    protected GeoValidationService $geoValidation;

    public function boot(AttendanceService $attendanceService, GeoValidationService $geoValidation)
    {
        $this->attendanceService = $attendanceService;
        $this->geoValidation = $geoValidation;
    }

    public function mount()
    {
        // Enforce strict face biometric gating
        if (!Auth::user()->hasRegisteredFace()) {
            session()->flash('error', 'Kunci biometrik wajah Anda belum terdaftar. Anda wajib mendaftarkan diri di menu profil terlebih dahulu atau hubungi HR untuk mendaftarkannya.');
            return redirect()->route('profile');
        }

        // Check if already checked in today
        if ($this->attendanceService->hasCheckedInToday(Auth::user())) {
            session()->flash('error', 'Anda sudah melakukan absen masuk hari ini.');
            return redirect()->route('dashboard');
        }

        // Fetch office branch location and prioritize custom radius
        $branch = Auth::user()->branch;
        $this->maxRadius = (float) ($branch?->radius ?? cache()->get('settings.radius', 200));
        
        if ($branch) {
            $this->branchLatitude = (float) $branch->latitude;
            $this->branchLongitude = (float) $branch->longitude;
        } else {
            // Default to center if no branch is configured
            $this->branchLatitude = -6.200000;
            $this->branchLongitude = 106.816666;
        }
    }

    public function updateLocation($lat, $lng, $accuracy)
    {
        try {
            $this->latitude = $lat;
            $this->longitude = $lng;
            $this->accuracy = $accuracy;

            $branch = Auth::user()->branch;
            if (!$branch) {
                $this->locationValid = false;
                $this->locationMessage = 'Branch tidak ditemukan untuk user.';
                return;
            }

            // Run geofence validation
            $geofence = $this->geoValidation->validateGeofence($lat, $lng, $branch);
            $this->distanceFromBranch = $geofence['distance'];
            $this->locationValid = $geofence['valid'];
            $this->locationMessage = $geofence['message'];

            // Reverse Geocode coordinates to street address
            if (empty($this->resolvedAddress)) {
                try {
                    $response = \Illuminate\Support\Facades\Http::withHeaders([
                        'User-Agent' => 'AbsenPintar/1.0 (contact@yrizzz.com)'
                    ])->timeout(5)->get("https://nominatim.openstreetmap.org/reverse", [
                        'format' => 'jsonv2',
                        'lat' => $lat,
                        'lon' => $lng,
                        'zoom' => 18,
                        'addressdetails' => 1
                    ]);
                    
                    if ($response->successful()) {
                        $resData = $response->json();
                        $this->resolvedAddress = $resData['display_name'] ?? '';
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::warning("Reverse geocoding failed: " . $e->getMessage());
                }
            }

        } catch (\Exception $e) {
            $this->locationValid = false;
            $this->locationMessage = 'Gagal memverifikasi lokasi: ' . $e->getMessage();
        }
    }

    public function compareLiveFace($imageData)
    {
        try {
            $this->selfieData = $imageData;

            // Decode base64 image temporarily
            $rawImg = str_replace('data:image/jpeg;base64,', '', $imageData);
            $rawImg = str_replace(' ', '+', $rawImg);
            $imageDecoded = base64_decode($rawImg);

            $tempPath = 'selfies/' . Auth::id() . '/live_checkin_temp.jpg';
            Storage::disk('public')->put($tempPath, $imageDecoded);

            $masterPath = 'master_face/user_' . Auth::id() . '.jpg';
            
            // Get current calibrated threshold from settings
            $settingThreshold = (float) cache()->get('settings.biometric_liveness_threshold', 0.95);
            $calibratedThreshold = $settingThreshold * 0.70;

            $verification = $this->attendanceService->compareFaceSimilarity($masterPath, $tempPath, $calibratedThreshold);

            // Clean up temporary image
            Storage::disk('public')->delete($tempPath);

            $this->faceSimilarity = round($verification['similarity'], 1);
            $this->faceValid = $verification['verified'];
            $this->faceStatusMessage = $verification['message'];



        } catch (\Exception $e) {
            $this->faceValid = false;
            $this->faceStatusMessage = 'Error verifikasi: ' . $e->getMessage();
        }
    }

    public function captureDeviceFingerprint($fingerprint)
    {
        $this->deviceFingerprint = $fingerprint;
    }

    public function submit()
    {
        if ($this->isSubmitting) {
            return;
        }
        $this->isSubmitting = true;

        try {
            // Hard gate server-side checks
            if (!$this->locationValid) {
                $this->isSubmitting = false;
                session()->flash('error', 'Gagal: Anda harus berada di dalam radius lokasi kantor yang ditentukan.');
                return;
            }

            if (!$this->faceValid) {
                $this->isSubmitting = false;
                session()->flash('error', 'Gagal: Verifikasi wajah belum terkonfirmasi.');
                return;
            }

            if (!$this->selfieData) {
                $this->isSubmitting = false;
                session()->flash('error', 'Gagal: Gambar selfie biometrik belum terekam.');
                return;
            }

            // Save final selfie image for audit trail
            $imageData = str_replace('data:image/jpeg;base64,', '', $this->selfieData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageDecoded = base64_decode($imageData);
            
            $fileName = 'selfies/' . Auth::id() . '/' . now()->format('Y-m-d_His') . '.jpg';
            Storage::disk('public')->put($fileName, $imageDecoded);

            // Prepare attendance data
            $data = [
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
                'accuracy' => $this->accuracy,
                'ip_address' => request()->ip(),
                'selfie_path' => $fileName,
                'device_fingerprint' => $this->deviceFingerprint,
                'shift_id' => Auth::user()->shifts()->first()?->id,
                'resolved_address' => $this->resolvedAddress,
            ];

            // Process check-in
            $attendance = $this->attendanceService->checkIn(Auth::user(), $data);

            session()->flash('success', 'Absen masuk berhasil dilakukan!');
            
            if ($attendance->risk_level === 'high') {
                session()->flash('warning', 'Absensi Anda ditandai untuk ditinjau karena skor risiko terdeteksi tinggi.');
            }

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            $this->isSubmitting = false;
            session()->flash('error', 'Gagal melakukan absen masuk: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.attendance.check-in');
    }
}
