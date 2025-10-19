<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Complaint;
use App\Models\Resident;

class ComplaintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first few residents to create sample complaints
        $residents = Resident::take(8)->get();
        
        if ($residents->isEmpty()) {
            echo "No residents found. Please create residents first.\n";
            return;
        }

        $sampleComplaints = [
            [
                'complaint_type' => 'noise_complaint',
                'subject' => 'Loud Music from Neighbor',
                'description' => 'My neighbor plays extremely loud music every night until 2 AM, disturbing the peace of the entire street.',
                'incident_details' => 'This has been happening for the past two weeks consistently.',
                'incident_location' => 'Block 5, Lot 12',
                'priority' => 'medium',
                'status' => 'pending'
            ],
            [
                'complaint_type' => 'property_dispute',
                'subject' => 'Boundary Issue with Neighbor',
                'description' => 'My neighbor has built a fence that extends into my property by approximately 2 meters.',
                'incident_details' => 'I have the property documents that clearly show the boundary lines.',
                'incident_location' => 'Block 3, Lot 8',
                'priority' => 'high',
                'status' => 'approved'
            ],
            [
                'complaint_type' => 'infrastructure_problem',
                'subject' => 'Broken Street Light',
                'description' => 'The street light in our area has been broken for over a month, making it dangerous to walk at night.',
                'incident_details' => 'There have been several incidents of people tripping and falling due to poor visibility.',
                'incident_location' => 'Main Street corner',
                'priority' => 'high',
                'status' => 'scheduled'
            ],
            [
                'complaint_type' => 'drainage_problem',
                'subject' => 'Clogged Drainage System',
                'description' => 'The drainage in our street is clogged, causing flooding during heavy rains.',
                'incident_details' => 'Water reaches knee-deep during typhoons.',
                'incident_location' => 'Block 7 Street',
                'priority' => 'urgent',
                'status' => 'approved'
            ],
            [
                'complaint_type' => 'public_safety',
                'subject' => 'Stray Dogs Issue',
                'description' => 'Multiple stray dogs are roaming in our area, some appear aggressive and pose a threat to children.',
                'incident_details' => 'A child was recently chased by one of the dogs.',
                'incident_location' => 'Block 2 vicinity',
                'priority' => 'high',
                'status' => 'resolved'
            ],
            [
                'complaint_type' => 'waste_management',
                'subject' => 'Improper Garbage Disposal',
                'description' => 'Some residents are dumping garbage in vacant lots instead of proper disposal areas.',
                'incident_details' => 'The area is starting to smell and attracting pests.',
                'incident_location' => 'Vacant lot near Block 4',
                'priority' => 'medium',
                'status' => 'pending'
            ],
            [
                'complaint_type' => 'illegal_construction',
                'subject' => 'Unauthorized Building Extension',
                'description' => 'My neighbor is building an extension without proper permits, blocking our shared pathway.',
                'incident_details' => 'Construction started without notice and is affecting our access.',
                'incident_location' => 'Block 6, Lot 15',
                'priority' => 'high',
                'status' => 'approved'
            ],
            [
                'complaint_type' => 'water_supply',
                'subject' => 'Intermittent Water Supply',
                'description' => 'Our area has been experiencing water shortages for the past week.',
                'incident_details' => 'Water only comes out for 2-3 hours per day.',
                'incident_location' => 'Block 1 area',
                'priority' => 'urgent',
                'status' => 'resolved'
            ]
        ];

        foreach ($residents as $index => $resident) {
            if (isset($sampleComplaints[$index])) {
                $complaintData = $sampleComplaints[$index];
                
                // Add random involved parties for some complaints
                $involvedParties = [];
                if (rand(0, 1)) {
                    $involvedParties = [
                        'Juan Dela Cruz',
                        'Maria Santos'
                    ];
                }

                Complaint::create([
                    'barangay_id' => $resident->barangay_id,
                    'complaint_type' => $complaintData['complaint_type'],
                    'priority' => $complaintData['priority'],
                    'subject' => $complaintData['subject'],
                    'description' => $complaintData['description'],
                    'incident_details' => $complaintData['incident_details'],
                    'incident_date' => now()->subDays(rand(1, 30)),
                    'incident_location' => $complaintData['incident_location'],
                    'involved_parties' => $involvedParties,
                    'status' => $complaintData['status'],
                    'filed_at' => now()->subDays(rand(1, 30)),
                    'approved_at' => in_array($complaintData['status'], ['approved', 'scheduled', 'resolved']) ? now()->subDays(rand(1, 15)) : null,
                    'approved_by' => in_array($complaintData['status'], ['approved', 'scheduled', 'resolved']) ? 1 : null,
                    'scheduled_at' => $complaintData['status'] === 'scheduled' ? now()->addDays(rand(1, 7)) : null,
                    'resolved_at' => $complaintData['status'] === 'resolved' ? now()->subDays(rand(1, 5)) : null,
                ]);
            }
        }

        echo "Created " . count($sampleComplaints) . " sample complaints.\n";
    }
}
