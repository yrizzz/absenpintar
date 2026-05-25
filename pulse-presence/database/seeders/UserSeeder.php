<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $headOffice = Branch::where('code', 'HO')->first();
        $regularShift = Shift::where('code', 'REG')->first();

        // Super Admin
        $superAdmin = User::create([
            'employee_id' => 'EMP001',
            'name' => 'Super Admin',
            'email' => 'admin@AbsenPintar.com',
            'password' => Hash::make('password'),
            'branch_id' => $headOffice->id,
            'phone' => '+62812345678',
            'status' => 'active',
            'work_mode' => 'wfo',
        ]);
        $superAdmin->assignRole('super_admin');
        $superAdmin->shifts()->attach($regularShift->id, [
            'effective_date' => now(),
        ]);

        // HR Admin
        $hrAdmin = User::create([
            'employee_id' => 'EMP002',
            'name' => 'HR Admin',
            'email' => 'hr@AbsenPintar.com',
            'password' => Hash::make('password'),
            'branch_id' => $headOffice->id,
            'phone' => '+62812345679',
            'status' => 'active',
            'work_mode' => 'wfo',
        ]);
        $hrAdmin->assignRole('hr_admin');
        $hrAdmin->shifts()->attach($regularShift->id, [
            'effective_date' => now(),
        ]);

        // Manager
        $manager = User::create([
            'employee_id' => 'EMP003',
            'name' => 'Manager',
            'email' => 'manager@AbsenPintar.com',
            'password' => Hash::make('password'),
            'branch_id' => $headOffice->id,
            'phone' => '+62812345680',
            'status' => 'active',
            'work_mode' => 'hybrid',
        ]);
        $manager->assignRole('manager');
        $manager->shifts()->attach($regularShift->id, [
            'effective_date' => now(),
        ]);

        // Employees
        for ($i = 4; $i <= 10; $i++) {
            $employee = User::create([
                'employee_id' => 'EMP' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'name' => 'Employee ' . $i,
                'email' => 'employee' . $i . '@AbsenPintar.com',
                'password' => Hash::make('password'),
                'branch_id' => $headOffice->id,
                'phone' => '+6281234' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'status' => 'active',
                'work_mode' => $i % 2 == 0 ? 'wfo' : 'wfh',
            ]);
            $employee->assignRole('employee');
            $employee->shifts()->attach($regularShift->id, [
                'effective_date' => now(),
            ]);
        }
    }
}
