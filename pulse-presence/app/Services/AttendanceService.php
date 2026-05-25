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
            $workStartSetting = cache()->get('settings.work_hour_start', '08:00');
            $gracePeriodSetting = (int) cache()->get('settings.grace_period', 15);
            $currentTimeStr = now()->format('H:i:s');
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
            $workEndSetting = cache()->get('settings.work_hour_end', '17:00');
            $currentTimeStr = now()->format('H:i:s');
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
}
