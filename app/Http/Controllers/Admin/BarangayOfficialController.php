<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Official;
use Illuminate\Support\Facades\Storage;

class BarangayOfficialController extends Controller
{
    public function edit()
    {
        // Get all officials and organize them for the form
        $officials = Official::all();

        // Create a simple object to hold the officials data like the old structure
        $officialsData = new \stdClass();

        // Initialize all expected properties to avoid undefined property errors
        $officialsData->captain_name = '';
        $officialsData->captain_photo = '';
        $officialsData->secretary_name = '';
        $officialsData->secretary_photo = '';
        $officialsData->treasurer_name = '';
        $officialsData->treasurer_photo = '';
        $officialsData->sk_chairperson_name = '';
        $officialsData->sk_chairperson_photo = '';
        for ($i = 1; $i <= 7; $i++) {
            $officialsData->{"councilor{$i}_name"} = '';
            $officialsData->{"councilor{$i}_photo"} = '';
        }

        // Map officials to old structure for the view
        foreach ($officials as $official) {
            switch ($official->position) {
                case 'Captain':
                    $officialsData->captain_name = $official->name;
                    $officialsData->captain_photo = $official->profile_pic;
                    break;
                case 'Secretary':
                    $officialsData->secretary_name = $official->name;
                    $officialsData->secretary_photo = $official->profile_pic;
                    break;
                case 'Treasurer':
                    $officialsData->treasurer_name = $official->name;
                    $officialsData->treasurer_photo = $official->profile_pic;
                    break;
                case 'SK Chairman':
                    $officialsData->sk_chairperson_name = $official->name;
                    $officialsData->sk_chairperson_photo = $official->profile_pic;
                    break;
                case 'Councilor':
                    // Find the next available councilor slot
                    for ($i = 1; $i <= 7; $i++) {
                        if (empty($officialsData->{"councilor{$i}_name"})) {
                            $officialsData->{"councilor{$i}_name"} = $official->name;
                            $officialsData->{"councilor{$i}_photo"} = $official->profile_pic;
                            break;
                        }
                    }
                    break;
            }
        }

        return view('admin.officials.edit-single', ['officials' => $officialsData]);
    }

