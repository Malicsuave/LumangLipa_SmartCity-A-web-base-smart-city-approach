<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gad;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Requests\GadRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GadController extends Controller
{
    /**
     * Display a listing of GAD records.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Gad::with('resident');
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('resident', function ($residentQuery) use ($search) {
                    $residentQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('barangay_id', 'like', "%{$search}%");
                })->orWhere('gender_identity', 'like', "%{$search}%")
                  ->orWhereJsonContains('programs_enrolled', $search);
            });
        }
        
        // Program filter
        if ($request->has('program') && !empty($request->program)) {
            $query->whereJsonContains('programs_enrolled', $request->program);
        }
        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('program_status', $request->status);
        }
        // Gender Identity filter
        if ($request->has('gender_identity') && !empty($request->gender_identity)) {
            $query->where('gender_identity', $request->gender_identity);
        }
        // Civil Status filter (from resident)
        if ($request->has('civil_status') && !empty($request->civil_status)) {
            $query->whereHas('resident', function($q) use ($request) {
                $q->where('civil_status', $request->civil_status);
            });
        }
        // Age Group filter (from resident birthdate)
        if ($request->has('age_group') && !empty($request->age_group)) {
            $now = now();
            switch ($request->age_group) {
                case 'children':
                    $query->whereHas('resident', function($q) use ($now) {
                        $q->whereDate('birthdate', '>=', $now->copy()->subYears(18));
                    });
                    break;
                case 'adults':
                    $query->whereHas('resident', function($q) use ($now) {
                        $q->whereDate('birthdate', '<', $now->copy()->subYears(18))
                          ->whereDate('birthdate', '>', $now->copy()->subYears(60));
                    });
                    break;
                case 'seniors':
                    $query->whereHas('resident', function($q) use ($now) {
                        $q->whereDate('birthdate', '<=', $now->copy()->subYears(60));
                    });
                    break;
            }
        }
        // Date filter (created_at)
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        // Special categories
        if ($request->has('is_pregnant') && $request->is_pregnant == '1') {
            $query->where('is_pregnant', true);
        }
        if ($request->has('is_solo_parent') && $request->is_solo_parent == '1') {
            $query->where('is_solo_parent', true);
        }
        if ($request->has('is_vaw_case') && $request->is_vaw_case == '1') {
            $query->where('is_vaw_case', true);
        }
        // Sorting functionality
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }
        switch ($sort) {
            case 'resident_name':
                $query->join('residents', 'gads.resident_id', '=', 'residents.id')
                      ->orderBy('residents.last_name', $direction)
                      ->orderBy('residents.first_name', $direction)
                      ->select('gads.*');
                break;
            case 'gender_identity':
                $query->orderBy('gender_identity', $direction);
                break;
            case 'program_status':
                $query->orderBy('program_status', $direction);
                break;
            case 'created_at':
                $query->orderBy('created_at', $direction);
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        $gadRecords = $query->paginate(15);
        $gadRecords->appends($request->query());
        $programTypes = Gad::getProgramTypes();
        $stats = [
            'total' => Gad::count(),
            'pregnant' => Gad::where('is_pregnant', true)->count(),
            'solo_parents' => Gad::where('is_solo_parent', true)->count(),
            'vaw_cases' => Gad::where('is_vaw_case', true)->count(),
            'by_gender' => DB::table('gads')
                ->select('gender_identity', DB::raw('count(*) as count'))
                ->groupBy('gender_identity')
                ->get()
                ->pluck('count', 'gender_identity')
                ->toArray()
        ];
        return view('admin.gad.index', [
            'gadRecords' => $gadRecords,
            'programTypes' => $programTypes,
            'stats' => $stats,
            'filters' => $request->only(['program', 'status', 'gender_identity', 'civil_status', 'age_group', 'date_from', 'date_to', 'is_pregnant', 'is_solo_parent', 'is_vaw_case'])
        ]);
    }

    /**
     * Show the form for creating a new GAD record.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get residents without GAD records, prioritizing females
        $residents = Resident::whereDoesntHave('gad')
            ->orderByRaw("sex = 'Female' DESC") // Put females first
            ->get()
            ->pluck('full_name', 'id');
            
        $programTypes = Gad::getProgramTypes();
        
        return view('admin.gad.create', compact('residents', 'programTypes'));
    }

    /**
     * Store a newly created GAD record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Log the incoming request data for debugging
            Log::debug('GAD Store - Request received', [
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);
            
            // Custom validation for new GAD records
            $validator = \Validator::make($request->all(), [
                // Resident validation
                'resident_id' => 'required|exists:residents,id',
                
                // Basic information
                'gender_identity' => 'required|string|in:Male,Female,Non-binary,Transgender,Other',
                'gender_details' => [
                    'nullable',
                    'string',
                    'max:100',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->gender_identity === 'Other' && empty($value)) {
                            $fail('Gender details are required when selecting "Other" as gender identity.');
                        }
                    },
                ],
                'email' => [
                    'nullable', 
                    'email:rfc,dns'
                ],
                'phone_number' => [
                    'nullable',
                    'string',
                ],
                
                // Program information
                'programs_enrolled' => 'nullable|array',
                'enrollment_date' => [
                    'nullable',
                    'date',
                    'before_or_equal:today',
                ],
                'program_end_date' => [
                    'nullable',
                    'date',
                    function ($attribute, $value, $fail) use ($request) {
                        if (!empty($value) && !empty($request->enrollment_date) && $value < $request->enrollment_date) {
                            $fail('Program end date must be after enrollment date.');
                        }
                    },
                ],
                'program_status' => 'nullable|string|in:Active,Completed,On Hold,Discontinued',
                
                // Pregnancy information - only validate if is_pregnant is checked
                'due_date' => [
                    'nullable',
                    'date',
                    'after:today',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->has('is_pregnant') && empty($value)) {
                            $fail('Due date is required when pregnancy is indicated.');
                        }
                        
                        // Additional check to ensure the date is in the future
                        if (!empty($value) && strtotime($value) <= strtotime('today')) {
                            $fail('Due date must be in the future.');
                        }
                    },
                ],
                
                // Solo parent information - only validate if is_solo_parent is checked
                'solo_parent_id' => [
                    'nullable',
                    'string',
                    'max:50',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->has('is_solo_parent') && empty($value)) {
                            $fail('Solo Parent ID is required when solo parent status is indicated.');
                        }
                    },
                ],
                'solo_parent_id_issued' => [
                    'nullable',
                    'date',
                    'before_or_equal:today',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->has('is_solo_parent') && empty($value)) {
                            $fail('ID issuance date is required for solo parents.');
                        }
                    },
                ],
                'solo_parent_id_expiry' => [
                    'nullable',
                    'date',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->has('is_solo_parent')) {
                            if (empty($value)) {
                                $fail('ID expiry date is required for solo parents.');
                            } elseif (!empty($request->solo_parent_id_issued) && $value <= $request->solo_parent_id_issued) {
                                $fail('ID expiry date must be after the issuance date.');
                            }
                        }
                    },
                ],
                
                // VAW case information - only validate if is_vaw_case is checked
                'vaw_report_date' => [
                    'nullable',
                    'date',
                    'before_or_equal:today',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->has('is_vaw_case') && empty($value)) {
                            $fail('Report date is required for VAW cases.');
                        }
                    },
                ],
                'vaw_case_number' => [
                    'nullable',
                    'string',
                    'max:50',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->has('is_vaw_case') && empty($value)) {
                            $fail('Case number is required for VAW cases.');
                        }
                    },
                ],
                'vaw_case_status' => [
                    'nullable',
                    'string',
                    'in:Pending,Ongoing,Resolved,Closed',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->has('is_vaw_case') && empty($value)) {
                            $fail('Case status is required for VAW cases.');
                        }
                    },
                ],
                'vaw_case_details' => [
                    'nullable',
                    'string',
                    'max:1000',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->has('is_vaw_case')) {
                            if (empty($value)) {
                                $fail('Case details are required for VAW cases.');
                            } elseif (strlen($value) < 10) {
                                $fail('Case details should contain at least 10 characters.');
                            }
                        }
                    },
                ],
                
                // Additional notes
                'notes' => 'nullable|string|max:500',
            ]);
            
            // If validation fails, redirect back with errors
            if ($validator->fails()) {
                Log::debug('GAD Store - Validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            // Get validated data
            $data = $validator->validated();
            
            // Explicitly handle boolean fields (checkboxes)
            $booleanFields = ['is_pregnant', 'is_lactating', 'needs_maternity_support', 
                             'is_solo_parent', 'is_vaw_case', 'has_philhealth'];
            
            foreach ($booleanFields as $field) {
                $data[$field] = $request->has($field) ? true : false;
            }
            
            // Handle optional fields for sections that are not active
            if (!$request->has('is_pregnant')) {
                $data['due_date'] = null;
                $data['is_lactating'] = false;
                $data['needs_maternity_support'] = false;
            }
            
            if (!$request->has('is_solo_parent')) {
                $data['solo_parent_id'] = null;
                $data['solo_parent_id_issued'] = null;
                $data['solo_parent_id_expiry'] = null;
                $data['solo_parent_details'] = null;
            }
            
            if (!$request->has('is_vaw_case')) {
                $data['vaw_case_number'] = null;
                $data['vaw_report_date'] = null;
                $data['vaw_case_status'] = null;
                $data['vaw_case_details'] = null;
            }
            
            // Ensure programs_enrolled is an array
            if (!isset($data['programs_enrolled']) || !is_array($data['programs_enrolled'])) {
                $data['programs_enrolled'] = [];
            }
            
            // Use DB transaction to ensure atomicity
            DB::beginTransaction();
            
            // Create GAD record
            $gad = Gad::create($data);
            
            // Update resident's population sectors
            $resident = Resident::find($data['resident_id']);
            $sectors = $resident->population_sectors ?? [];
            
            if ($data['is_solo_parent'] && !in_array('Solo Parent', $sectors)) {
                $sectors[] = 'Solo Parent';
            }
            
            // Save updated population sectors
            $resident->population_sectors = array_unique($sectors);
            $resident->save();
            
            // Commit transaction
            DB::commit();
            
            // Log success
            Log::info('GAD record created successfully', [
                'gad_id' => $gad->id,
                'resident_id' => $gad->resident_id,
                'user_id' => auth()->id()
            ]);
            
            // Redirect to show page with success message
            return redirect()->route('admin.gad.show', $gad->id)
                ->with('success', 'GAD record created successfully!');
                
        } catch (\Exception $e) {
            // Roll back transaction in case of error
            DB::rollBack();
            
            // Log the error
            Log::error('Error creating GAD record', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return with error message
            return redirect()->back()
                ->with('error', 'Error creating GAD record: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified GAD record.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $gad = Gad::with('resident')->findOrFail($id);
        return view('admin.gad.show', compact('gad'));
    }

    /**
     * Show the form for editing the specified GAD record.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $gad = Gad::with('resident')->findOrFail($id);
        $programTypes = Gad::getProgramTypes();
        
        return view('admin.gad.edit', compact('gad', 'programTypes'));
    }

    /**
     * Update the specified GAD record.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Log the incoming request data for debugging
            Log::debug('GAD Update - Request received', [
                'gad_id' => $id,
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);
            
            $gad = Gad::findOrFail($id);
            
            // Custom validation that allows numbers in fields
            $validator = \Validator::make($request->all(), [
                // Basic information
                'gender_identity' => 'required|string|in:Male,Female,Non-binary,Transgender,Other',
                'gender_details' => [
                    'nullable',
                    'string',
                    'max:100',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->gender_identity === 'Other' && empty($value)) {
                            $fail('Gender details are required when selecting "Other" as gender identity.');
                        }
                    },
                ],
                
                // Program information
                'programs_enrolled' => 'nullable|array',
                'enrollment_date' => 'nullable|date',
                'program_end_date' => 'nullable|date',
                'program_status' => 'nullable|string|in:Active,Completed,On Hold,Discontinued',
                
                // Pregnancy information - only validate if is_pregnant is checked
                'due_date' => [
                    'nullable',
                    'date',
                    'after:today', // Changed from 'after_or_equal' to 'after' to ensure future date only
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->has('is_pregnant') && empty($value)) {
                            $fail('Due date is required when pregnancy is indicated.');
                        }
                        
                        // Additional check to ensure the date is in the future
                        if (!empty($value) && strtotime($value) <= strtotime('today')) {
                            $fail('Due date must be in the future.');
                        }
                    },
                ],
                
                // Solo parent information - only validate if is_solo_parent is checked
                'solo_parent_id' => [
                    'nullable',
                    'string',
                    'max:50',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->has('is_solo_parent') && empty($value)) {
                            $fail('Solo Parent ID is required when solo parent status is indicated.');
                        }
                    },
                ],
                'solo_parent_id_issued' => [
                    'nullable',
                    'date',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->has('is_solo_parent') && empty($value)) {
                            $fail('ID issuance date is required for solo parents.');
                        }
                    },
                ],
                'solo_parent_id_expiry' => [
                    'nullable',
                    'date',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->has('is_solo_parent')) {
                            if (empty($value)) {
                                $fail('ID expiry date is required for solo parents.');
                            } elseif (!empty($request->solo_parent_id_issued) && $value <= $request->solo_parent_id_issued) {
                                $fail('ID expiry date must be after the issuance date.');
                            }
                        }
                    },
                ],
                
                // VAW case information - only validate if is_vaw_case is checked
                'vaw_report_date' => [
                    'nullable',
                    'date',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->has('is_vaw_case') && empty($value)) {
                            $fail('Report date is required for VAW cases.');
                        }
                    },
                ],
                'vaw_case_number' => [
                    'nullable',
                    'string',
                    'max:50',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->has('is_vaw_case') && empty($value)) {
                            $fail('Case number is required for VAW cases.');
                        }
                    },
                ],
                'vaw_case_status' => [
                    'nullable',
                    'string',
                    'in:Pending,Ongoing,Resolved,Closed',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->has('is_vaw_case') && empty($value)) {
                            $fail('Case status is required for VAW cases.');
                        }
                    },
                ],
                'vaw_case_details' => [
                    'nullable',
                    'string',
                    'max:1000',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($request->has('is_vaw_case')) {
                            if (empty($value)) {
                                $fail('Case details are required for VAW cases.');
                            } elseif (strlen($value) < 10) {
                                $fail('Case details should contain at least 10 characters.');
                            }
                        }
                    },
                ],
                
                // Additional notes
                'notes' => 'nullable|string|max:500',
            ]);
            
            // If validation fails, redirect back with errors
            if ($validator->fails()) {
                Log::debug('GAD Update - Validation failed', [
                    'errors' => $validator->errors()->toArray()
                ]);
                
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            // Get validated data
            $data = $validator->validated();
            
            // Explicitly handle boolean fields (checkboxes)
            $booleanFields = ['is_pregnant', 'is_lactating', 'needs_maternity_support', 
                             'is_solo_parent', 'is_vaw_case', 'has_philhealth'];
            
            foreach ($booleanFields as $field) {
                $data[$field] = $request->has($field) ? true : false;
            }
            
            // Handle optional fields for sections that are not active
            if (!$request->has('is_pregnant')) {
                $data['due_date'] = null;
                $data['is_lactating'] = false;
                $data['needs_maternity_support'] = false;
            }
            
            if (!$request->has('is_solo_parent')) {
                $data['solo_parent_id'] = null;
                $data['solo_parent_id_issued'] = null;
                $data['solo_parent_id_expiry'] = null;
                $data['solo_parent_details'] = null;
            }
            
            if (!$request->has('is_vaw_case')) {
                $data['vaw_case_number'] = null;
                $data['vaw_report_date'] = null;
                $data['vaw_case_status'] = null;
                $data['vaw_case_details'] = null;
            }
            
            // Ensure programs_enrolled is an array
            if (!isset($data['programs_enrolled']) || !is_array($data['programs_enrolled'])) {
                $data['programs_enrolled'] = [];
            }
            
            // Use DB transaction to ensure atomicity
            DB::beginTransaction();
            
            // Log before update
            Log::debug('GAD Update - Before update', [
                'current_data' => $gad->toArray(),
                'new_data' => $data
            ]);
            
            // Update GAD record with prepared data - use fill and save instead of update()
            $gad->fill($data);
            $saveResult = $gad->save();
            
            // Log after update
            Log::debug('GAD Update - After save', [
                'save_result' => $saveResult,
                'updated_gad' => $gad->fresh()->toArray()
            ]);

            // Update resident's population sectors
            $resident = $gad->resident;
            $sectors = $resident->population_sectors ?? [];

            if ($data['is_solo_parent'] && !in_array('Solo Parent', $sectors)) {
                $sectors[] = 'Solo Parent';
            } elseif (!$data['is_solo_parent'] && in_array('Solo Parent', $sectors)) {
                $sectors = array_diff($sectors, ['Solo Parent']);
            }

            $resident->population_sectors = array_unique($sectors);
            $resident->save();
            
            // Commit transaction
            DB::commit();
            
            // Log success
            Log::info('GAD record updated successfully', [
                'gad_id' => $gad->id,
                'resident_id' => $gad->resident_id,
                'user_id' => auth()->id()
            ]);
            
            // Redirect to show page with success message
            return redirect()->route('admin.gad.show', $gad->id)
                ->with('success', 'GAD record updated successfully!');
            
        } catch (\Exception $e) {
            // Roll back transaction in case of error
            DB::rollBack();
            
            // Log the error
            Log::error('Error updating GAD record', [
                'gad_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return with error message
            return redirect()->back()
                ->with('error', 'Error updating GAD record: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Direct update for the GAD record - bypasses complex validation for troubleshooting.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function directUpdate(Request $request, $id)
    {
        try {
            // Get the GAD record
            $gad = Gad::findOrFail($id);
            
            // Get all input data
            $data = $request->all();
            
            // Handle boolean fields which come as checkbox values
            $booleanFields = ['is_pregnant', 'is_lactating', 'needs_maternity_support', 
                             'is_solo_parent', 'is_vaw_case', 'has_philhealth'];
            
            foreach ($booleanFields as $field) {
                // Set boolean fields - if not present in request, it means unchecked (false)
                $data[$field] = $request->has($field) ? true : false;
            }
            
            // Clear fields that should be null when sections are toggled off
            if (!$data['is_pregnant']) {
                $data['due_date'] = null;
            }
            
            if (!$data['is_solo_parent']) {
                $data['solo_parent_id'] = null;
                $data['solo_parent_id_issued'] = null;
                $data['solo_parent_id_expiry'] = null;
                $data['solo_parent_details'] = null;
            }
            
            if (!$data['is_vaw_case']) {
                $data['vaw_case_number'] = null;
                $data['vaw_report_date'] = null;
                $data['vaw_case_status'] = null;
                $data['vaw_case_details'] = null;
            }
            
            // Handle programs enrolled array
            if (!isset($data['programs_enrolled'])) {
                $data['programs_enrolled'] = [];
            }
            
            // Log the data being saved
            \Log::info('GAD Direct Update - Data being saved', [
                'gad_id' => $id,
                'data' => $data
            ]);
            
            // Direct update of GAD record
            $result = $gad->update($data);
            
            // Log the result
            \Log::info('GAD Direct Update - Result', [
                'gad_id' => $id,
                'result' => $result,
                'updated_gad' => $gad->fresh()->toArray()
            ]);
            
            // Return with simple success message
            return redirect()->route('admin.gad.show', $gad->id)
                ->with('success', 'GAD record updated successfully!');
        
        } catch (\Exception $e) {
            // Log any errors
            \Log::error('GAD Direct Update - Error', [
                'gad_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return with error message
            return redirect()->back()
                ->with('error', 'Error updating GAD record: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Archive the specified GAD record instead of permanently deleting.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $gad = Gad::findOrFail($id);
        
        try {
            // Archive (soft delete) the GAD record
            $gad->delete();
            
            return redirect()->route('admin.gad.index')
                ->with('success', 'GAD record archived successfully! You can restore it from the archive.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error archiving GAD record: ' . $e->getMessage());
        }
    }

    /**
     * Display archived GAD records.
     */
    public function archived(Request $request)
    {
        $query = Gad::onlyTrashed()->with('resident');
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('resident', function ($residentQuery) use ($search) {
                    $residentQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('middle_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('barangay_id', 'like', "%{$search}%");
                })->orWhere('gender_identity', 'like', "%{$search}%")
                  ->orWhereJsonContains('programs_enrolled', $search);
            });
        }
        // Program filter
        if ($request->has('program') && !empty($request->program)) {
            $query->whereJsonContains('programs_enrolled', $request->program);
        }
        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('program_status', $request->status);
        }
        // Gender Identity filter
        if ($request->has('gender_identity') && !empty($request->gender_identity)) {
            $query->where('gender_identity', $request->gender_identity);
        }
        // Civil Status filter (from resident)
        if ($request->has('civil_status') && !empty($request->civil_status)) {
            $query->whereHas('resident', function($q) use ($request) {
                $q->where('civil_status', $request->civil_status);
            });
        }
        // Age Group filter (from resident birthdate)
        if ($request->has('age_group') && !empty($request->age_group)) {
            $now = now();
            switch ($request->age_group) {
                case 'children':
                    $query->whereHas('resident', function($q) use ($now) {
                        $q->whereDate('birthdate', '>=', $now->copy()->subYears(18));
                    });
                    break;
                case 'adults':
                    $query->whereHas('resident', function($q) use ($now) {
                        $q->whereDate('birthdate', '<', $now->copy()->subYears(18))
                          ->whereDate('birthdate', '>', $now->copy()->subYears(60));
                    });
                    break;
                case 'seniors':
                    $query->whereHas('resident', function($q) use ($now) {
                        $q->whereDate('birthdate', '<=', $now->copy()->subYears(60));
                    });
                    break;
            }
        }
        // Date filter (deleted_at)
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('deleted_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('deleted_at', '<=', $request->date_to);
        }
        // Special categories
        if ($request->has('is_pregnant') && $request->is_pregnant == '1') {
            $query->where('is_pregnant', true);
        }
        if ($request->has('is_solo_parent') && $request->is_solo_parent == '1') {
            $query->where('is_solo_parent', true);
        }
        if ($request->has('is_vaw_case') && $request->is_vaw_case == '1') {
            $query->where('is_vaw_case', true);
        }
        $archivedGadRecords = $query->orderBy('deleted_at', 'desc')->paginate(15);
        $archivedGadRecords->appends($request->query());
        $programTypes = Gad::getProgramTypes();
        return view('admin.gad.archived', [
            'archivedGadRecords' => $archivedGadRecords,
            'programTypes' => $programTypes,
            'filters' => $request->only(['program', 'status', 'gender_identity', 'civil_status', 'age_group', 'date_from', 'date_to', 'is_pregnant', 'is_solo_parent', 'is_vaw_case'])
        ]);
    }

    /**
     * Restore an archived GAD record.
     */
    public function restore($id)
    {
        try {
            $gad = Gad::onlyTrashed()->findOrFail($id);
            $gad->restore();
            
            return redirect()->route('admin.gad.archived')
                ->with('success', 'GAD record restored successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error restoring GAD record: ' . $e->getMessage());
        }
    }

    /**
     * Permanently delete an archived GAD record.
     */
    public function forceDelete($id)
    {
        try {
            $gad = Gad::onlyTrashed()->findOrFail($id);
            
            // Keep a reference to resident before permanently deleting GAD record
            $resident = $gad->resident;
            
            // Permanently delete the GAD record
            $gad->forceDelete();
            
            // Update resident's population sectors if needed
            if ($resident) {
                $sectors = $resident->population_sectors ?? [];
                
                // Remove Solo Parent tag if it was added due to GAD record
                if (in_array('Solo Parent', $sectors) && $gad->is_solo_parent) {
                    $sectors = array_diff($sectors, ['Solo Parent']);
                    $resident->population_sectors = $sectors;
                    $resident->save();
                }
            }
            
            return redirect()->route('admin.gad.archived')
                ->with('success', 'GAD record permanently deleted!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error permanently deleting GAD record: ' . $e->getMessage());
        }
    }

    /**
     * Generate a report of GAD statistics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function reports(Request $request)
    {
        // Date range filters
        $startDate = $request->input('start_date') ? date('Y-m-d', strtotime($request->input('start_date'))) : null;
        $endDate = $request->input('end_date') ? date('Y-m-d', strtotime($request->input('end_date'))) : null;

        // Base query
        $query = Gad::with('resident');

        // Apply date filters if provided
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // GAD records
        $gadRecords = $query->get();

        // Generate statistics
        $stats = [
            'total' => $gadRecords->count(),
            'by_gender_identity' => $gadRecords->groupBy('gender_identity')
                ->map(function ($group) {
                    return $group->count();
                }),
            'pregnant_women' => $gadRecords->where('is_pregnant', true)->count(),
            'solo_parents' => $gadRecords->where('is_solo_parent', true)->count(),
            'vaw_cases' => $gadRecords->where('is_vaw_case', true)->count(),
            'program_participation' => [],
        ];

        // Calculate program participation statistics
        foreach ($gadRecords as $gad) {
            if ($gad->programs_enrolled) {
                foreach ($gad->programs_enrolled as $program) {
                    if (!array_key_exists($program, $stats['program_participation'])) {
                        $stats['program_participation'][$program] = 0;
                    }
                    $stats['program_participation'][$program]++;
                }
            }
        }

        // Check if export was requested
        if ($request->has('export') && $request->export === 'pdf') {
            $pdf = Pdf::loadView('admin.gad.reports-pdf', [
                'stats' => $stats,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);

            return $pdf->download('gad-report-' . date('Y-m-d') . '.pdf');
        }

        return view('admin.gad.reports', [
            'stats' => $stats,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
