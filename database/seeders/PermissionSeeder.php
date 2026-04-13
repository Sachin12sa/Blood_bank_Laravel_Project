<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // User management
            'create user', 'edit user', 'delete user', 'view user',
            // Donor management
            'manage donors', 'view donors',
            // Hospital management
            'manage hospitals', 'approve hospitals', 'view hospitals',
            // Blood unit management
            'manage blood-units', 'view blood-units',
            // Blood request management
            'manage blood-requests', 'approve requests', 'dispatch requests', 'view blood-requests',
            // Reports
            'view reports', 'view analytics',
            // Donor-specific
            'donate blood', 'view own donations', 'download certificate',
            // Hospital-specific
            'request blood', 'view own requests',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }
    }
}