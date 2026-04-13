<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Admin — full access
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'create user', 'edit user', 'delete user', 'view user',
            'manage donors', 'view donors',
            'manage hospitals', 'approve hospitals', 'view hospitals',
            'manage blood-units', 'view blood-units',
            'manage blood-requests', 'approve requests', 'dispatch requests', 'view blood-requests',
            'view reports', 'view analytics',
        ]);

        // Donor — can donate and view own data
        $donor = Role::firstOrCreate(['name' => 'donor', 'guard_name' => 'web']);
        $donor->syncPermissions([
            'donate blood', 'view own donations', 'download certificate',
        ]);

        // Hospital — can request blood and view own requests
        $hospital = Role::firstOrCreate(['name' => 'hospital', 'guard_name' => 'web']);
        $hospital->syncPermissions([
            'request blood', 'view own requests',
        ]);
    }
}
