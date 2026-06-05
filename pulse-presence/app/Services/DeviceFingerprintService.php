<?php

namespace App\Services;

use App\Models\DeviceFingerprint;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
     * Detect VPN / proxy / Tor usage from the request IP.
     *
     * When an IPQualityScore API key is configured (services.ipqualityscore.key)
     * the live reputation API is used. Otherwise we fall back to a self-contained
     * heuristic based on private/reserved IPs and timezone mismatch.
     */
    public function detectVPN(string $ipAddress, string $timezone): array
    {
        $apiKey = config('services.ipqualityscore.key');

        if ($apiKey && filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            $live = $this->lookupIpReputation($ipAddress, $apiKey);
            if ($live !== null) {
                return $live;
            }
        }

        return $this->heuristicVpnCheck($ipAddress, $timezone);
    }

    /**
     * Query IPQualityScore and cache the result for 24h to avoid rate limits.
     */
    protected function lookupIpReputation(string $ipAddress, string $apiKey): ?array
    {
        return Cache::remember("vpn_lookup:{$ipAddress}", now()->addDay(), function () use ($ipAddress, $apiKey) {
            try {
                $response = Http::timeout(4)->get(
                    "https://ipqualityscore.com/api/json/ip/{$apiKey}/{$ipAddress}",
                    ['strictness' => 1, 'allow_public_access_points' => 'true']
                );

                if (!$response->successful() || !($response->json('success') ?? false)) {
                    return null;
                }

                $isVpn = (bool) ($response->json('vpn') || $response->json('proxy') || $response->json('tor') || $response->json('active_vpn'));
                $fraudScore = (int) ($response->json('fraud_score') ?? 0);

                return [
                    'is_vpn' => $isVpn,
                    // Map provider fraud_score (0-100) onto our scale, capping the VPN contribution.
                    'risk_score' => $isVpn ? max(25, (int) round($fraudScore / 2)) : min(10, (int) round($fraudScore / 4)),
                    'message' => $isVpn ? 'VPN/Proxy/Tor terdeteksi (IPQS)' : 'Tidak terdeteksi VPN (IPQS)',
                ];
            } catch (\Throwable $e) {
                Log::warning('VPN lookup failed: ' . $e->getMessage(), ['ip' => $ipAddress]);
                return null;
            }
        });
    }

    /**
     * Dependency-free fallback. Private/reserved IPs are treated as internal (safe).
     * A timezone that does not match the business timezone is a mild remote-access signal.
     */
    protected function heuristicVpnCheck(string $ipAddress, string $timezone): array
    {
        $isPublic = filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false;

        $expectedTimezone = (string) (cache()->get('settings.timezone') ?? config('app.timezone', 'Asia/Jakarta'));
        $timezoneMismatch = $timezone !== '' && $expectedTimezone !== '' && $timezone !== $expectedTimezone;

        $riskScore = 0;
        if ($isPublic && $timezoneMismatch) {
            // Public IP + foreign timezone is a plausible VPN/remote indicator.
            $riskScore = 15;
        } elseif ($timezoneMismatch) {
            $riskScore = 5;
        }

        return [
            'is_vpn' => false, // No definitive signal without a reputation provider.
            'risk_score' => $riskScore,
            'message' => $timezoneMismatch
                ? "Zona waktu perangkat ({$timezone}) berbeda dari zona waktu kantor ({$expectedTimezone})."
                : 'Tidak terdeteksi indikasi VPN.',
        ];
    }
}
