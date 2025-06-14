<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gad;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        
        // Apply filters if provided
        if ($request->has('program') && !empty($request->program)) {
            $query->whereJsonContains('programs_enrolled', $request->program);
        }
        
        if ($request->has('status') && !empty($request->status)) {
            $query->where('program_status', $request->status);
        }
        
        if ($request->has('is_pregnant') && $request->is_pregnant == '1') {
            $query->where('is_pregnant', true);
        }
        
        if ($request->has('is_solo_parent') && $request->is_solo_parent == '1') {
            $query->where('is_solo_parent', true);
        }
        
        if ($request->has('is_vaw_case') && $request->is_vaw_case == '1') {
            $query->where('is_vaw_case', true);
        }
        
        // Fetch GAD records with pagination
        $gadRecords = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get program types for filter dropdown
        $programTypes = Gad::getProgramTypes();
        
        // Get statistics for dashboard
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
            'filters' => $request->only(['program', 'status', 'is_pregnant', 'is_solo_parent', 'is_vaw_case'])
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
        $request->validate([
            'resident_id' => 'required|exists:residents,id',
            'gender_identity' => 'required|string',
            'programs_enrolled' => 'nullable|array',
            'enrollment_date' => 'nullable|date',
            'program_end_date' => 'nullable|date|after_or_equal:enrollment_date',
            'program_status' => 'nullable|string',
            'is_pregnant' => 'nullable|boolean',
            'due_date' => 'nullable|date|required_if:is_pregnant,1',
            'is_lactating' => 'nullable|boolean',
            'needs_maternity_support' => 'nullable|boolean',
            'is_solo_parent' => 'nullable|boolean',
            'solo_parent_id' => 'nullable|string|required_if:is_solo_parent,1',
            'solo_parent_id_issued' => 'nullable|date|required_if:is_solo_parent,1',
            'solo_parent_id_expiry' => 'nullable|date|after:solo_parent_id_issued|required_if:is_solo_parent,1',
            'is_vaw_case' => 'nullable|boolean',
            'vaw_report_date' => 'nullable|date|required_if:is_vaw_case,1',
            'vaw_case_number' => 'nullable|string|required_if:is_vaw_case,1',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Create GAD record
        $gad = Gad::create($request->all());
        
        // Update resident's population sectors to include relevant tags
        $resident = Resident::find($request->resident_id);
        $sectors = $resident->population_sectors ?? [];
        
        if ($request->is_solo_parent && !in_array('Solo Parent', $sectors)) {
            $sectors[] = 'Solo Parent';
        }
        
        // Save updated population sectors
        $resident->population_sectors = array_unique($sectors);
        $resident->save();
        
        return redirect()->route('admin.gad.show', $gad->id)
            ->with('success', 'GAD record created successfully!');
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
        $request->validate([
            'gender_identity' => 'required|string',
            'programs_enrolled' => 'nullable|array',
            'enrollment_date' => 'nullable|date',
            'program_end_date' => 'nullable|date|after_or_equal:enrollment_date',
            'program_status' => 'nullable|string',
            'is_pregnant' => 'nullable|boolean',
            'due_date' => 'nullable|date|required_if:is_pregnant,1',
            'is_lactating' => 'nullable|boolean',
            'needs_maternity_support' => 'nullable|boolean',
            'is_solo_parent' => 'nullable|boolean',
            'solo_parent_id' => 'nullable|string|required_if:is_solo_parent,1',
            'solo_parent_id_issued' => 'nullable|date|required_if:is_solo_parent,1',
            'solo_parent_id_expiry' => 'nullable|date|after:solo_parent_id_issued|required_if:is_solo_parent,1',
            'is_vaw_case' => 'nullable|boolean',
            'vaw_report_date' => 'nullable|date|required_if:is_vaw_case,1',
            'vaw_case_number' => 'nullable|string|required_if:is_vaw_case,1',
            'notes' => 'nullable|string|max:500',
        ]);
        
        $gad = Gad::findOrFail($id);
        $gad->update($request->all());
        
        // Update resident's population sectors
        $resident = $gad->resident;
        $sectors = $resident->population_sectors ?? [];
        
        if ($request->is_solo_parent && !in_array('Solo Parent', $sectors)) {
            $sectors[] = 'Solo Parent';
        } elseif (!$request->is_solo_parent && in_array('Solo Parent', $sectors)) {
            $sectors = array_diff($sectors, ['Solo Parent']);
        }
        
        $resident->population_sectors = array_unique($sectors);
        $resident->save();
        
        return redirect()->route('admin.gad.show', $gad->id)
            ->with('success', 'GAD record updated successfully!');
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
        
        // Apply filters if provided
        if ($request->has('program') && !empty($request->program)) {
            $query->whereJsonContains('programs_enrolled', $request->program);
        }
        
        if ($request->has('status') && !empty($request->status)) {
            $query->where('program_status', $request->status);
        }
        
        if ($request->has('is_pregnant') && $request->is_pregnant == '1') {
            $query->where('is_pregnant', true);
        }
        
        if ($request->has('is_solo_parent') && $request->is_solo_parent == '1') {
            $query->where('is_solo_parent', true);
        }
        
        if ($request->has('is_vaw_case') && $request->is_vaw_case == '1') {
            $query->where('is_vaw_case', true);
        }
        
        // Fetch archived GAD records with pagination
        $archivedGadRecords = $query->orderBy('deleted_at', 'desc')->paginate(15);
        
        // Get program types for filter dropdown
        $programTypes = Gad::getProgramTypes();
        
        return view('admin.gad.archived', [
            'archivedGadRecords' => $archivedGadRecords,
            'programTypes' => $programTypes,
            'filters' => $request->only(['program', 'status', 'is_pregnant', 'is_solo_parent', 'is_vaw_case'])
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
            'program_participation' => collect(),
        ];
        
        // Calculate program participation statistics
        foreach ($gadRecords as $gad) {
            if ($gad->programs_enrolled) {
                foreach ($gad->programs_enrolled as $program) {
                    if (!$stats['program_participation']->has($program)) {
                        $stats['program_participation'][$program] = 0;
                    }
                    $stats['program_participation'][$program]++;
                }
            }
        }
        
        // Check if export was requested
        if ($request->has('export') && $request->export == 'pdf') {
            // Export to PDF
            $pdf = \PDF::loadView('admin.gad.reports-pdf', [
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
