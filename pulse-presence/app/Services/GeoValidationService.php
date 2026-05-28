<?php

namespace App\Services;

use App\Models\Branch;

class GeoValidationService
{
    /**
     * Validate GPS accuracy.
     */
    public function validateAccuracy(float $accuracy): array
    {
        // Use calibrated margin threshold from Control Panel settings
        $threshold = cache()->get('settings.gps_margin', 15);
        
        $isValid = $accuracy <= $threshold;
        $riskScore = 0;

        if ($accuracy > $threshold) {
            $riskScore = min(30, ($accuracy - $threshold) / 10);
        }

        return [
            'valid' => $isValid,
            'risk_score' => (int) $riskScore,
            'message' => $isValid ? 'GPS accuracy acceptable' : 'GPS accuracy too low',
        ];
    }

    /**
     * Validate geofence for a branch.
     */
    public function validateGeofence(
        float $latitude,
        float $longitude,
        Branch $branch
    ): array {
        // Prioritize branch-specific radius first, falling back to cached global settings.radius, then absolute default 200.
        $radius = (float) ($branch->radius ?? cache()->get('settings.radius', 200));
        if ($radius <= 0) {
            $radius = 200.0;
        }

        $distance = $this->calculateDistance(
            $branch->latitude,
            $branch->longitude,
            $latitude,
            $longitude
        );

        $isWithin = $distance <= $radius;

        $riskScore = 0;
        if (!$isWithin) {
            // Calculate risk based on distance from geofence
            $excessDistance = $distance - $radius;
            $riskScore = min(40, $excessDistance / 10);
        }

        return [
            'valid' => $isWithin,
            'distance' => round($distance, 2),
            'risk_score' => (int) $riskScore,
            'message' => $isWithin 
                ? 'Within geofence' 
                : "Outside geofence by " . round($distance - $radius, 2) . "m",
        ];
    }

    /**
     * Calculate distance between two coordinates using Haversine formula.
     */
    public function calculateDistance(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
        $earthRadius = 6371000; // meters

        $latFrom = deg2rad($lat1);
        $lonFrom = deg2rad($lon1);
        $latTo = deg2rad($lat2);
        $lonTo = deg2rad($lon2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Detect impossible travel between two locations.
     */
    public function detectImpossibleTravel(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2,
        int $timeDifferenceSeconds
    ): array {
        $distance = $this->calculateDistance($lat1, $lon1, $lat2, $lon2);
        
        // Calculate speed in km/h
        $timeInHours = $timeDifferenceSeconds / 3600;
        $speed = $timeInHours > 0 ? ($distance / 1000) / $timeInHours : 999;

        // Threshold: 120 km/h (reasonable max speed including transport)
        $maxReasonableSpeed = 120;
        $isImpossible = $speed > $maxReasonableSpeed;

        return [
            'impossible' => $isImpossible,
            'distance' => round($distance, 2),
            'speed' => round($speed, 2),
            'risk_score' => $isImpossible ? 50 : 0,
            'message' => $isImpossible 
                ? "Impossible travel detected: {$speed} km/h" 
                : 'Travel speed normal',
        ];
    }
}
