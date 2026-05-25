<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Branch management
            'view branches',
            'create branches',
            'edit branches',
            'delete branches',
            
            // Attendance
            'view own attendance',
            'view all attendance',
            'create attendance',
            'edit attendance',
            'delete attendance',
            'approve attendance',
            
            // Leave management
            'view own leaves',
            'view all leaves',
            'create leaves',
            'edit leaves',
            'delete leaves',
            'approve leaves',
            
            // Permission requests
            'view own permissions',
            'view all permissions',
            'create permissions',
            'approve permissions',
            
            // Shift management
            'view shifts',
            'create shifts',
            'edit shifts',
            'delete shifts',
            'assign shifts',
            
            // Reports
            'view reports',
            'export reports',
            
            // Monitoring
            'view dashboard',
            'view suspicious events',
            'review suspicious events',
            
            // Audit logs
            'view audit logs',
            
            // Settings
            'manage settings',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions

        // Super Admin - all permissions
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // HR Admin
        $hrAdmin = Role::create(['name' => 'hr_admin']);
        $hrAdmin->givePermissionTo([
            'view users',
            'create users',
            'edit users',
            'view branches',
            'view all attendance',
            'edit attendance',
            'approve attendance',
            'view all leaves',
            'approve leaves',
            'view all permissions',
            'approve permissions',
            'view shifts',
            'create shifts',
            'edit shifts',
            'assign shifts',
            'view reports',
            'export reports',
            'view dashboard',
            'view suspicious events',
            'review suspicious events',
        ]);

        // Manager
        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo([
            'view own attendance',
            'view all attendance',
            'view own leaves',
            'view all leaves',
            'approve leaves',
            'view own permissions',
            'view all permissions',
            'approve permissions',
            'view reports',
            'view dashboard',
        ]);

        // Employee
        $employee = Role::create(['name' => 'employee']);
        $employee->givePermissionTo([
            'view own attendance',
            'create attendance',
            'view own leaves',
            'create leaves',
            'view own permissions',
            'create permissions',
        ]);
    }
}
