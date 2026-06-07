<?php

namespace Database\Seeders;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    /**
     * Seed leave requests covering every stage of the two-step approval flow.
     */
    public function run(): void
    {
        $manager = User::where('email', 'manager@PresensiKu.com')->first();
        $hr = User::where('email', 'hr@PresensiKu.com')->first();

        $emp = fn (int $n) => User::where('employee_id', 'EMP' . str_pad($n, 3, '0', STR_PAD_LEFT))->first();

        $rows = [
            // Fully approved annual leave (past).
            [
                'user' => $emp(4),
                'leave_type' => 'annual',
                'start_date' => now()->subDays(20)->toDateString(),
                'end_date' => now()->subDays(18)->toDateString(),
                'total_days' => 3,
                'reason' => 'Liburan keluarga ke luar kota.',
                'status' => 'hr_approved',
                'manager_id' => $manager?->id,
                'manager_approved_at' => now()->subDays(24),
                'manager_notes' => 'Disetujui oleh Manajer',
                'hr_id' => $hr?->id,
                'hr_approved_at' => now()->subDays(23),
                'hr_notes' => 'Disetujui & difinalisasi oleh HR',
            ],
            // Approved sick leave.
            [
                'user' => $emp(5),
                'leave_type' => 'sick',
                'start_date' => now()->subDays(10)->toDateString(),
                'end_date' => now()->subDays(9)->toDateString(),
                'total_days' => 2,
                'reason' => 'Demam dan istirahat sesuai anjuran dokter.',
                'status' => 'hr_approved',
                'manager_id' => $manager?->id,
                'manager_approved_at' => now()->subDays(11),
                'manager_notes' => 'Disetujui oleh Manajer',
                'hr_id' => $hr?->id,
                'hr_approved_at' => now()->subDays(11),
                'hr_notes' => 'Lampiran surat dokter diverifikasi.',
            ],
            // Awaiting manager approval.
            [
                'user' => $emp(6),
                'leave_type' => 'annual',
                'start_date' => now()->addDays(5)->toDateString(),
                'end_date' => now()->addDays(6)->toDateString(),
                'total_days' => 2,
                'reason' => 'Keperluan keluarga.',
                'status' => 'pending',
            ],
            // Approved by manager, awaiting HR.
            [
                'user' => $emp(7),
                'leave_type' => 'annual',
                'start_date' => now()->addDays(9)->toDateString(),
                'end_date' => now()->addDays(11)->toDateString(),
                'total_days' => 3,
                'reason' => 'Menghadiri acara pernikahan saudara.',
                'status' => 'manager_approved',
                'manager_id' => $manager?->id,
                'manager_approved_at' => now()->subDay(),
                'manager_notes' => 'Disetujui, pastikan handover tugas.',
            ],
            // Rejected.
            [
                'user' => $emp(8),
                'leave_type' => 'unpaid',
                'start_date' => now()->addDays(2)->toDateString(),
                'end_date' => now()->addDays(8)->toDateString(),
                'total_days' => 7,
                'reason' => 'Cuti pribadi di luar tanggungan.',
                'status' => 'rejected',
                'manager_id' => $manager?->id,
                'manager_approved_at' => now()->subDays(2),
                'manager_notes' => 'Ditolak: beban kerja tim sedang tinggi.',
            ],
        ];

        foreach ($rows as $row) {
            $user = $row['user'];
            unset($row['user']);
            if (!$user) {
                continue;
            }
            LeaveRequest::create(array_merge(['user_id' => $user->id], $row));
        }
    }
}
