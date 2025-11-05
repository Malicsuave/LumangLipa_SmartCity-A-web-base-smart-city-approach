<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthRecord;
use App\Models\Resident;
use App\Models\HealthServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class HealthMonitoringController extends Controller
{
    /**
     * Display health monitoring dashboard
     */
    public function index(Request $request)
    {
        try {
            // Get demographics statistics
            $stats = $this->getHealthStatistics();
            
            // Get category filter from query parameter
            $category = $request->query('category');
            
            // Build query for recent records based on category
            $recordsQuery = HealthRecord::with('resident');
            
            // Apply category filters
            if ($category === 'senior') {
                // Senior citizens (60+)
                $recordsQuery->whereHas('resident', function ($query) {
                    $query->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= 60');
                });
            } elseif ($category === 'pregnant') {
                // Pregnant women
                $recordsQuery->where('is_pregnant', true);
            } elseif ($category === 'malnourished') {
                // Malnourished (BMI < 18.5)
                $recordsQuery->where('bmi', '<', 18.5)
                    ->whereNotNull('bmi');
            }
            
            // Get recent health records
            $recentRecords = $recordsQuery->latest()
                ->limit(10)
                ->get();
            
            // Get overdue checkups and prenatal visits
            $overdueCheckups = HealthRecord::with('resident')
                ->where('next_checkup_date', '<', now())
                ->whereNotNull('next_checkup_date')
                ->get();
            
            $overduePrenatal = HealthRecord::with('resident')
                ->where('is_pregnant', true)
                ->where('next_prenatal_visit', '<', now())
                ->whereNotNull('next_prenatal_visit')
                ->get();
            
            return view('admin.health-monitoring.index', compact(
                'stats',
                'recentRecords',
                'overdueCheckups',
                'overduePrenatal',
                'category'
            ));
        } catch (\Exception $e) {
            Log::error('Health Monitoring Dashboard Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading health monitoring dashboard.');
        }
    }

    /**
     * Get health statistics
     */
    private function getHealthStatistics()
    {
        return [
            // Demographics
            'total_seniors' => HealthRecord::where('is_senior_citizen', true)->count(),
            'total_pregnant' => HealthRecord::where('is_pregnant', true)->count(),
            'total_malnourished' => HealthRecord::where('is_malnourished', true)->count(),
            'total_family_planning' => HealthRecord::where('is_using_family_planning', true)->count(),
            
            // By trimester
            'first_trimester' => HealthRecord::where('is_pregnant', true)->where('trimester', 1)->count(),
            'second_trimester' => HealthRecord::where('is_pregnant', true)->where('trimester', 2)->count(),
            'third_trimester' => HealthRecord::where('is_pregnant', true)->where('trimester', 3)->count(),
            
            // Malnutrition breakdown
            'underweight' => HealthRecord::where('is_malnourished', true)->where('malnutrition_type', 'underweight')->count(),
            'stunted' => HealthRecord::where('is_malnourished', true)->where('malnutrition_type', 'stunted')->count(),
            'wasted' => HealthRecord::where('is_malnourished', true)->where('malnutrition_type', 'wasted')->count(),
            'overweight' => HealthRecord::where('is_malnourished', true)->where('malnutrition_type', 'overweight')->count(),
            
            // Service statistics (this month)
            'immunizations_this_month' => HealthServiceRequest::where('service_type', 'LIKE', '%immunization%')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'checkups_this_month' => HealthServiceRequest::where('service_type', 'LIKE', '%checkup%')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'prenatal_this_month' => HealthServiceRequest::where('service_type', 'prenatal_checkup')
                ->whereMonth('created_at', now()->month)
                ->count(),
            'family_planning_this_month' => HealthServiceRequest::where('service_type', 'family_planning')
                ->whereMonth('created_at', now()->month)
                ->count(),
            
            // Overdue monitoring
            'overdue_checkups' => HealthRecord::where('next_checkup_date', '<', now())
                ->whereNotNull('next_checkup_date')
                ->count(),
            'overdue_prenatal' => HealthRecord::where('is_pregnant', true)
                ->where('next_prenatal_visit', '<', now())
                ->whereNotNull('next_prenatal_visit')
                ->count(),
        ];
    }

    /**
     * Show senior citizens health records
     */
    public function seniors(Request $request)
    {
        $query = HealthRecord::with('resident')
            ->where('is_senior_citizen', true);
        
        // Apply filters
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('resident', function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('barangay_id', 'LIKE', "%{$search}%");
            });
        }
        
        $seniors = $query->paginate(20);
        
        return view('admin.health-monitoring.seniors', compact('seniors'));
    }

    /**
     * Show pregnant women health records
     */
    public function pregnant(Request $request)
    {
        $query = HealthRecord::with('resident')
            ->where('is_pregnant', true);
        
        // Apply filters
        if ($request->has('trimester')) {
            $query->where('trimester', $request->trimester);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('resident', function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('barangay_id', 'LIKE', "%{$search}%");
            });
        }
        
        $pregnant = $query->orderBy('expected_due_date', 'asc')->paginate(20);
        
        return view('admin.health-monitoring.pregnant', compact('pregnant'));
    }

    /**
     * Show malnourished residents health records
     */
    public function malnourished(Request $request)
    {
        $query = HealthRecord::with('resident')
            ->where('is_malnourished', true);
        
        // Apply filters
        if ($request->has('type')) {
            $query->where('malnutrition_type', $request->type);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('resident', function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('barangay_id', 'LIKE', "%{$search}%");
            });
        }
        
        $malnourished = $query->paginate(20);
        
        return view('admin.health-monitoring.malnourished', compact('malnourished'));
    }

    /**
     * Show form to create a new health record
     */
    public function create()
    {
        $residents = Resident::orderBy('last_name')->get();
        return view('admin.health-monitoring.create', compact('residents'));
    }

    /**
     * Show form to edit a health record
     */
    public function edit($id)
    {
        $healthRecord = HealthRecord::with('resident')->findOrFail($id);
        return view('admin.health-monitoring.edit', compact('healthRecord'));
    }

    /**
     * Update an existing health record
     */
    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'resident_id' => 'required|exists:residents,id',
                'is_senior_citizen' => 'nullable|boolean',
                'is_pregnant' => 'nullable|boolean',
                'is_malnourished' => 'nullable|boolean',
                'height' => 'nullable|numeric',
                'weight' => 'nullable|numeric',
                'blood_pressure' => 'nullable|string',
                'temperature' => 'nullable|numeric',
                'blood_type' => 'nullable|string',
                'allergies' => 'nullable|string',
                'current_medications' => 'nullable|string',
                'medical_conditions' => 'nullable|string',
                'malnutrition_type' => 'nullable|string',
                'pregnancy_weeks' => 'nullable|integer',
                'expected_delivery_date' => 'nullable|date',
                'next_prenatal_visit' => 'nullable|date',
                'pregnancy_complications' => 'nullable|string',
                'last_checkup_date' => 'nullable|date',
                'next_checkup_date' => 'nullable|date',
                'emergency_contact' => 'nullable|string',
                'notes' => 'nullable|string',
            ]);

            $healthRecord = HealthRecord::findOrFail($id);
            
            // Calculate BMI if height and weight provided
            if ($request->height && $request->weight) {
                $heightInMeters = $request->height / 100;
                $validated['bmi'] = round($request->weight / ($heightInMeters * $heightInMeters), 2);
            }
            
            // Calculate trimester if pregnancy weeks provided
            if ($request->pregnancy_weeks) {
                $validated['trimester'] = $this->calculateTrimester($request->pregnancy_weeks);
            }
            
            // Convert checkboxes to boolean
            $validated['is_senior_citizen'] = $request->has('is_senior_citizen') ? 1 : 0;
            $validated['is_pregnant'] = $request->has('is_pregnant') ? 1 : 0;
            $validated['is_malnourished'] = $request->has('is_malnourished') ? 1 : 0;

            $healthRecord->update($validated);

            return redirect()
                ->route('admin.health-monitoring.show', $healthRecord->id)
                ->with('success', 'Health record updated successfully.');
                
        } catch (\Exception $e) {
            Log::error('Health Record Update Error: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error updating health record: ' . $e->getMessage());
        }
    }

    /**
     * Create or update health record
     */
    public function storeOrUpdate(Request $request, $barangayId = null)
    {
        $validated = $request->validate([
            'barangay_id' => 'required|exists:residents,barangay_id',
            'is_senior_citizen' => 'boolean',
            'is_pregnant' => 'boolean',
            'expected_due_date' => 'nullable|date|after:today',
            'trimester' => 'nullable|integer|min:1|max:3',
            'is_malnourished' => 'boolean',
            'malnutrition_type' => 'nullable|in:underweight,stunted,wasted,overweight',
            'height' => 'nullable|numeric|min:0|max:300',
            'weight' => 'nullable|numeric|min:0|max:500',
            'blood_type' => 'nullable|string|max:10',
            'blood_pressure' => 'nullable|string|max:20',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'medical_conditions' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'allergies' => 'nullable|string',
            'is_using_family_planning' => 'boolean',
            'family_planning_method' => 'nullable|string|max:100',
            'emergency_contact_name' => 'nullable|string|max:100',
            'emergency_contact_number' => 'nullable|string|max:20',
            'emergency_contact_relationship' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        try {
            // Calculate BMI if height and weight provided
            if (isset($validated['height']) && isset($validated['weight'])) {
                $heightInMeters = $validated['height'] / 100;
                $validated['bmi'] = round($validated['weight'] / ($heightInMeters * $heightInMeters), 2);
            }

            $validated['updated_by'] = auth()->id();

            $healthRecord = HealthRecord::updateOrCreate(
                ['barangay_id' => $validated['barangay_id']],
                array_merge($validated, ['created_by' => auth()->id()])
            );

            return response()->json([
                'success' => true,
                'message' => 'Health record updated successfully!',
                'data' => $healthRecord
            ]);
        } catch (\Exception $e) {
            Log::error('Health Record Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating health record.'
            ], 500);
        }
    }

    /**
     * Show health record details
     */
    public function show($id)
    {
        try {
            $healthRecord = HealthRecord::with(['resident', 'creator', 'updater'])->findOrFail($id);
            
            // Get service history
            $serviceHistory = HealthServiceRequest::where('barangay_id', $healthRecord->barangay_id)
                ->orderBy('requested_at', 'desc')
                ->get();
            
            return view('admin.health-monitoring.show', compact('healthRecord', 'serviceHistory'));
        } catch (\Exception $e) {
            Log::error('Health Record Show Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Health record not found.');
        }
    }

    /**
     * Get health record for API
     */
    public function getRecord($barangayId)
    {
        try {
            $healthRecord = HealthRecord::with('resident')
                ->where('barangay_id', $barangayId)
                ->first();
            
            if (!$healthRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'No health record found for this resident.'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $healthRecord
            ]);
        } catch (\Exception $e) {
            Log::error('Get Health Record Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error fetching health record.'
            ], 500);
        }
    }

    /**
     * Export health monitoring data to CSV
     */
    public function export(Request $request)
    {
        try {
            $type = $request->query('type', 'all');
            
            // Build query based on type
            $query = HealthRecord::with('resident');
            
            switch ($type) {
                case 'seniors':
                    $query->where('is_senior_citizen', true);
                    $filename = 'senior_citizens_health_records';
                    break;
                case 'pregnant':
                    $query->where('is_pregnant', true);
                    $filename = 'pregnant_women_health_records';
                    break;
                case 'malnourished':
                    $query->where('is_malnourished', true);
                    $filename = 'malnourished_health_records';
                    break;
                default:
                    $filename = 'all_health_records';
            }
            
            $records = $query->get();
            
            // Set headers for CSV download
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '_' . date('Y-m-d') . '.csv"',
            ];
            
            $callback = function() use ($records, $type) {
                $file = fopen('php://output', 'w');
                
                // Add CSV headers
                $headers = [
                    'Barangay ID',
                    'Name',
                    'Age',
                    'Gender',
                    'Blood Type',
                    'Height (cm)',
                    'Weight (kg)',
                    'BMI',
                    'Blood Pressure',
                    'Temperature',
                ];
                
                // Add type-specific headers
                if ($type == 'pregnant' || $type == 'all') {
                    $headers = array_merge($headers, [
                        'Pregnant',
                        'Pregnancy Weeks',
                        'Trimester',
                        'Expected Delivery',
                        'Next Prenatal Visit'
                    ]);
                }
                
                if ($type == 'seniors' || $type == 'all') {
                    $headers[] = 'Senior Citizen';
                }
                
                if ($type == 'malnourished' || $type == 'all') {
                    $headers = array_merge($headers, [
                        'Malnourished',
                        'Malnutrition Type'
                    ]);
                }
                
                $headers = array_merge($headers, [
                    'Medical Conditions',
                    'Current Medications',
                    'Allergies',
                    'Last Checkup',
                    'Next Checkup',
                    'Emergency Contact',
                    'Notes'
                ]);
                
                fputcsv($file, $headers);
                
                // Add data rows
                foreach ($records as $record) {
                    $resident = $record->resident;
                    
                    $row = [
                        $resident->barangay_id ?? 'N/A',
                        ($resident->first_name ?? '') . ' ' . ($resident->last_name ?? ''),
                        $resident->age ?? 'N/A',
                        $resident->gender ?? 'N/A',
                        $record->blood_type ?? 'N/A',
                        $record->height ?? 'N/A',
                        $record->weight ?? 'N/A',
                        $record->bmi ?? 'N/A',
                        $record->blood_pressure ?? 'N/A',
                        $record->temperature ?? 'N/A',
                    ];
                    
                    if ($type == 'pregnant' || $type == 'all') {
                        $row = array_merge($row, [
                            $record->is_pregnant ? 'Yes' : 'No',
                            $record->pregnancy_weeks ?? 'N/A',
                            $record->trimester ?? 'N/A',
                            $record->expected_delivery_date ? $record->expected_delivery_date->format('Y-m-d') : 'N/A',
                            $record->next_prenatal_visit ? $record->next_prenatal_visit->format('Y-m-d') : 'N/A'
                        ]);
                    }
                    
                    if ($type == 'seniors' || $type == 'all') {
                        $row[] = $record->is_senior_citizen ? 'Yes' : 'No';
                    }
                    
                    if ($type == 'malnourished' || $type == 'all') {
                        $row = array_merge($row, [
                            $record->is_malnourished ? 'Yes' : 'No',
                            $record->malnutrition_type ?? 'N/A'
                        ]);
                    }
                    
                    $row = array_merge($row, [
                        $record->medical_conditions ?? 'N/A',
                        $record->current_medications ?? 'N/A',
                        $record->allergies ?? 'N/A',
                        $record->last_checkup_date ? $record->last_checkup_date->format('Y-m-d') : 'N/A',
                        $record->next_checkup_date ? $record->next_checkup_date->format('Y-m-d') : 'N/A',
                        $record->emergency_contact ?? 'N/A',
                        $record->notes ?? 'N/A'
                    ]);
                    
                    fputcsv($file, $row);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Health Record Export Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting health records.');
        }
    }
    
    /**
     * Calculate trimester based on pregnancy weeks
     */
    private function calculateTrimester($weeks)
    {
        if ($weeks <= 13) {
            return 1;
        } elseif ($weeks <= 26) {
            return 2;
        } else {
            return 3;
        }
    }
}
