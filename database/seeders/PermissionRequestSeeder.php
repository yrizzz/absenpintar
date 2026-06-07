<?php

namespace Database\Seeders;

use App\Models\PermissionRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionRequestSeeder extends Seeder
{
    /**
     * Seed work-permission (izin) requests covering the dual approval flow
     * (Kepala Divisi → HRD).
     */
    public function run(): void
    {
        $manager = User::where('email', 'manager@PresensiKu.com')->first();
        $hr = User::where('email', 'hr@PresensiKu.com')->first();

        $emp = fn (int $n) => User::where('employee_id', 'EMP' . str_pad($n, 3, '0', STR_PAD_LEFT))->first();

        $rows = [
            // Fully approved (dept head + HR).
            [
                'user' => $emp(4),
                'type' => 'ijin_datang_terlambat',
                'date' => now()->subDays(3)->toDateString(),
                'start_time' => '08:00',
                'end_time' => '09:30',
                'reason' => 'Mengantar anak ke sekolah di hari pertama.',
                'status_dept_head' => 'approved',
                'dept_head_id' => $manager?->id,
                'dept_head_approved_at' => now()->subDays(3),
                'status_hr' => 'approved',
                'hr_id' => $hr?->id,
                'hr_approved_at' => now()->subDays(3),
                'status' => 'approved',
                'approval_notes' => 'Kadiv: ok | HRD: disetujui',
            ],
            // Awaiting Kepala Divisi.
            [
                'user' => $emp(5),
                'type' => 'ijin_pulang_awal',
                'date' => now()->addDay()->toDateString(),
                'start_time' => '15:30',
                'end_time' => '17:00',
                'reason' => 'Ada keperluan keluarga mendesak sore hari.',
                'status_dept_head' => 'pending',
                'status_hr' => 'pending',
                'status' => 'pending',
            ],
            // Approved by Kepala Divisi, awaiting HR.
            [
                'user' => $emp(6),
                'type' => 'ijin_tidak_masuk',
                'date' => now()->addDays(2)->toDateString(),
                'start_time' => null,
                'end_time' => null,
                'reason' => 'Mengurus dokumen kependudukan di kelurahan.',
                'status_dept_head' => 'approved',
                'dept_head_id' => $manager?->id,
                'dept_head_approved_at' => now()->subHours(6),
                'status_hr' => 'pending',
                'status' => 'pending',
                'approval_notes' => 'Kadiv: silakan, lampirkan bukti.',
            ],
            // Rejected.
            [
                'user' => $emp(7),
                'type' => 'ijin_setengah_hari',
                'date' => now()->addDays(4)->toDateString(),
                'start_time' => '08:00',
                'end_time' => '12:00',
                'reason' => 'Keperluan pribadi.',
                'status_dept_head' => 'rejected',
                'dept_head_id' => $manager?->id,
                'dept_head_approved_at' => now()->subHours(2),
                'status_hr' => 'pending',
                'status' => 'rejected',
                'approval_notes' => 'Ditolak: bentrok dengan deadline proyek.',
            ],
        ];

        foreach ($rows as $row) {
            $user = $row['user'];
            unset($row['user']);
            if (!$user) {
                continue;
            }
            PermissionRequest::create(array_merge(['user_id' => $user->id], $row));
        }
    }
}
