<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\AdminApproval;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminApprovalsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * This seeder pre-approves specific Gmail addresses for admin access
     * with appropriate roles. This is a security measure to ensure
     * only authorized Gmail accounts can access the admin dashboard.
     */
    public function run(): void
    {
        // Ensure the roles exist in the database
        $this->ensureRolesExist();
        
        // Get role IDs from the database after ensuring they exist
        $roles = Role::pluck('id', 'name');
        
        // Debug log to check if roles were found
        Log::info('Available roles for admin approvals:', $roles->toArray());
        
        // Check if we have the Barangay Captain role
        if (!isset($roles['Barangay Captain'])) {
            Log::error('Barangay Captain role not found! Available roles: ' . implode(', ', $roles->keys()->toArray()));
            $this->command->error('Cannot create admin approval: Barangay Captain role not found!');
            return;
        }
        
        // Add your Gmail account as Barangay Captain (super admin)
        DB::table('admin_approvals')->insert([
            'email' => 'captainlumanglipa4223@gmail.com',
            'role_id' => $roles['Barangay Captain'],
            'is_active' => true,
            'approved_by' => 'System',
            'approved_at' => now(),
            'notes' => 'Initial super admin account created during system setup',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $this->command->info('Successfully created admin approval for captainlumanglipa4223@gmail.com as Barangay Captain');
    }
    
    /**
     * Ensure that all required roles exist in the database.
     */
    private function ensureRolesExist(): void
    {
        $requiredRoles = [
            [
                'name' => 'Barangay Captain',
                'description' => 'Super Admin: Full access to all modules and system management.',
            ],
            [
                'name' => 'Barangay Secretary',
                'description' => 'General Admin: Manages documents, complaints, and announcements.',
            ],
            [
                'name' => 'Health Worker',
                'description' => 'Health Admin: Manages health services and resident health data.',
            ],
            [
                'name' => 'Complaint Manager',
                'description' => 'Handles complaint management and resolution.',
            ],
        ];
        
        foreach ($requiredRoles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
        
        $this->command->line('Required roles have been checked/created.');
    }
}
