<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Role::insert([
            [
                'name' => 'Barangay Captain',
                'description' => 'Super Admin: Full access to all modules and system management.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Barangay Secretary',
                'description' => 'General Admin: Manages documents, complaints, and announcements.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Health Worker',
                'description' => 'Health Admin: Manages health services and resident health data.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Complaint Manager',
                'description' => 'Handles complaint management and resolution.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
