<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Seed the application's global configuration. Setting::put() persists to the
     * `settings` table AND mirrors into the cache under "settings.{key}", which is
     * what the attendance/permission/report flows read at runtime.
     */
    public function run(): void
    {
        $defaults = [
            // Security & GPS
            'radius' => 200,
            'gps_margin' => 25,
            'biometric_liveness_threshold' => 0.95,
            'require_mfa' => false,

            // Work hours & overtime
            'work_hour_start' => '08:00',
            'work_hour_end' => '17:00',
            'grace_period' => 15,
            'timezone' => 'Asia/Jakarta',
            'overtime_min_hours' => 1,
            'overtime_full_day_hours' => 8,

            // Permission (izin) limits — hours
            'permission_max_late_hours' => 2,
            'permission_max_early_hours' => 2,
            'permission_max_half_day_hours' => 4,

            // Company identity (used on printed letters)
            'company_name' => 'PT PresensiKu Nusantara',
            'company_address' => 'Jl. Jenderal Sudirman Kav. 52-53, Jakarta Selatan 12190',
            'company_phone' => '+62 21 5151 5151',
            'company_email' => 'hrd@presensiku.com',
        ];

        foreach ($defaults as $key => $value) {
            Setting::put($key, $value);
        }
    }
}
