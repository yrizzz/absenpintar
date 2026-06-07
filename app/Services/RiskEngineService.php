<?php

namespace App\Services;

use App\Models\AttendanceLog;
use App\Models\SuspiciousEvent;
use App\Models\User;

class RiskEngineService
{
    protected array $riskFactors = [
        'new_device' => 20,
        'untrusted_device' => 15,
        'vpn_detected' => 25,
        'gps_mismatch' => 40,
        'face_mismatch' => 80,
        'low_gps_accuracy' => 15,
        'timezone_mismatch' => 20,
        'outside_geofence' => 40,
        'impossible_travel' => 50,
    ];

    /**
     * Calculate total risk score from multiple factors.
     */
    public function calculateRiskScore(array $factors): int
    {
        $totalScore = 0;

        foreach ($factors as $factor => $detected) {
            if ($detected && isset($this->riskFactors[$factor])) {
                $totalScore += $this->riskFactors[$factor];
            }
        }

        // Cap at 100
        return min(100, $totalScore);
    }

    /**
     * Determine risk level from score.
     */
    public function getRiskLevel(int $score): string
    {
        $mediumThreshold = config('attendance.risk_threshold_medium', 30);
        $highThreshold = config('attendance.risk_threshold_high', 60);

        if ($score >= $highThreshold) {
            return 'high';
        } elseif ($score >= $mediumThreshold) {
            return 'medium';
        }

        return 'low';
    }

    /**
     * Analyze attendance log and create suspicious events if needed.
     */
    public function analyzeAttendance(AttendanceLog $log, array $validationResults): void
    {
        $suspiciousEvents = [];

        // Check GPS accuracy
        if (isset($validationResults['gps_accuracy']) && !$validationResults['gps_accuracy']['valid']) {
            $suspiciousEvents[] = [
                'event_type' => 'low_gps_accuracy',
                'risk_score' => $validationResults['gps_accuracy']['risk_score'],
                'description' => $validationResults['gps_accuracy']['message'],
                'metadata' => ['accuracy' => $log->accuracy],
            ];
        }

        // Check geofence
        if (isset($validationResults['geofence']) && !$validationResults['geofence']['valid']) {
            $suspiciousEvents[] = [
                'event_type' => 'fake_gps_suspected',
                'risk_score' => $validationResults['geofence']['risk_score'],
                'description' => $validationResults['geofence']['message'],
                'metadata' => [
                    'distance' => $validationResults['geofence']['distance'],
                    'latitude' => $log->latitude,
                    'longitude' => $log->longitude,
                ],
            ];
        }

        // Check device
        if (isset($validationResults['device'])) {
            if ($validationResults['device']['is_new_device']) {
                $suspiciousEvents[] = [
                    'event_type' => 'new_device',
                    'risk_score' => 20,
                    'description' => 'New device detected',
                    'metadata' => $validationResults['device'],
                ];
            }
        }

        // Check VPN
        if (isset($validationResults['vpn']) && $validationResults['vpn']['is_vpn']) {
            $suspiciousEvents[] = [
                'event_type' => 'vpn_usage',
                'risk_score' => $validationResults['vpn']['risk_score'],
                'description' => $validationResults['vpn']['message'],
                'metadata' => ['ip_address' => $log->ip_address],
            ];
        }

        // Check impossible travel
        if (isset($validationResults['impossible_travel']) && $validationResults['impossible_travel']['impossible']) {
            $suspiciousEvents[] = [
                'event_type' => 'impossible_travel',
                'risk_score' => $validationResults['impossible_travel']['risk_score'],
                'description' => $validationResults['impossible_travel']['message'],
                'metadata' => $validationResults['impossible_travel'],
            ];
        }

        // Create suspicious event records
        foreach ($suspiciousEvents as $event) {
            SuspiciousEvent::create([
                'user_id' => $log->user_id,
                'attendance_log_id' => $log->id,
                'event_type' => $event['event_type'],
                'risk_score' => $event['risk_score'],
                'description' => $event['description'],
                'metadata' => $event['metadata'],
                'status' => 'pending',
            ]);
        }
    }

    /**
     * Get attendance status based on risk level.
     */
    public function getAttendanceStatus(string $riskLevel): string
    {
        return match ($riskLevel) {
            'high' => 'flagged',
            'medium' => 'pending',
            default => 'approved',
        };
    }

    /**
     * Check for multiple device usage in short time.
     */
    public function detectMultipleDevices(User $user, int $timeWindowMinutes = 30): array
    {
        $recentLogs = AttendanceLog::where('user_id', $user->id)
            ->where('timestamp', '>=', now()->subMinutes($timeWindowMinutes))
            ->distinct('device_fingerprint_id')
            ->count('device_fingerprint_id');

        $isMultiple = $recentLogs > 1;

        return [
            'multiple_devices' => $isMultiple,
            'device_count' => $recentLogs,
            'risk_score' => $isMultiple ? 30 : 0,
        ];
    }
}
