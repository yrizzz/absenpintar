<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Head Office',
                'code' => 'HO',
                'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'latitude' => -6.208763,
                'longitude' => 106.845599,
                'radius' => 200,
                'timezone' => 'Asia/Jakarta',
                'is_active' => true,
            ],
            [
                'name' => 'Branch Surabaya',
                'code' => 'SBY',
                'address' => 'Jl. Tunjungan No. 45, Surabaya',
                'latitude' => -7.257472,
                'longitude' => 112.752090,
                'radius' => 200,
                'timezone' => 'Asia/Jakarta',
                'is_active' => true,
            ],
            [
                'name' => 'Branch Bandung',
                'code' => 'BDG',
                'address' => 'Jl. Asia Afrika No. 78, Bandung',
                'latitude' => -6.921831,
                'longitude' => 107.607086,
                'radius' => 200,
                'timezone' => 'Asia/Jakarta',
                'is_active' => true,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
