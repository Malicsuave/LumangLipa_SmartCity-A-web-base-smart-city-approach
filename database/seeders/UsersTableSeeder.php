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
     * Creates only the Captain user as the super admin.
     * Other users will need to register and request access approval.
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
            ]
            // Other default users removed - new users will go through approval process
        ]);
    }
}
