<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\FamilyMember;
use App\Http\Controllers\DashboardController;

class PopulationController extends Controller
{
    /**
     * Show the population duplicates management page
     */
    public function duplicates()
    {
        $dashboardController = new DashboardController();
        $potentialDuplicates = $dashboardController->getPotentialDuplicates();
        
        return view('admin.population.duplicates', compact('potentialDuplicates'));
    }
    
    /**
     * Merge duplicate entries
     */
    public function mergeDuplicate(Request $request)
    {
        $request->validate([
            'type' => 'required|in:resident_family_member,duplicate_family_members',
            'action' => 'required|in:keep_resident,promote_family_member,merge_data',
            'primary_id' => 'required|integer',
        ]);
        
        try {
            if ($request->type === 'resident_family_member') {
                $this->handleResidentFamilyMemberDuplicate($request);
            } elseif ($request->type === 'duplicate_family_members') {
                $this->handleDuplicateFamilyMembers($request);
            }
            
            return back()->with('success', 'Duplicate entries have been successfully merged.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error merging duplicates: ' . $e->getMessage());
        }
    }
    
    /**
     * Remove duplicate entry
     */
    public function removeDuplicate($type, $id)
    {
        try {
            if ($type === 'family_member') {
                $familyMember = FamilyMember::findOrFail($id);
                $familyMember->delete();
            } elseif ($type === 'resident') {
                $resident = Resident::findOrFail($id);
                $resident->delete();
            }
            
            return back()->with('success', 'Duplicate entry has been removed.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error removing duplicate: ' . $e->getMessage());
        }
    }
    
    /**
     * Handle resident/family member duplicate merge
     */
    private function handleResidentFamilyMemberDuplicate(Request $request)
    {
        $residentId = $request->input('resident_id');
        $familyMemberId = $request->input('family_member_id');
        
        $resident = Resident::findOrFail($residentId);
        $familyMember = FamilyMember::findOrFail($familyMemberId);
        
        switch ($request->action) {
            case 'keep_resident':
                // Remove the family member entry
                $familyMember->delete();
                break;
                
            case 'promote_family_member':
                // This would require complex logic to convert family member to resident
                // For now, we'll just remove the family member duplicate
                $familyMember->delete();
                break;
                
            case 'merge_data':
                // Merge any missing data from family member to resident
                if (!$resident->contact_number && $familyMember->phone) {
                    $resident->contact_number = $familyMember->phone;
                }
                $resident->save();
                $familyMember->delete();
                break;
        }
    }
    
    /**
     * Handle duplicate family members merge
     */
    private function handleDuplicateFamilyMembers(Request $request)
    {
        $primaryId = $request->input('primary_id');
        $duplicateIds = $request->input('duplicate_ids', []);
        
        $primaryMember = FamilyMember::findOrFail($primaryId);
        
        // Remove duplicate family members
        FamilyMember::whereIn('id', $duplicateIds)->delete();
    }
}