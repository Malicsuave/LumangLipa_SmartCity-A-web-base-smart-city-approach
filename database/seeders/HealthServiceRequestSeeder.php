<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HealthServiceRequest;
use App\Models\Resident;

class HealthServiceRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first few residents to create sample requests
        $residents = Resident::take(5)->get();
        
        if ($residents->isEmpty()) {
            echo "No residents found. Please create residents first.\n";
            return;
        }

        $serviceTypes = [
            'medical_consultation',
            'blood_pressure_check',
            'vaccination',
            'prenatal_checkup',
            'health_certificate',
            'medicine_distribution'
        ];

        $priorities = ['low', 'medium', 'high'];
        $statuses = ['pending', 'approved', 'scheduled', 'completed'];

        foreach ($residents as $index => $resident) {
            // Create 2-3 sample requests per resident
            $requestCount = rand(2, 3);
            
            for ($i = 0; $i < $requestCount; $i++) {
                $serviceType = $serviceTypes[array_rand($serviceTypes)];
                $priority = $priorities[array_rand($priorities)];
                $status = $statuses[array_rand($statuses)];
                
                $requestedAt = now()->subDays(rand(1, 30));
                $approvedAt = null;
                $scheduledAt = null;
                $completedAt = null;
                
                if (in_array($status, ['approved', 'scheduled', 'completed'])) {
                    $approvedAt = $requestedAt->addHours(rand(1, 24));
                }
                
                if (in_array($status, ['scheduled', 'completed'])) {
                    $scheduledAt = $approvedAt->addDays(rand(1, 7));
                }
                
                if ($status === 'completed') {
                    $completedAt = $scheduledAt->addHours(rand(1, 4));
                }

                HealthServiceRequest::create([
                    'barangay_id' => $resident->barangay_id,
                    'service_type' => $serviceType,
                    'priority' => $priority,
                    'purpose' => $this->generatePurpose($serviceType),
                    'health_concern' => $this->generateHealthConcern($serviceType),
                    'symptoms' => rand(0, 1) ? $this->generateSymptoms($serviceType) : null,
                    'status' => $status,
                    'requested_at' => $requestedAt,
                    'approved_at' => $approvedAt,
                    'approved_by' => $approvedAt ? 1 : null, // Assuming user ID 1 exists
                    'scheduled_at' => $scheduledAt,
                    'completed_at' => $completedAt,
                    'rejection_reason' => null,
                    'admin_notes' => null,
                ]);
            }
        }

        echo "Sample health service requests created successfully!\n";
    }

    private function generatePurpose($serviceType)
    {
        $purposes = [
            'medical_consultation' => [
                'I have been experiencing persistent headaches and would like to consult with a medical professional.',
                'Regular health check-up to monitor my overall health condition.',
                'Follow-up consultation for previous medical condition.'
            ],
            'blood_pressure_check' => [
                'Routine blood pressure monitoring as advised by my doctor.',
                'I have been feeling dizzy lately and want to check my blood pressure.',
                'Family history of hypertension, need regular monitoring.'
            ],
            'vaccination' => [
                'Need to get COVID-19 booster shot.',
                'Required vaccination for travel purposes.',
                'Routine vaccination as part of health maintenance.'
            ],
            'prenatal_checkup' => [
                'Regular prenatal examination during pregnancy.',
                'First prenatal visit to confirm pregnancy.',
                'Follow-up prenatal check-up as scheduled.'
            ],
            'health_certificate' => [
                'Required health certificate for employment.',
                'Need medical certificate for school enrollment.',
                'Health clearance for travel requirements.'
            ],
            'medicine_distribution' => [
                'Need to get prescribed medication for hypertension.',
                'Requesting vitamins and supplements for senior citizens.',
                'Pick up maintenance medication as prescribed.'
            ]
        ];

        $typePurposes = $purposes[$serviceType] ?? ['General health service request.'];
        return $typePurposes[array_rand($typePurposes)];
    }

    private function generateHealthConcern($serviceType)
    {
        $concerns = [
            'medical_consultation' => [
                'Experiencing frequent headaches and fatigue.',
                'Concerned about persistent cough and chest discomfort.',
                'Having trouble sleeping and feeling anxious.'
            ],
            'blood_pressure_check' => [
                'History of high blood pressure in family.',
                'Recent episodes of dizziness and palpitations.',
                'Monitoring blood pressure due to medication changes.'
            ],
            'vaccination' => [
                'Want to ensure protection against COVID-19.',
                'Need vaccination for international travel.',
                'Keeping up with recommended vaccination schedule.'
            ],
            'prenatal_checkup' => [
                'First pregnancy, want to ensure everything is normal.',
                'Previous pregnancy complications, need monitoring.',
                'Regular check-up to monitor baby\'s development.'
            ],
            'health_certificate' => [
                'Required for pre-employment medical examination.',
                'Need clearance for sports participation.',
                'Health screening for visa application.'
            ],
            'medicine_distribution' => [
                'Running low on prescribed medication.',
                'Need medication for chronic condition management.',
                'Doctor recommended vitamins and supplements.'
            ]
        ];

        $typeConcerns = $concerns[$serviceType] ?? ['General health concern.'];
        return $typeConcerns[array_rand($typeConcerns)];
    }

    private function generateSymptoms($serviceType)
    {
        $symptoms = [
            'medical_consultation' => [
                'Headache, fatigue, mild fever',
                'Persistent cough, chest pain, shortness of breath',
                'Insomnia, anxiety, loss of appetite'
            ],
            'blood_pressure_check' => [
                'Dizziness, headache, blurred vision',
                'Chest pain, palpitations, nausea',
                'Fatigue, weakness, confusion'
            ],
            'vaccination' => [
                'No current symptoms, preventive measure',
                'Mild fever from previous vaccination',
                'No symptoms, routine vaccination'
            ],
            'prenatal_checkup' => [
                'Morning sickness, fatigue, breast tenderness',
                'Mild nausea, frequent urination, back pain',
                'No symptoms, routine check-up'
            ],
            'health_certificate' => [
                'No current symptoms, routine examination',
                'Generally feeling well, preventive check',
                'No symptoms, required for clearance'
            ],
            'medicine_distribution' => [
                'No current symptoms, maintenance medication',
                'Mild symptoms managed with current medication',
                'Stable condition, need refill'
            ]
        ];

        $typeSymptoms = $symptoms[$serviceType] ?? ['No specific symptoms reported.'];
        return $typeSymptoms[array_rand($typeSymptoms)];
    }
}
