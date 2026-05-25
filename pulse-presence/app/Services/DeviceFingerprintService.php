<?php

namespace App\Services;

use App\Models\DeviceFingerprint;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DeviceFingerprintService
{
    /**
     * Generate device hash from fingerprint data.
     */
    public function generateDeviceHash(array $fingerprintData): string
    {
        $components = [
            $fingerprintData['browser'] ?? '',
            $fingerprintData['os'] ?? '',
            $fingerprintData['platform'] ?? '',
            $fingerprintData['screen_resolution'] ?? '',
            $fingerprintData['timezone'] ?? '',
            $fingerprintData['language'] ?? '',
            $fingerprintData['hardware_concurrency'] ?? '',
        ];

        return hash('sha256', implode('|', $components));
    }

    /**
     * Register or update device fingerprint for a user.
     */
    public function registerDevice(User $user, array $fingerprintData): DeviceFingerprint
    {
        $deviceHash = $this->generateDeviceHash($fingerprintData);

        $device = DeviceFingerprint::firstOrNew([
            'user_id' => $user->id,
            'device_hash' => $deviceHash,
        ]);

        $device->fill([
            'browser' => $fingerprintData['browser'] ?? null,
            'os' => $fingerprintData['os'] ?? null,
            'platform' => $fingerprintData['platform'] ?? null,
            'timezone' => $fingerprintData['timezone'] ?? null,
            'language' => $fingerprintData['language'] ?? null,
            'screen_resolution' => $fingerprintData['screen_resolution'] ?? null,
            'hardware_concurrency' => $fingerprintData['hardware_concurrency'] ?? null,
            'gpu_info' => $fingerprintData['gpu_info'] ?? null,
            'last_used_at' => now(),
        ]);

        // First device is automatically trusted
        if ($user->deviceFingerprints()->count() === 0) {
            $device->trusted = true;
        }

        $device->save();

        return $device;
    }

    /**
     * Validate device fingerprint and calculate risk.
     */
    public function validateDevice(User $user, array $fingerprintData): array
    {
        $deviceHash = $this->generateDeviceHash($fingerprintData);
        $device = DeviceFingerprint::where('user_id', $user->id)
            ->where('device_hash', $deviceHash)
            ->first();

        $riskScore = 0;
        $isNewDevice = false;
        $isTrusted = false;

        if (!$device) {
            // New device detected
            $isNewDevice = true;
            $riskScore += 20;
        } else {
            $isTrusted = $device->trusted;
            if (!$isTrusted) {
                $riskScore += 15;
            }
            $device->updateLastUsed();
        }

        // Check timezone mismatch
        $userTimezone = $user->branch->timezone ?? config('app.timezone');
        $deviceTimezone = $fingerprintData['timezone'] ?? null;
        
        if ($deviceTimezone && $deviceTimezone !== $userTimezone) {
            $riskScore += 20;
        }

        return [
            'device' => $device,
            'is_new_device' => $isNewDevice,
            'is_trusted' => $isTrusted,
            'risk_score' => $riskScore,
            'requires_verification' => $isNewDevice || !$isTrusted,
        ];
    }

    /**
     * Detect VPN usage based on IP and timezone.
     */
    public function detectVPN(string $ipAddress, string $timezone): array
    {
        // Basic VPN detection logic
        // In production, use a service like IPQualityScore or similar
        
        $riskScore = 0;
        $isVPN = false;

        // Check for common VPN IP ranges (simplified)
        $vpnIndicators = [
            'vpn' => 25,
            'proxy' => 25,
            'tor' => 30,
        ];

        // This is a placeholder - integrate with real VPN detection service
        // For now, we'll just return low risk
        
        return [
            'is_vpn' => $isVPN,
            'risk_score' => $riskScore,
            'message' => $isVPN ? 'VPN detected' : 'No VPN detected',
        ];
    }
}
