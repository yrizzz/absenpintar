<?php

return [
    /*
    |--------------------------------------------------------------------------
    | GPS Accuracy Threshold
    |--------------------------------------------------------------------------
    |
    | Maximum acceptable GPS accuracy in meters. Attendance with accuracy
    | above this threshold will be flagged.
    |
    */
    'gps_accuracy_threshold' => env('ATTENDANCE_GPS_ACCURACY_THRESHOLD', 100),

    /*
    |--------------------------------------------------------------------------
    | Geofence Radius
    |--------------------------------------------------------------------------
    |
    | Default geofence radius in meters for branches.
    |
    */
    'geofence_radius' => env('ATTENDANCE_GEOFENCE_RADIUS', 200),

    /*
    |--------------------------------------------------------------------------
    | Risk Thresholds
    |--------------------------------------------------------------------------
    |
    | Risk score thresholds for determining risk levels.
    |
    */
    'risk_threshold_medium' => env('ATTENDANCE_RISK_THRESHOLD_MEDIUM', 30),
    'risk_threshold_high' => env('ATTENDANCE_RISK_THRESHOLD_HIGH', 60),

    /*
    |--------------------------------------------------------------------------
    | Selfie Settings
    |--------------------------------------------------------------------------
    |
    | Settings for selfie capture and storage.
    |
    */
    'selfie_retention_days' => env('SELFIE_RETENTION_DAYS', 90),
    'selfie_max_size' => 5120, // KB
    'selfie_quality' => 80, // JPEG quality

    /*
    |--------------------------------------------------------------------------
    | AI Service
    |--------------------------------------------------------------------------
    |
    | Configuration for AI face verification service.
    |
    */
    'ai_service_url' => env('AI_SERVICE_URL', 'http://localhost:8001'),
    'ai_service_timeout' => env('AI_SERVICE_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Attendance Rules
    |--------------------------------------------------------------------------
    |
    | General attendance rules and settings.
    |
    */
    'max_check_ins_per_day' => 1,
    'max_check_outs_per_day' => 1,
    'impossible_travel_speed_threshold' => 120, // km/h
    'multiple_device_time_window' => 30, // minutes
];
