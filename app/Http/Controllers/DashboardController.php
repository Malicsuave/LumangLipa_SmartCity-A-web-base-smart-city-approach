<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\FamilyMember;
use App\Models\Household;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with metrics.
     */
    public function index()
    {
        // Get resident counts
        $metrics = [
            'total_residents' => Resident::count(),
            'non_migrant_count' => Resident::where('type_of_resident', 'Non-Migrant')->count(),
            'migrant_count' => Resident::where('type_of_resident', 'Migrant')->count(),
            'transient_count' => Resident::where('type_of_resident', 'Transient')->count(),
            'households_count' => Household::count(),
            'family_members_count' => FamilyMember::count(),
            'total_population' => Resident::count() + FamilyMember::count(),
            
            // Get resident counts by gender
            'male_residents' => Resident::where('sex', 'Male')->count(),
            'female_residents' => Resident::where('sex', 'Female')->count(),
            
            // Get resident counts by age group
            'children' => Resident::whereDate('birthdate', '>=', Carbon::now()->subYears(18))->count(),
            'adults' => Resident::whereDate('birthdate', '<', Carbon::now()->subYears(18))
                               ->whereDate('birthdate', '>=', Carbon::now()->subYears(60))
                               ->count(),
            'senior_citizens' => Resident::whereDate('birthdate', '<', Carbon::now()->subYears(60))->count(),
            
            // Civil status breakdown
            'single_residents' => Resident::where('civil_status', 'Single')->count(),
            'married_residents' => Resident::where('civil_status', 'Married')->count(),
            'widowed_residents' => Resident::where('civil_status', 'Widowed')->count(),
            'separated_residents' => Resident::where('civil_status', 'Separated')->count(),
            'divorced_residents' => Resident::where('civil_status', 'Divorced')->count(),
        ];
        
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
}
