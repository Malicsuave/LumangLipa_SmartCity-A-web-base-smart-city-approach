<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HealthRecord;
use App\Models\Resident;
use Carbon\Carbon;

class HealthRecordsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a sample of residents to create health records for
        $residents = Resident::limit(50)->get();
        
        if ($residents->isEmpty()) {
            $this->command->warn('No residents found. Please seed residents first.');
            return;
        }

        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $malnutritionTypes = ['Underweight', 'Stunted', 'Wasted', 'Overweight'];
        $commonConditions = [
            'Hypertension',
            'Diabetes Type 2',
            'Arthritis',
            'Asthma',
            'Heart Disease',
            null
        ];
        $commonMedications = [
            'Metformin (Diabetes)',
            'Losartan (Blood Pressure)',
            'Amlodipine (Hypertension)',
            'Aspirin (Blood Thinner)',
            'Atorvastatin (Cholesterol)',
            null
        ];
        $commonAllergies = [
            'Penicillin',
            'Peanuts',
            'Shellfish',
            'Dust',
            null
        ];

        foreach ($residents as $index => $resident) {
            $age = $resident->age ?? 25;
            $isSenior = $age >= 60;
            $isPregnant = false;
            $isMalnourished = rand(1, 10) <= 2; // 20% chance
            
            // Only females of childbearing age can be pregnant
            if ($resident->gender === 'Female' && $age >= 18 && $age <= 45) {
                $isPregnant = rand(1, 10) <= 2; // 20% chance
            }

            // Generate realistic vitals
            $height = rand(150, 180); // cm
            $weight = rand(45, 90); // kg
            $bmi = round($weight / (($height / 100) ** 2), 2);
            
            $bloodPressure = $isSenior 
                ? rand(130, 160) . '/' . rand(80, 100)  // Higher for seniors
                : rand(110, 130) . '/' . rand(70, 85);   // Normal for others
            
            $temperature = number_format(rand(360, 375) / 10, 1); // 36.0-37.5°C

            // Pregnancy details
            $pregnancyWeeks = null;
            $trimester = null;
            $expectedDeliveryDate = null;
            $nextPrenatalVisit = null;
            
            if ($isPregnant) {
                $pregnancyWeeks = rand(4, 38);
                $trimester = $pregnancyWeeks <= 13 ? 1 : ($pregnancyWeeks <= 26 ? 2 : 3);
                $expectedDeliveryDate = Carbon::now()->addWeeks(40 - $pregnancyWeeks);
                $nextPrenatalVisit = Carbon::now()->addWeeks(rand(1, 4));
            }

            // Checkup dates
            $lastCheckup = Carbon::now()->subDays(rand(30, 180));
            $nextCheckup = $isSenior 
                ? Carbon::now()->addDays(rand(-10, 60))  // Some overdue for seniors
                : Carbon::now()->addDays(rand(30, 180));

            HealthRecord::create([
                'resident_id' => $resident->id,
                'barangay_id' => $resident->barangay_id,
                
                // Demographics
                'is_senior_citizen' => $isSenior,
                'is_pregnant' => $isPregnant,
                'is_malnourished' => $isMalnourished,
                
                // Vitals
                'height' => $height,
                'weight' => $weight,
                'bmi' => $bmi,
                'blood_pressure' => $bloodPressure,
                'temperature' => $temperature,
                
                // Medical Info
                'blood_type' => $bloodTypes[array_rand($bloodTypes)],
                'allergies' => $commonAllergies[array_rand($commonAllergies)],
                'current_medications' => $isSenior 
                    ? $commonMedications[array_rand($commonMedications)]
                    : (rand(1, 5) == 1 ? $commonMedications[array_rand($commonMedications)] : null),
                'medical_conditions' => $isSenior 
                    ? $commonConditions[array_rand($commonConditions)]
                    : (rand(1, 10) == 1 ? $commonConditions[array_rand($commonConditions)] : null),
                
                // Malnutrition
                'malnutrition_type' => $isMalnourished 
                    ? $malnutritionTypes[array_rand($malnutritionTypes)]
                    : null,
                
                // Pregnancy
                'pregnancy_weeks' => $pregnancyWeeks,
                'trimester' => $trimester,
                'expected_delivery_date' => $expectedDeliveryDate,
                'next_prenatal_visit' => $nextPrenatalVisit,
                'pregnancy_complications' => $isPregnant && rand(1, 10) <= 2 
                    ? 'Gestational diabetes' 
                    : null,
                
                // Immunizations (JSON)
                'immunizations_record' => $this->generateImmunizations($isSenior, $age),
                
                // Checkups
                'last_checkup_date' => $lastCheckup,
                'next_checkup_date' => $nextCheckup,
                
                // Family Planning
                'is_using_family_planning' => !$isPregnant && $resident->gender === 'Female' && $age >= 18 && $age <= 45
                    ? rand(1, 10) <= 5  // 50% chance
                    : false,
                'family_planning_method' => null,
                
                // Emergency Contact
                'emergency_contact' => 'Contact: 09' . rand(100000000, 999999999),
                
                // Notes
                'notes' => $this->generateNotes($isSenior, $isPregnant, $isMalnourished),
                
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ Created ' . $residents->count() . ' health records successfully!');
        $this->command->info('   - Senior Citizens: ' . HealthRecord::where('is_senior_citizen', true)->count());
        $this->command->info('   - Pregnant Women: ' . HealthRecord::where('is_pregnant', true)->count());
        $this->command->info('   - Malnourished: ' . HealthRecord::where('is_malnourished', true)->count());
    }

    /**
     * Generate immunization records
     */
    private function generateImmunizations($isSenior, $age)
    {
        $immunizations = [];
        
        if ($isSenior) {
            $immunizations[] = [
                'vaccine' => 'Flu Vaccine',
                'date' => Carbon::now()->subMonths(rand(1, 11))->format('Y-m-d'),
                'next_dose' => Carbon::now()->addYear()->format('Y-m-d')
            ];
            
            if (rand(1, 2) == 1) {
                $immunizations[] = [
                    'vaccine' => 'Pneumococcal Vaccine',
                    'date' => Carbon::now()->subYears(rand(1, 5))->format('Y-m-d'),
                    'next_dose' => null
                ];
            }
        } elseif ($age < 18) {
            $immunizations[] = [
                'vaccine' => 'MMR',
                'date' => Carbon::now()->subYears(rand(1, $age))->format('Y-m-d'),
                'next_dose' => null
            ];
            
            if ($age >= 9 && $age <= 14) {
                $immunizations[] = [
                    'vaccine' => 'HPV Vaccine',
                    'date' => Carbon::now()->subMonths(rand(1, 12))->format('Y-m-d'),
                    'next_dose' => Carbon::now()->addMonths(6)->format('Y-m-d')
                ];
            }
        }
        
        return empty($immunizations) ? null : json_encode($immunizations);
    }

    /**
     * Generate sample notes
     */
    private function generateNotes($isSenior, $isPregnant, $isMalnourished)
    {
        $notes = [];
        
        if ($isSenior) {
            $notes[] = 'Regular monitoring required for age-related conditions.';
        }
        
        if ($isPregnant) {
            $notes[] = 'Prenatal vitamins prescribed. Regular checkups scheduled.';
        }
        
        if ($isMalnourished) {
            $notes[] = 'Nutritional counseling provided. Follow-up in 2 weeks.';
        }
        
        return empty($notes) ? null : implode(' ', $notes);
    }
}
