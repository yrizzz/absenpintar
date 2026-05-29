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
#[Title('Check Out')]
class CheckOut extends Component
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
            session()->flash('error', 'Kunci biometrik wajah Anda belum terdaftar. Anda wajib mendaftarkan diri di profil terlebih dahulu atau hubungi HR.');
            return redirect()->route('profile');
        }

        // Check if already checked out today
        if ($this->attendanceService->hasCheckedOutToday(Auth::user())) {
            session()->flash('error', 'Anda sudah melakukan absen keluar hari ini.');
            return redirect()->route('dashboard');
        }

        // 🛡️ STRICT GATING: Proactive Early Check-out Prevention
        $shift = Auth::user()->shifts()->first();
        if ($shift) {
            $timezoneSetting = cache()->get('settings.timezone', 'Asia/Jakarta');
            $workEndSetting = cache()->get('settings.work_hour_end', '17:00');
            $currentTimeStr = now()->timezone($timezoneSetting)->format('H:i:s');
            $isEarlyLeave = $currentTimeStr < $workEndSetting;

            // If early checkout and no permission request exists
            if ($isEarlyLeave) {
                $hasApproval = Auth::user()->leaveRequests()
                    ->where('status', 'approved')
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->whereIn('leave_type', ['ijin_pulang_awal', 'ijin_setengah_hari', 'ijin_tidak_masuk'])
                    ->exists();

                if (!$hasApproval) {
                    $workEnd = cache()->get('settings.work_hour_end', '17:00');
                    session()->flash('error', "Akses Ditolak: Anda belum memasuki jam pulang kerja ({$workEnd}). Anda wajib mengajukan permohonan ijin pulang awal terlebih dahulu jika ingin keluar lebih cepat.");
                    return redirect()->route('dashboard');
                }
            }
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

            // Reverse Geocode coordinates to street address via Google Maps
            if (empty($this->resolvedAddress)) {
                try {
                    $response = \Illuminate\Support\Facades\Http::timeout(5)->get("https://maps.googleapis.com/maps/api/geocode/json", [
                        'latlng' => $lat . ',' . $lng,
                        'language' => 'id',
                        'result_type' => 'street_address|route|sublocality|locality',
                        'key' => config('services.google_maps.key', ''),
                    ]);
                    
                    if ($response->successful()) {
                        $resData = $response->json();
                        if (!empty($resData['results'][0]['formatted_address'])) {
                            $this->resolvedAddress = $resData['results'][0]['formatted_address'];
                        }
                    }

                    // Fallback to Nominatim if Google fails or no API key
                    if (empty($this->resolvedAddress)) {
                        $fallback = \Illuminate\Support\Facades\Http::withHeaders([
                            'User-Agent' => 'AbsenPintar/1.0 (contact@yrizzz.com)'
                        ])->timeout(5)->get("https://nominatim.openstreetmap.org/reverse", [
                            'format' => 'jsonv2',
                            'lat' => $lat,
                            'lon' => $lng,
                            'zoom' => 18,
                            'addressdetails' => 1
                        ]);
                        
                        if ($fallback->successful()) {
                            $this->resolvedAddress = $fallback->json()['display_name'] ?? '';
                        }
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

            $tempPath = 'selfies/' . Auth::id() . '/live_checkout_temp.jpg';
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
            
            $fileName = 'selfies/' . Auth::id() . '/' . now()->format('Y-m-d_His') . '_checkout.jpg';
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

            // Process check-out
            $attendance = $this->attendanceService->checkOut(Auth::user(), $data);

            $durationStr = $attendance->metadata['working_duration'] ?? 'Belum terhitung';
            session()->flash('success', "Absen keluar berhasil dilakukan! Durasi kerja hari ini: {$durationStr}.");

            return redirect()->route('dashboard');

        } catch (\Exception $e) {
            $this->isSubmitting = false;
            session()->flash('error', 'Gagal melakukan absen keluar: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.attendance.check-out');
    }
}
