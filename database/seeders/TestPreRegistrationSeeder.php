<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PreRegistration;
use App\Models\SeniorPreRegistration;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestPreRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testEmail = 'reywillardd01@gmail.com';
        
        // Sample Filipino names
        $firstNames = ['Juan', 'Maria', 'Jose', 'Ana', 'Pedro', 'Rosa', 'Miguel', 'Carmen', 'Luis', 'Elena'];
        $middleNames = ['Santos', 'Reyes', 'Cruz', 'Bautista', 'Garcia', 'Gonzales', 'Ramos', 'Flores', 'Torres', 'Rivera'];
        $lastNames = ['Dela Cruz', 'Mendoza', 'Lopez', 'Aquino', 'Fernandez', 'Villanueva', 'Santiago', 'Castro', 'Morales', 'Domingo'];
        
        $puroks = ['Purok 1', 'Purok 2', 'Purok 3', 'Purok 4', 'Purok 5'];
        
        echo "Creating 10 Regular Resident Pre-Registrations...\n";
        
        // Create 10 Regular Resident Pre-Registrations
        for ($i = 0; $i < 10; $i++) {
            $birthdate = Carbon::now()->subYears(rand(25, 55))->subDays(rand(1, 365));
            
            PreRegistration::create([
                'type_of_resident' => ['Non-Migrant', 'Migrant', 'Transient'][rand(0, 2)],
                'first_name' => $firstNames[$i],
                'middle_name' => $middleNames[$i],
                'last_name' => $lastNames[$i],
                'suffix' => rand(0, 5) == 0 ? 'Jr.' : null,
                'birthdate' => $birthdate->format('Y-m-d'),
                'birthplace' => 'Mataasnakahoy, Batangas',
                'sex' => rand(0, 1) ? 'Male' : 'Female',
                'civil_status' => ['Single', 'Married', 'Widowed', 'Separated'][rand(0, 3)],
                'citizenship_type' => ['FILIPINO', 'DUAL', 'NATURALIZED'][rand(0, 2)],
                'citizenship_country' => null,
                'educational_attainment' => ['Elementary Graduate', 'High School Graduate', 'College Graduate', 'Vocational Graduate'][rand(0, 3)],
                'education_status' => ['Studying', 'Graduated', 'Stopped Schooling'][rand(0, 2)],
                'religion' => ['Roman Catholic', 'Christian', 'Islam', 'Others'][rand(0, 3)],
                'profession_occupation' => ['Farmer', 'Teacher', 'Driver', 'Vendor', 'Office Worker', 'Self-Employed'][rand(0, 5)],
                'contact_number' => '0' . rand(900, 999) . rand(1000000, 9999999),
                'email_address' => $testEmail,
                'address' => 'Sitio Malaking Bato, Barangay Lumanglipa, Mataasnakahoy, Batangas',
                'purok' => $puroks[rand(0, 4)],
                'custom_purok' => null,
                'emergency_contact_name' => $firstNames[rand(0, 9)] . ' ' . $lastNames[rand(0, 9)],
                'emergency_contact_relationship' => ['Spouse', 'Parent', 'Sibling', 'Child'][rand(0, 3)],
                'emergency_contact_number' => '0' . rand(900, 999) . rand(1000000, 9999999),
                'emergency_contact_address' => 'Barangay Lumanglipa, Mataasnakahoy, Batangas',
                'photo' => null,
                'signature' => null,
                'proof_of_residency' => null,
                'status' => 'pending',
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ]);
            
            echo "Created regular resident " . ($i + 1) . ": {$firstNames[$i]} {$lastNames[$i]}\n";
        }
        
        echo "\nCreating 10 Senior Citizen Pre-Registrations...\n";
        
        // Create 10 Senior Citizen Pre-Registrations
        for ($i = 0; $i < 10; $i++) {
            $birthdate = Carbon::now()->subYears(rand(60, 85))->subDays(rand(1, 365));
            
            SeniorPreRegistration::create([
                'type_of_resident' => ['Non-Migrant', 'Migrant', 'Transient'][rand(0, 2)],
                'first_name' => $firstNames[$i],
                'middle_name' => $middleNames[$i],
                'last_name' => $lastNames[$i],
                'suffix' => rand(0, 5) == 0 ? 'Sr.' : null,
                'birthdate' => $birthdate->format('Y-m-d'),
                'birthplace' => 'Mataasnakahoy, Batangas',
                'sex' => rand(0, 1) ? 'Male' : 'Female',
                'civil_status' => ['Married', 'Widowed', 'Separated'][rand(0, 2)],
                'citizenship_type' => ['FILIPINO', 'DUAL', 'NATURALIZED'][rand(0, 2)],
                'citizenship_country' => null,
                'nationality' => 'Filipino',
                'educational_attainment' => ['Elementary Graduate', 'High School Graduate', 'College Graduate'][rand(0, 2)],
                'education_status' => 'Graduated',
                'religion' => ['Roman Catholic', 'Christian', 'Islam', 'Others'][rand(0, 3)],
                'profession_occupation' => ['Retired', 'Farmer', 'Former Teacher', 'Former Government Employee'][rand(0, 3)],
                'contact_number' => '0' . rand(900, 999) . rand(1000000, 9999999),
                'email_address' => $testEmail,
                'address' => 'Sitio Malaking Bato, Barangay Lumanglipa, Mataasnakahoy, Batangas',
                'purok' => $puroks[rand(0, 4)],
                'custom_purok' => null,
                'emergency_contact_name' => $firstNames[rand(0, 9)] . ' ' . $lastNames[rand(0, 9)],
                'emergency_contact_relationship' => ['Spouse', 'Child', 'Sibling', 'Relative'][rand(0, 3)],
                'emergency_contact_number' => '0' . rand(900, 999) . rand(1000000, 9999999),
                'emergency_contact_address' => 'Barangay Lumanglipa, Mataasnakahoy, Batangas',
                'photo' => null,
                'signature' => null,
                'proof_of_residency' => null,
                'health_condition' => ['excellent', 'good', 'fair', 'poor'][rand(0, 3)],
                'mobility_status' => ['independent', 'assisted', 'wheelchair', 'bedridden'][rand(0, 3)],
                'medical_conditions' => rand(0, 1) ? 'Hypertension, Diabetes' : null,
                'receiving_pension' => rand(0, 1) ? true : false,
                'pension_type' => ['SSS', 'GSIS', 'Social Pension'][rand(0, 2)],
                'pension_amount' => rand(2000, 8000),
                'has_philhealth' => rand(0, 1) ? true : false,
                'philhealth_number' => rand(0, 1) ? '12-' . rand(100000000, 999999999) . '-' . rand(0, 9) : null,
                'has_senior_discount_card' => rand(0, 1) ? true : false,
                'services' => json_encode(['healthcare', 'financial_assistance']),
                'notes' => 'Test senior citizen data for email testing',
                'status' => 'pending',
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now()->subDays(rand(1, 30)),
            ]);
            
            echo "Created senior citizen " . ($i + 1) . ": {$firstNames[$i]} {$lastNames[$i]}\n";
        }
        
        echo "\n✅ Successfully created 10 regular residents and 10 senior citizens for pre-registration!\n";
        echo "📧 All records use email: {$testEmail}\n";
        echo "📝 All records are in 'pending' status ready for approval/testing\n";
    }
}
