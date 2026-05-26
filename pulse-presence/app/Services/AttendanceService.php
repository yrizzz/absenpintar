<?php

namespace App\Services;

use App\Models\AttendanceLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function __construct(
        protected GeoValidationService $geoValidation,
        protected DeviceFingerprintService $deviceFingerprint,
        protected RiskEngineService $riskEngine
    ) {}

    /**
     * Process check-in attendance.
     */
    public function checkIn(User $user, array $data): AttendanceLog
    {
        return DB::transaction(function () use ($user, $data) {
            // Validate and register device
            $deviceValidation = $this->deviceFingerprint->validateDevice(
                $user,
                $data['device_fingerprint']
            );

            $device = $deviceValidation['device'] 
                ?? $this->deviceFingerprint->registerDevice($user, $data['device_fingerprint']);

            // Validate GPS accuracy
            $accuracyValidation = $this->geoValidation->validateAccuracy($data['accuracy']);

            // Biometric Face Recognition Comparison Check
            $masterPath = 'master_face/user_' . $user->id . '.jpg';
            $selfiePath = $data['selfie_path'] ?? null;
            if ($selfiePath && \Illuminate\Support\Facades\Storage::disk('local')->exists($masterPath)) {
                $settingThreshold = (float) cache()->get('settings.biometric_liveness_threshold', 0.95);
                // Calibrate the admin threshold (e.g. 0.95 -> 0.665 similarity required in Python)
                $calibratedThreshold = $settingThreshold * 0.70;

                $verification = $this->compareFaceSimilarity($masterPath, $selfiePath, $calibratedThreshold);
                
                if (!$verification['verified']) {
                    throw new \Exception("Verifikasi biometrik gagal! " . $verification['message'] . " (Tingkat Kecocokan: " . round($verification['similarity'], 1) . "%, minimal dibutuhkan " . round($settingThreshold * 100.0, 1) . "%).");
                }
            }

            // Validate geofence
            $geofenceValidation = $this->geoValidation->validateGeofence(
                $data['latitude'],
                $data['longitude'],
                $user->branch
            );

            // Check for impossible travel
            $lastAttendance = AttendanceLog::where('user_id', $user->id)
                ->latest('timestamp')
                ->first();

            $impossibleTravelValidation = ['impossible' => false, 'risk_score' => 0];
            
            if ($lastAttendance) {
                $timeDiff = now()->diffInSeconds($lastAttendance->timestamp);
                $impossibleTravelValidation = $this->geoValidation->detectImpossibleTravel(
                    $lastAttendance->latitude,
                    $lastAttendance->longitude,
                    $data['latitude'],
                    $data['longitude'],
                    $timeDiff
                );
            }

            // Detect VPN
            $vpnValidation = $this->deviceFingerprint->detectVPN(
                $data['ip_address'],
                $data['device_fingerprint']['timezone'] ?? ''
            );

            // Calculate total risk score
            $riskFactors = [
                'new_device' => $deviceValidation['is_new_device'],
                'untrusted_device' => !$deviceValidation['is_trusted'],
                'vpn_detected' => $vpnValidation['is_vpn'],
                'low_gps_accuracy' => !$accuracyValidation['valid'],
                'outside_geofence' => !$geofenceValidation['valid'],
                'impossible_travel' => $impossibleTravelValidation['impossible'],
            ];

            $totalRiskScore = $this->riskEngine->calculateRiskScore($riskFactors);
            $riskLevel = $this->riskEngine->getRiskLevel($totalRiskScore);
            $status = $this->riskEngine->getAttendanceStatus($riskLevel);

            // Check for approved permission requests today
            $approvedPermission = \App\Models\PermissionRequest::where('user_id', $user->id)
                ->where('date', now()->toDateString())
                ->where('status', 'approved')
                ->first();

            // Calculate is_late automatically
            $timezoneSetting = cache()->get('settings.timezone', 'Asia/Jakarta');
            $workStartSetting = cache()->get('settings.work_hour_start', '08:00');
            $gracePeriodSetting = (int) cache()->get('settings.grace_period', 15);
            $currentTimeStr = now()->timezone($timezoneSetting)->format('H:i:s');
            $workStartWithGrace = date('H:i:s', strtotime($workStartSetting) + ($gracePeriodSetting * 60));
            $isLate = $currentTimeStr > $workStartWithGrace;
            
            $notes = $isLate ? 'Terlambat melakukan absensi masuk.' : 'Absen masuk tepat waktu.';

            if ($isLate && $approvedPermission && in_array($approvedPermission->type, ['ijin_datang_terlambat', 'ijin_setengah_hari', 'ijin_tidak_masuk'])) {
                $isLate = false;
                $labelType = '';
                if ($approvedPermission->type === 'ijin_datang_terlambat') $labelType = 'Izin Datang Terlambat';
                if ($approvedPermission->type === 'ijin_setengah_hari') $labelType = 'Izin Setengah Hari';
                if ($approvedPermission->type === 'ijin_tidak_masuk') $labelType = 'Izin Tidak Masuk';
                $notes = "Absen masuk (Dispensasi: {$labelType} telah disetujui Kepala Divisi & HR).";
            }

            // Create attendance log
            $attendance = AttendanceLog::create([
                'user_id' => $user->id,
                'branch_id' => $user->branch_id,
                'shift_id' => $data['shift_id'] ?? null,
                'type' => 'checkin',
                'timestamp' => now(),
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'accuracy' => $data['accuracy'],
                'ip_address' => $data['ip_address'],
                'device_fingerprint_id' => $device->id,
                'selfie_path' => $data['selfie_path'] ?? null,
                'risk_score' => $totalRiskScore,
                'risk_level' => $riskLevel,
                'status' => $status,
                'work_mode' => $user->work_mode,
                'is_late' => $isLate,
                'notes' => $notes,
                'metadata' => [
                    'resolved_address' => $data['resolved_address'] ?? null,
                    'validations' => [
                        'gps_accuracy' => $accuracyValidation,
                        'geofence' => $geofenceValidation,
                        'device' => $deviceValidation,
                        'vpn' => $vpnValidation,
                        'impossible_travel' => $impossibleTravelValidation,
                    ],
                ],
            ]);

            // Analyze and create suspicious events
            $this->riskEngine->analyzeAttendance($attendance, [
                'gps_accuracy' => $accuracyValidation,
                'geofence' => $geofenceValidation,
                'device' => $deviceValidation,
                'vpn' => $vpnValidation,
                'impossible_travel' => $impossibleTravelValidation,
            ]);

            return $attendance;
        });

        event(new \App\Events\AttendanceLogged($attendance));

        return $attendance;
    }

    /**
     * Process check-out attendance.
     */
    public function checkOut(User $user, array $data): AttendanceLog
    {
        return DB::transaction(function () use ($user, $data) {
            // Similar validation as check-in
            $deviceValidation = $this->deviceFingerprint->validateDevice(
                $user,
                $data['device_fingerprint']
            );

            $device = $deviceValidation['device'] 
                ?? $this->deviceFingerprint->registerDevice($user, $data['device_fingerprint']);

            $accuracyValidation = $this->geoValidation->validateAccuracy($data['accuracy']);

            // Biometric Face Recognition Comparison Check
            $masterPath = 'master_face/user_' . $user->id . '.jpg';
            $selfiePath = $data['selfie_path'] ?? null;
            if ($selfiePath && \Illuminate\Support\Facades\Storage::disk('local')->exists($masterPath)) {
                $settingThreshold = (float) cache()->get('settings.biometric_liveness_threshold', 0.95);
                // Calibrate the admin threshold (e.g. 0.95 -> 0.665 similarity required in Python)
                $calibratedThreshold = $settingThreshold * 0.70;

                $verification = $this->compareFaceSimilarity($masterPath, $selfiePath, $calibratedThreshold);
                
                if (!$verification['verified']) {
                    throw new \Exception("Verifikasi biometrik gagal! " . $verification['message'] . " (Tingkat Kecocokan: " . round($verification['similarity'], 1) . "%, minimal dibutuhkan " . round($settingThreshold * 100.0, 1) . "%).");
                }
            }
            $geofenceValidation = $this->geoValidation->validateGeofence(
                $data['latitude'],
                $data['longitude'],
                $user->branch
            );

            $riskFactors = [
                'new_device' => $deviceValidation['is_new_device'],
                'untrusted_device' => !$deviceValidation['is_trusted'],
                'low_gps_accuracy' => !$accuracyValidation['valid'],
                'outside_geofence' => !$geofenceValidation['valid'],
            ];

            $totalRiskScore = $this->riskEngine->calculateRiskScore($riskFactors);
            $riskLevel = $this->riskEngine->getRiskLevel($totalRiskScore);
            $status = $this->riskEngine->getAttendanceStatus($riskLevel);

            // Check for approved permission requests today
            $approvedPermission = \App\Models\PermissionRequest::where('user_id', $user->id)
                ->where('date', now()->toDateString())
                ->where('status', 'approved')
                ->first();

            // Calculate is_early_leave automatically
            $timezoneSetting = cache()->get('settings.timezone', 'Asia/Jakarta');
            $workEndSetting = cache()->get('settings.work_hour_end', '17:00');
            $currentTimeStr = now()->timezone($timezoneSetting)->format('H:i:s');
            $isEarlyLeave = $currentTimeStr < $workEndSetting;

            if ($isEarlyLeave && $approvedPermission && in_array($approvedPermission->type, ['ijin_pulang_awal', 'ijin_setengah_hari', 'ijin_tidak_masuk'])) {
                $isEarlyLeave = false;
            }

            // Calculate flexible overtime automatically
            $overtimeHours = 0;
            $overtimeType = 'none';
            $overtimeLabel = null;

            if ($currentTimeStr > $workEndSetting) {
                $secondsDiff = strtotime($currentTimeStr) - strtotime($workEndSetting);
                $overtimeHours = round($secondsDiff / 3600, 2);

                $minOvertime = (float) cache()->get('settings.overtime_min_hours', 1.0);
                $fullDayOvertime = (float) cache()->get('settings.overtime_full_day_hours', 8.0);

                if ($overtimeHours >= $minOvertime) {
                    if ($overtimeHours >= $fullDayOvertime) {
                        $overtimeType = 'full_day';
                        $overtimeLabel = 'Lembur Penuh';
                    } else {
                        $overtimeType = 'standard';
                        $overtimeLabel = 'Lembur Standar';
                    }
                } else {
                    $overtimeHours = 0;
                }
            }

            // Calculate precise working duration
            $checkinLog = AttendanceLog::where('user_id', $user->id)
                ->where('type', 'checkin')
                ->whereDate('timestamp', now()->toDateString())
                ->latest('timestamp')
                ->first();

            $workingSeconds = 0;
            $workingDurationStr = 'Belum terhitung';
            if ($checkinLog) {
                $checkinTime = strtotime($checkinLog->timestamp);
                $checkoutTime = time();
                $workingSeconds = $checkoutTime - $checkinTime;
                
                $hours = floor($workingSeconds / 3600);
                $minutes = floor(($workingSeconds % 3600) / 60);
                $workingDurationStr = "{$hours} Jam {$minutes} Menit";
            }

            $notes = $isEarlyLeave ? 'Pulang mendahului jam selesai kerja.' : 'Pulang sesuai waktu kerja reguler.';
            if ($approvedPermission && in_array($approvedPermission->type, ['ijin_pulang_awal', 'ijin_setengah_hari']) && $currentTimeStr < $workEndSetting) {
                $labelType = $approvedPermission->type === 'ijin_pulang_awal' ? 'Izin Pulang Awal' : 'Izin Setengah Hari';
                $notes = "Pulang awal (Dispensasi: {$labelType} telah disetujui Kepala Divisi & HR).";
            }

            $notes .= " Durasi Jam Kerja: {$workingDurationStr}.";

            if ($overtimeHours > 0) {
                $notes .= " Terdeteksi lembur otomatis ({$overtimeLabel}): {$overtimeHours} jam.";
            }

            $attendance = AttendanceLog::create([
                'user_id' => $user->id,
                'branch_id' => $user->branch_id,
                'shift_id' => $data['shift_id'] ?? null,
                'type' => 'checkout',
                'timestamp' => now(),
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'accuracy' => $data['accuracy'],
                'ip_address' => $data['ip_address'],
                'device_fingerprint_id' => $device->id,
                'selfie_path' => $data['selfie_path'] ?? null,
                'risk_score' => $totalRiskScore,
                'risk_level' => $riskLevel,
                'status' => $status,
                'work_mode' => $user->work_mode,
                'is_early_leave' => $isEarlyLeave,
                'notes' => $notes,
                'metadata' => [
                    'resolved_address' => $data['resolved_address'] ?? null,
                    'validations' => [
                        'gps_accuracy' => $accuracyValidation,
                        'geofence' => $geofenceValidation,
                        'device' => $deviceValidation,
                    ],
                    'overtime' => [
                        'overtime_hours' => $overtimeHours,
                        'overtime_type' => $overtimeType,
                        'overtime_label' => $overtimeLabel,
                    ],
                    'working_duration' => $workingDurationStr,
                    'working_seconds' => $workingSeconds,
                ],
            ]);

            $this->riskEngine->analyzeAttendance($attendance, [
                'gps_accuracy' => $accuracyValidation,
                'geofence' => $geofenceValidation,
                'device' => $deviceValidation,
            ]);

            return $attendance;
        });

        event(new \App\Events\AttendanceLogged($attendance));

        return $attendance;
    }

    /**
     * Get today's attendance for a user.
     */
    public function getTodayAttendance(User $user): ?AttendanceLog
    {
        return AttendanceLog::where('user_id', $user->id)
            ->whereDate('timestamp', today())
            ->where('type', 'checkin')
            ->first();
    }

    /**
     * Check if user has checked in today.
     */
    public function hasCheckedInToday(User $user): bool
    {
        return $this->getTodayAttendance($user) !== null;
    }

    /**
     * Check if user has checked out today.
     */
    public function hasCheckedOutToday(User $user): bool
    {
        return AttendanceLog::where('user_id', $user->id)
            ->whereDate('timestamp', today())
            ->where('type', 'checkout')
            ->exists();
    }

    /**
     * Compare two images using the Python Face Recognition engine.
     * Returns an array containing verification status, similarity score, and messages.
     */
    public function compareFaceSimilarity(string $path1, string $path2, float $threshold = 0.65): array
    {
        $defaultResponse = [
            'verified' => false,
            'similarity' => 0.0,
            'message' => 'Gagal memproses verifikasi biometrik.'
        ];

        try {
            $diskLocal = \Illuminate\Support\Facades\Storage::disk('local');
            $diskPublic = \Illuminate\Support\Facades\Storage::disk('public');

            if (!$diskLocal->exists($path1) || !$diskPublic->exists($path2)) {
                return array_merge($defaultResponse, ['message' => 'File gambar wajah tidak ditemukan.']);
            }

            // Get absolute paths to the files
            // Laravel local disk stores in storage/app/private/
            $absPath1 = storage_path('app/private/' . $path1);
            $absPath2 = storage_path('app/public/' . $path2);

            // Execute python3 face_compare.py with custom calibrated threshold
            $scriptPath = base_path('face_compare.py');
            $command = "python3 " . escapeshellarg($scriptPath) . " " . escapeshellarg($absPath1) . " " . escapeshellarg($absPath2) . " " . escapeshellarg($threshold);
            
            $output = shell_exec($command);
            if (!$output) {
                return $defaultResponse;
            }

            $result = json_decode($output, true);
            if ($result) {
                return [
                    'verified' => $result['verified'] ?? false,
                    'similarity' => ($result['similarity'] ?? 0.0) * 100.0,
                    'message' => $result['message'] ?? 'Verifikasi berhasil'
                ];
            }

            return $defaultResponse;
        } catch (\Exception $e) {
            return array_merge($defaultResponse, ['message' => 'Exception: ' . $e->getMessage()]);
        }
    }
}
