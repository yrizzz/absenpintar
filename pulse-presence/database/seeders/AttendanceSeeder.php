<?php

namespace Database\Seeders;

use App\Models\AttendanceLog;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Generate realistic check-in / check-out history for every active employee
     * across the last 10 working days (weekdays only).
     */
    public function run(): void
    {
        $branch = Branch::where('code', 'HO')->first() ?? Branch::first();
        if (!$branch) {
            return;
        }

        $baseLat = (float) $branch->latitude;
        $baseLng = (float) $branch->longitude;

        $users = User::where('status', 'active')->get();

        // Walk back from yesterday, collecting 10 weekdays.
        $days = collect();
        $cursor = now()->subDay()->startOfDay();
        while ($days->count() < 10) {
            if (!$cursor->isWeekend()) {
                $days->push($cursor->copy());
            }
            $cursor->subDay();
        }

        foreach ($users as $user) {
            $workMode = in_array($user->work_mode, ['wfo', 'wfh', 'hybrid', 'mobile']) ? $user->work_mode : 'wfo';

            foreach ($days as $day) {
                // ~10% chance the employee is absent that day (no logs).
                if (random_int(1, 10) === 1) {
                    continue;
                }

                $lat = $baseLat + (random_int(-8, 8) / 100000);
                $lng = $baseLng + (random_int(-8, 8) / 100000);
                $accuracy = random_int(5, 30);

                // Check-in between 07:45 and 08:35.
                $checkInMinutes = random_int(-15, 35); // relative to 08:00
                $checkIn = $day->copy()->setTime(8, 0)->addMinutes($checkInMinutes);
                $isLate = $checkInMinutes > 15; // beyond grace period

                AttendanceLog::create([
                    'user_id' => $user->id,
                    'branch_id' => $branch->id,
                    'type' => 'checkin',
                    'timestamp' => $checkIn,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'accuracy' => $accuracy,
                    'ip_address' => '103.' . random_int(1, 254) . '.' . random_int(1, 254) . '.' . random_int(1, 254),
                    'risk_score' => $isLate ? random_int(10, 35) : random_int(0, 12),
                    'risk_level' => $isLate ? 'medium' : 'low',
                    'status' => 'approved',
                    'work_mode' => $workMode,
                    'is_late' => $isLate,
                    'notes' => $isLate ? 'Keterlambatan tercatat otomatis oleh sistem.' : null,
                    'created_at' => $checkIn,
                    'updated_at' => $checkIn,
                ]);

                // Check-out between 17:00 and 18:15.
                $checkOut = $day->copy()->setTime(17, 0)->addMinutes(random_int(0, 75));

                AttendanceLog::create([
                    'user_id' => $user->id,
                    'branch_id' => $branch->id,
                    'type' => 'checkout',
                    'timestamp' => $checkOut,
                    'latitude' => $lat,
                    'longitude' => $lng,
                    'accuracy' => $accuracy,
                    'ip_address' => '103.' . random_int(1, 254) . '.' . random_int(1, 254) . '.' . random_int(1, 254),
                    'risk_score' => random_int(0, 10),
                    'risk_level' => 'low',
                    'status' => 'approved',
                    'work_mode' => $workMode,
                    'created_at' => $checkOut,
                    'updated_at' => $checkOut,
                ]);
            }
        }
    }
}