    public function update(Request $request)
    {
        // Validation rules for names and photos
        $validated = $request->validate([
            'captain_name' => 'nullable|string',
            'secretary_name' => 'nullable|string',
            'sk_chairperson_name' => 'nullable|string',
            'treasurer_name' => 'nullable|string',
            'councilor1_name' => 'nullable|string',
            'councilor2_name' => 'nullable|string',
            'councilor3_name' => 'nullable|string',
            'councilor4_name' => 'nullable|string',
            'councilor5_name' => 'nullable|string',
            'councilor6_name' => 'nullable|string',
            'councilor7_name' => 'nullable|string',
            'captain_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'secretary_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sk_chairperson_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'treasurer_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'councilor1_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'councilor2_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'councilor3_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'councilor4_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'councilor5_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'councilor6_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'councilor7_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Define position mappings
        $positionMap = [
            'captain' => 'Captain',
            'secretary' => 'Secretary',
            'treasurer' => 'Treasurer',
            'sk_chairperson' => 'SK Chairman'
        ];

        // Process each position
        foreach ($positionMap as $key => $position) {
            $nameField = "{$key}_name";
            $photoField = "{$key}_photo";
            
            if (!empty($request->$nameField)) {
                // Find existing official or create new one
                $official = Official::where('position', $position)->first();
                
                if (!$official) {
                    $official = new Official();
                    $official->position = $position;
                }
                
                $official->name = $request->$nameField;
                
                // Handle photo upload
                if ($request->hasFile($photoField)) {
                    // Delete old photo if exists
                    if ($official->profile_pic) {
                        Storage::disk('public')->delete('officials/' . $official->profile_pic);
                    }
                    
                    $file = $request->file($photoField);
                    $extension = $file->getClientOriginalExtension();
                    $filename = strtolower($key) . '_' . time() . '.' . $extension;
                    $path = $file->storeAs('officials', $filename, 'public');
                    $official->profile_pic = $filename;
                }
                
                $official->save();
            } else {
                // If name is empty, delete the official if exists
                $existingOfficial = Official::where('position', $position)->first();
                if ($existingOfficial) {
                    // Delete photo if exists
                    if ($existingOfficial->profile_pic) {
                        Storage::disk('public')->delete('officials/' . $existingOfficial->profile_pic);
                    }
                    $existingOfficial->delete();
                }
            }
        }

        // Process councilors - more sophisticated approach
        $existingCouncilors = Official::where('position', 'Councilor')->get()->keyBy('name');
        $newCouncilorNames = [];
        
        // Collect new councilor names from form
        for ($i = 1; $i <= 7; $i++) {
            $nameField = "councilor{$i}_name";
            if (!empty($request->$nameField)) {
                $newCouncilorNames[$i] = $request->$nameField;
            }
        }
        
        // Delete councilors that are no longer in the form
        foreach ($existingCouncilors as $existingCouncilor) {
            if (!in_array($existingCouncilor->name, $newCouncilorNames)) {
                if ($existingCouncilor->profile_pic) {
                    Storage::disk('public')->delete('officials/' . $existingCouncilor->profile_pic);
                }
                $existingCouncilor->delete();
            }
        }
        
        // Process each councilor position
        for ($i = 1; $i <= 7; $i++) {
            $nameField = "councilor{$i}_name";
            $photoField = "councilor{$i}_photo";
            
            if (!empty($request->$nameField)) {
                // Find existing councilor with same name or create new
                $official = $existingCouncilors->get($request->$nameField);
                
                if (!$official) {
                    $official = new Official();
                    $official->position = 'Councilor';
                }
                
                $official->name = $request->$nameField;
                
                // Handle photo upload - only update if new photo is uploaded
                if ($request->hasFile($photoField)) {
                    // Delete old photo if exists
                    if ($official->profile_pic) {
                        Storage::disk('public')->delete('officials/' . $official->profile_pic);
                    }
                    
                    $file = $request->file($photoField);
                    $extension = $file->getClientOriginalExtension();
                    $filename = "councilor{$i}_" . time() . '.' . $extension;
                    $path = $file->storeAs('officials', $filename, 'public');
                    $official->profile_pic = $filename;
                }
                
                $official->save();
            }
        }

        return redirect()->route('admin.officials.edit-single')->with('success', 'Officials information updated successfully.');
    }

    /**
     * Delete an official's photo
     */
    public function deletePhoto(Request $request, $field)
    {
        // Parse field to determine position
        $positionMap = [
            'captain_photo' => 'Captain',
            'secretary_photo' => 'Secretary',
            'treasurer_photo' => 'Treasurer',
            'sk_chairperson_photo' => 'SK Chairman'
        ];

        $position = null;
        $isCouncilor = false;
        
        if (isset($positionMap[$field])) {
            $position = $positionMap[$field];
        } elseif (preg_match('/councilor\d+_photo/', $field)) {
            $position = 'Councilor';
            $isCouncilor = true;
        }
        
        if ($position) {
            if ($isCouncilor) {
                // For councilors, find the first one with a photo and delete it
                $official = Official::where('position', $position)
                    ->whereNotNull('profile_pic')
                    ->first();
            } else {
                // For other positions, find the specific official
                $official = Official::where('position', $position)->first();
            }
            
            if ($official && $official->profile_pic) {
                // Delete the file
                if (Storage::disk('public')->exists('officials/' . $official->profile_pic)) {
                    Storage::disk('public')->delete('officials/' . $official->profile_pic);
                }
                
                // Update database
                $official->update(['profile_pic' => null]);
                
                return response()->json(['success' => true, 'message' => 'Photo deleted successfully.']);
            }
        }
        
        return response()->json(['success' => false, 'message' => 'Photo not found.']);
    }
}
