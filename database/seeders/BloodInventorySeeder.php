<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BloodInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $groups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];

    foreach ($groups as $group) {
        \App\Models\BloodInventory::firstOrCreate(
            ['blood_group' => $group],
            ['total_units' => 0, 'available_units' => 0, 'threshold' => 5]
        );
    }
}
}
