<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resident;
use App\Models\Household;
use App\Models\FamilyMember;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    /**
     * Test household update functionality
     */
    public function testHouseholdUpdate(Request $request, $resident_id)
    {
        $resident = Resident::findOrFail($resident_id);
        
        // Log the test
        \Log::info('Test Household Update', [
            'resident' => $resident->id . ' - ' . $resident->full_name,
            'has_household' => $resident->household ? 'Yes (ID: '.$resident->household->id.')' : 'No'
        ]);

        // Create test household data
        $householdData = [
            'primary_name' => 'Test Primary ' . now()->format('H:i:s'),
            'primary_gender' => 'Male',
            'primary_birthday' => '1980-01-01',
            'primary_phone' => '09123456789',
            'primary_work' => 'Test Occupation',
            'primary_allergies' => 'Test Allergy',
            'primary_medical_condition' => 'Test Condition',
            
            'secondary_name' => 'Test Secondary ' . now()->format('H:i:s'),
            'secondary_gender' => 'Female',
            'secondary_birthday' => '1985-01-01',
            'secondary_phone' => '09123456780',
            'secondary_work' => 'Test Occupation 2',
            'secondary_allergies' => 'Test Allergy 2',
            'secondary_medical_condition' => 'Test Condition 2',
            
            'emergency_contact_name' => 'Test Emergency ' . now()->format('H:i:s'),
            'emergency_relationship' => 'Relative',
            'emergency_work' => 'Test Occupation 3',
            'emergency_phone' => '09123456781'
        ];
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            if ($resident->household) {
                \Log::info('Updating existing household', $householdData);
                $resident->household->update($householdData);
                $result = "Updated existing household (ID: {$resident->household->id})";
            } else {
                \Log::info('Creating new household', $householdData);
                $household = new Household($householdData);
                $household->resident_id = $resident->id;
                $household->save();
                $result = "Created new household (ID: {$household->id})";
            }
            
            DB::commit();
            
            // Reload the resident with updated household
            $resident->refresh();
            
            return response()->json([
                'success' => true, 
                'message' => $result,
                'resident' => $resident->id . ' - ' . $resident->full_name,
                'household' => $resident->household
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Test failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    /**
     * Display the database structure for household and family members
     */
    public function showDatabaseStructure()
    {
        try {
            // Get household table structure
            $householdColumns = DB::select('SHOW COLUMNS FROM households');
            
            // Get family_members table structure
            $familyMemberColumns = DB::select('SHOW COLUMNS FROM family_members');
            
            return response()->json([
                'household_table' => $householdColumns,
                'family_members_table' => $familyMemberColumns
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Get all the household data for a resident
     */
    public function getHouseholdData($resident_id)
    {
        $resident = Resident::with('household', 'familyMembers')->findOrFail($resident_id);
        
        return response()->json([
            'resident' => [
                'id' => $resident->id,
                'name' => $resident->full_name,
                'address' => $resident->address
            ],
            'household' => $resident->household,
            'family_members' => $resident->familyMembers
        ]);
    }
}
