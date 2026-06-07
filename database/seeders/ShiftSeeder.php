<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $shifts = [
            [
                'name' => 'Regular Shift',
                'code' => 'REG',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'grace_period' => 15,
                'late_threshold' => 15,
                'overtime_threshold' => 60,
                'working_days' => [1, 2, 3, 4, 5], // Monday to Friday
                'is_active' => true,
            ],
            [
                'name' => 'Morning Shift',
                'code' => 'MORNING',
                'start_time' => '06:00:00',
                'end_time' => '14:00:00',
                'grace_period' => 10,
                'late_threshold' => 10,
                'overtime_threshold' => 60,
                'working_days' => [1, 2, 3, 4, 5],
                'is_active' => true,
            ],
            [
                'name' => 'Afternoon Shift',
                'code' => 'AFTERNOON',
                'start_time' => '14:00:00',
                'end_time' => '22:00:00',
                'grace_period' => 10,
                'late_threshold' => 10,
                'overtime_threshold' => 60,
                'working_days' => [1, 2, 3, 4, 5],
                'is_active' => true,
            ],
            [
                'name' => 'Night Shift',
                'code' => 'NIGHT',
                'start_time' => '22:00:00',
                'end_time' => '06:00:00',
                'grace_period' => 10,
                'late_threshold' => 10,
                'overtime_threshold' => 60,
                'working_days' => [1, 2, 3, 4, 5],
                'is_active' => true,
            ],
        ];

        foreach ($shifts as $shift) {
            Shift::create($shift);
        }
    }
}
