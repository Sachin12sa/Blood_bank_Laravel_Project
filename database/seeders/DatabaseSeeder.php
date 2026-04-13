<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Seed permissions, roles, and inventory
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(BloodInventorySeeder::class);

        // Create default admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@bloodbank.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole('admin');
    }
}
