<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resident;
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
        // Get basic counts
        $basicMetrics = [
            'total_residents' => Resident::count(),
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
        $simpleTotalPopulation = $basicMetrics['total_residents'];
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
     * @return int
     */
    private function calculateUniquePopulation()
    {
        // Get all residents
        $residents = Resident::select('first_name', 'last_name', 'middle_name', 'birthdate', 'sex')->get();
        $uniqueIndividuals = collect();
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
        return $uniqueIndividuals->count();
    }

    /**
     * Generate a unique key for a person based on name, birthdate, and gender
     * @param string $name
     * @param \Carbon\Carbon|null $birthdate
     * @param string $gender
     * @return string
     */
    private function generatePersonKey($name, $birthdate, $gender)
    {
        $normalizedName = strtolower(preg_replace('/\s+/', ' ', trim($name)));
        $birthdateStr = $birthdate ? $birthdate->format('Y-m-d') : 'unknown';
        return md5($normalizedName . '|' . $birthdateStr . '|' . strtolower($gender));
    }

    /**
     * Get population statistics
     * @return array
     */
    private function getPopulationStats()
    {
        $residents = Resident::select('first_name', 'last_name', 'middle_name', 'birthdate', 'sex', 'type_of_resident')->get();
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
        foreach ($residents as $resident) {
            $key = $this->generatePersonKey(
                $resident->first_name . ' ' . ($resident->middle_name ? $resident->middle_name . ' ' : '') . $resident->last_name,
                $resident->birthdate,
                $resident->sex
            );
            if (!$uniqueIndividuals->has($key)) {
                $uniqueIndividuals->put($key, true);
                if ($resident->sex === 'Male') $demographics['male']++;
                if ($resident->sex === 'Female') $demographics['female']++;
                $age = $resident->birthdate ? $resident->birthdate->age : 0;
                if ($age < 18) $demographics['children']++;
                elseif ($age < 60) $demographics['adults']++;
                else $demographics['seniors']++;
                if ($resident->type_of_resident === 'Non-Migrant') $demographics['non_migrant']++;
                elseif ($resident->type_of_resident === 'Migrant') $demographics['migrant']++;
                elseif ($resident->type_of_resident === 'Transient') $demographics['transient']++;
            }
        }
        return $demographics;
    }

    /**
     * Get potential duplicate entries for admin review
     * @return array
     */
    public function getPotentialDuplicates()
    {
        $residents = Resident::select('id', 'first_name', 'last_name', 'middle_name', 'birthdate', 'sex')->get();
        $potentialDuplicates = [];
        $processedKeys = [];

        // Check for residents who might also exist as family members
        foreach ($residents as $resident) {
            $residentKey = $this->generatePersonKey(
                $resident->first_name . ' ' . ($resident->middle_name ? $resident->middle_name . ' ' : '') . $resident->last_name,
                $resident->birthdate,
                $resident->sex
            );

            foreach ($residents as $compareResident) {
                if ($resident->id === $compareResident->id) continue;

                $compareResidentKey = $this->generatePersonKey(
                    $compareResident->first_name . ' ' . ($compareResident->middle_name ? $compareResident->middle_name . ' ' : '') . $compareResident->last_name,
                    $compareResident->birthdate,
                    $compareResident->sex
                );

                if ($residentKey === $compareResidentKey) {
                    $potentialDuplicates[] = [
                        'type' => 'resident_resident',
                        'resident1' => [
                            'id' => $resident->id,
                            'name' => $resident->first_name . ' ' . $resident->last_name,
                            'birthdate' => $resident->birthdate,
                            'sex' => $resident->sex
                        ],
                        'resident2' => [
                            'id' => $compareResident->id,
                            'name' => $compareResident->first_name . ' ' . $compareResident->last_name,
                            'birthdate' => $compareResident->birthdate,
                            'sex' => $compareResident->sex
                        ],
                        'confidence' => 'high'
                    ];
                }
            }
        }

        return $potentialDuplicates;
    }

    /**
     * Smart City System Metrics Methods
     */
    private function calculatePopulationGrowth()
    {
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
    private function getPendingDocumentRequests() { return 45; }
    private function getCompletedRequestsToday() { return 12; }
    private function getPendingApprovals() { return 8; }
    private function getNewRegistrationsToday() { return Resident::whereDate('created_at', today())->count(); }
    private function getNewResidentsToday() { return Resident::whereDate('created_at', today())->count(); }
}
