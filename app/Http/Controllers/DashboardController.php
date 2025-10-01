<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\FamilyMember;
use App\Models\Household;
use App\Models\CensusHousehold;
use App\Models\CensusMember;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with metrics.
     */
    public function index()
    {
        // Get basic counts (keeping for backward compatibility)
        $basicMetrics = [
            'total_residents' => Resident::count(),
            'households_count' => Household::count(),
            'family_members_count' => FamilyMember::count(),
            // Census data metrics
            'census_households' => CensusHousehold::count(),
            'census_population' => CensusMember::count(),
            'census_male_population' => CensusMember::where('gender', 'Male')->count(),
            'census_female_population' => CensusMember::where('gender', 'Female')->count(),
        ];
        
        // Get deduplicated population statistics
        $populationStats = $this->getPopulationStats();
        $uniquePopulation = $this->calculateUniquePopulation();
        
        // Smart City System Metrics
        $smartCityMetrics = [
            // System Health
            'system_uptime' => '99.9%',
            'active_alerts' => 0,
            'cpu_usage' => 75,
            'db_performance' => 90,
            'service_availability' => 99,
            'uptime_percentage' => '99.9',
            'total_requests' => '35,210',
            
            // Population Growth
            'population_growth' => $this->calculatePopulationGrowth(),
            
            // Service Metrics
            'pending_requests' => $this->getPendingDocumentRequests(),
            'completed_today' => $this->getCompletedRequestsToday(),
            'pending_approval' => $this->getPendingApprovals(),
            'new_registrations' => $this->getNewRegistrationsToday(),
            'new_residents_today' => $this->getNewResidentsToday(),
            
            // System Performance
            'processed_docs' => 160,
            'total_docs' => 200,
        ];
        
        // Combine metrics with deduplicated data
        $metrics = array_merge($basicMetrics, [
            // Use deduplicated counts for accurate population statistics
            'total_population' => $uniquePopulation,
            'male_residents' => $populationStats['male'],
            'female_residents' => $populationStats['female'],
            'children' => $populationStats['children'],
            'adults' => $populationStats['adults'],
            'senior_citizens' => $populationStats['seniors'],
            'non_migrant_count' => $populationStats['non_migrant'],
            'migrant_count' => $populationStats['migrant'],
            'transient_count' => $populationStats['transient'],
            
            // Civil status breakdown (only from registered residents)
            'single_residents' => Resident::where('civil_status', 'Single')->count(),
            'married_residents' => Resident::where('civil_status', 'Married')->count(),
            'widowed_residents' => Resident::where('civil_status', 'Widowed')->count(),
            'separated_residents' => Resident::where('civil_status', 'Separated')->count(),
            'divorced_residents' => Resident::where('civil_status', 'Divorced')->count(),
        ], $smartCityMetrics);
        
        // Add warning if there's significant difference between simple and deduplicated counts
        $simpleTotalPopulation = $basicMetrics['total_residents'] + $basicMetrics['family_members_count'];
        $metrics['potential_duplicates'] = $simpleTotalPopulation - $uniquePopulation;
        
        // Prepare chart data
        $chartData = [
            // Resident type chart
            'resident_types' => [
                'labels' => ['Non-Migrant', 'Migrant', 'Transient'],
                'data' => [
                    $metrics['non_migrant_count'], 
                    $metrics['migrant_count'], 
                    $metrics['transient_count']
                ],
                'colors' => ['#3B82F6', '#F59E0B', '#EF4444']
            ],
            
            // Gender distribution chart
            'gender_distribution' => [
                'labels' => ['Male', 'Female'],
                'data' => [$metrics['male_residents'], $metrics['female_residents']],
                'colors' => ['#3B82F6', '#EC4899']
            ],
            
            // Age group chart
            'age_groups' => [
                'labels' => ['Children (0-17)', 'Adults (18-59)', 'Senior Citizens (60+)'],
                'data' => [
                    $metrics['children'], 
                    $metrics['adults'], 
                    $metrics['senior_citizens']
                ],
                'colors' => ['#10B981', '#6366F1', '#F59E0B']
            ],
            
            // Civil status chart
            'civil_status' => [
                'labels' => ['Single', 'Married', 'Widowed', 'Separated', 'Divorced'],
                'data' => [
                    $metrics['single_residents'],
                    $metrics['married_residents'],
                    $metrics['widowed_residents'],
                    $metrics['separated_residents'],
                    $metrics['divorced_residents']
                ],
                'colors' => ['#3B82F6', '#10B981', '#F59E0B', '#6366F1', '#EF4444']
            ]
        ];
        
        // Monthly registration data for the past 6 months
        $monthlyData = [];
        $monthLabels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->startOfMonth()->subMonths($i);
            $monthLabels[] = $month->format('M Y');
            
            $monthlyData[] = Resident::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }
        
        $chartData['monthly_registrations'] = [
            'labels' => $monthLabels,
            'data' => $monthlyData,
            'colors' => ['#3B82F6']
        ];
        
        return view('admin.dashboard', compact('metrics', 'chartData'));
    }
    
    /**
     * Calculate unique population count with deduplication logic
     * 
     * @return int
     */
    private function calculateUniquePopulation()
    {
        // Get all residents
        $residents = Resident::select('first_name', 'last_name', 'middle_name', 'birthdate', 'sex')->get();
        
        // Get all family members
        $familyMembers = FamilyMember::select('name', 'birthday', 'gender')->get();
        
        // Create a set to track unique individuals
        $uniqueIndividuals = collect();
        
        // Add residents to unique set
        foreach ($residents as $resident) {
            $key = $this->generatePersonKey(
                $resident->first_name . ' ' . ($resident->middle_name ? $resident->middle_name . ' ' : '') . $resident->last_name,
                $resident->birthdate,
                $resident->sex
            );
            
            $uniqueIndividuals->put($key, [
                'name' => $resident->first_name . ' ' . $resident->last_name,
                'birthdate' => $resident->birthdate,
                'sex' => $resident->sex,
                'type' => 'resident'
            ]);
        }
        
        // Add family members to unique set (only if not already present)
        foreach ($familyMembers as $member) {
            $key = $this->generatePersonKey($member->name, $member->birthday, $member->gender);
            
            // Only add if not already present (avoiding duplicates)
            if (!$uniqueIndividuals->has($key)) {
                $uniqueIndividuals->put($key, [
                    'name' => $member->name,
                    'birthdate' => $member->birthday,
                    'sex' => $member->gender,
                    'type' => 'family_member'
                ]);
            }
        }
        
        return $uniqueIndividuals->count();
    }
    
    /**
     * Generate a unique key for a person based on name, birthdate, and gender
     * 
     * @param string $name
     * @param \Carbon\Carbon|null $birthdate
     * @param string $gender
     * @return string
     */
    private function generatePersonKey($name, $birthdate, $gender)
    {
        // Normalize name (remove extra spaces, convert to lowercase)
        $normalizedName = strtolower(preg_replace('/\s+/', ' ', trim($name)));
        
        // Format birthdate
        $birthdateStr = $birthdate ? $birthdate->format('Y-m-d') : 'unknown';
        
        // Generate key
        return md5($normalizedName . '|' . $birthdateStr . '|' . strtolower($gender));
    }
    
    /**
     * Get population statistics with deduplication
     * 
     * @return array
     */
    private function getPopulationStats()
    {
        // Get deduplicated counts by demographic
        $residents = Resident::select('first_name', 'last_name', 'middle_name', 'birthdate', 'sex', 'type_of_resident')->get();
        $familyMembers = FamilyMember::select('name', 'birthday', 'gender')->get();
        
        $uniqueIndividuals = collect();
        $demographics = [
            'male' => 0,
            'female' => 0,
            'children' => 0,
            'adults' => 0,
            'seniors' => 0,
            'non_migrant' => 0,
            'migrant' => 0,
            'transient' => 0
        ];
        
        // Process residents
        foreach ($residents as $resident) {
            $key = $this->generatePersonKey(
                $resident->first_name . ' ' . ($resident->middle_name ? $resident->middle_name . ' ' : '') . $resident->last_name,
                $resident->birthdate,
                $resident->sex
            );
            
            if (!$uniqueIndividuals->has($key)) {
                $uniqueIndividuals->put($key, true);
                
                // Count demographics
                if ($resident->sex === 'Male') $demographics['male']++;
                if ($resident->sex === 'Female') $demographics['female']++;
                
                $age = $resident->birthdate ? $resident->birthdate->age : 0;
                if ($age < 18) $demographics['children']++;
                elseif ($age < 60) $demographics['adults']++;
                else $demographics['seniors']++;
                
                // Count by resident type
                if ($resident->type_of_resident === 'Non-Migrant') $demographics['non_migrant']++;
                elseif ($resident->type_of_resident === 'Migrant') $demographics['migrant']++;
                elseif ($resident->type_of_resident === 'Transient') $demographics['transient']++;
            }
        }
        
        // Process family members (only if not already counted as residents)
        foreach ($familyMembers as $member) {
            $key = $this->generatePersonKey($member->name, $member->birthday, $member->gender);
            
            if (!$uniqueIndividuals->has($key)) {
                $uniqueIndividuals->put($key, true);
                
                // Count demographics for family members
                if ($member->gender === 'Male') $demographics['male']++;
                if ($member->gender === 'Female') $demographics['female']++;
                
                $age = $member->birthday ? $member->birthday->age : 0;
                if ($age < 18) $demographics['children']++;
                elseif ($age < 60) $demographics['adults']++;
                else $demographics['seniors']++;
                
                // Family members are assumed to be non-migrant unless specified otherwise
                $demographics['non_migrant']++;
            }
        }
        
        return $demographics;
    }
    
    /**
     * Get potential duplicate entries for admin review
     * 
     * @return array
     */
    public function getPotentialDuplicates()
    {
        $residents = Resident::select('id', 'first_name', 'last_name', 'middle_name', 'birthdate', 'sex')->get();
        $familyMembers = FamilyMember::select('id', 'resident_id', 'name', 'birthday', 'gender')->get();
        
        $potentialDuplicates = [];
        $processedKeys = [];
        
        // Check for residents who might also exist as family members
        foreach ($residents as $resident) {
            $residentKey = $this->generatePersonKey(
                $resident->first_name . ' ' . ($resident->middle_name ? $resident->middle_name . ' ' : '') . $resident->last_name,
                $resident->birthdate,
                $resident->sex
            );
            
            foreach ($familyMembers as $member) {
                $memberKey = $this->generatePersonKey($member->name, $member->birthday, $member->gender);
                
                if ($residentKey === $memberKey) {
                    $potentialDuplicates[] = [
                        'type' => 'resident_family_member',
                        'resident' => [
                            'id' => $resident->id,
                            'name' => $resident->first_name . ' ' . $resident->last_name,
                            'birthdate' => $resident->birthdate,
                            'sex' => $resident->sex
                        ],
                        'family_member' => [
                            'id' => $member->id,
                            'resident_id' => $member->resident_id,
                            'name' => $member->name,
                            'birthday' => $member->birthday,
                            'gender' => $member->gender
                        ],
                        'confidence' => 'high'
                    ];
                }
            }
        }
        
        // Check for duplicate family members across different households
        foreach ($familyMembers as $member1) {
            $key1 = $this->generatePersonKey($member1->name, $member1->birthday, $member1->gender);
            
            if (in_array($key1, $processedKeys)) continue;
            
            $duplicates = [];
            foreach ($familyMembers as $member2) {
                if ($member1->id === $member2->id) continue;
                
                $key2 = $this->generatePersonKey($member2->name, $member2->birthday, $member2->gender);
                
                if ($key1 === $key2) {
                    $duplicates[] = $member2;
                }
            }
            
            if (!empty($duplicates)) {
                $potentialDuplicates[] = [
                    'type' => 'duplicate_family_members',
                    'original' => $member1,
                    'duplicates' => $duplicates,
                    'confidence' => 'high'
                ];
                
                $processedKeys[] = $key1;
            }
        }
        
        return $potentialDuplicates;
    }
    
    /**
     * Smart City System Metrics Methods
     */
    private function calculatePopulationGrowth()
    {
        // Calculate population growth rate (placeholder - you can enhance this)
        $currentMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();
        
        $currentMonthCount = Resident::where('created_at', '>=', $currentMonth)->count();
        $lastMonthCount = Resident::whereBetween('created_at', [$lastMonth, $currentMonth])->count();
        
        if ($lastMonthCount > 0) {
            $growthRate = (($currentMonthCount - $lastMonthCount) / $lastMonthCount) * 100;
            return round($growthRate, 1);
        }
        
        return 0;
    }
    
    private function getPendingDocumentRequests()
    {
        // This would depend on your document request system
        // For now, return a placeholder value
        return 45;
    }
    
    private function getCompletedRequestsToday()
    {
        // Count documents/requests completed today
        // Placeholder implementation
        return 12;
    }
    
    private function getPendingApprovals()
    {
        // Count pending approvals
        // Placeholder implementation  
        return 8;
    }
    
    private function getNewRegistrationsToday()
    {
        return Resident::whereDate('created_at', today())->count();
    }
    
    private function getNewResidentsToday()
    {
        $todayResidents = Resident::whereDate('created_at', today())->count();
        $todayFamilyMembers = FamilyMember::whereDate('created_at', today())->count();
        
        return $todayResidents + $todayFamilyMembers;
    }
}
