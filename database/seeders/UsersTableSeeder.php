<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::pluck('id', 'name');

        User::insert([
            [
                'name' => 'Barangay Captain',
                'email' => 'captain@lumanglipa.city',
                'password' => Hash::make('password123'),
                'role_id' => $roles['Barangay Captain'] ?? null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Barangay Secretary',
                'email' => 'secretary@lumanglipa.city',
                'password' => Hash::make('password123'),
                'role_id' => $roles['Barangay Secretary'] ?? null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Health Worker',
                'email' => 'health@lumanglipa.city',
                'password' => Hash::make('password123'),
                'role_id' => $roles['Health Worker'] ?? null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Complaint Manager',
                'email' => 'complaints@lumanglipa.city',
                'password' => Hash::make('password123'),
                'role_id' => $roles['Complaint Manager'] ?? null,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
