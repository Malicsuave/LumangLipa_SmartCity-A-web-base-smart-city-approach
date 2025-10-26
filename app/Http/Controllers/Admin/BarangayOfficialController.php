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
        // Get all officials and organize them by position
        $officials = (object) [
            'captain_name' => '',
            'secretary_name' => '',
            'treasurer_name' => '',
            'sk_chairperson_name' => '',
            'sk_chairperson_committee' => '',
            'captain_photo' => '',
            'secretary_photo' => '',
            'treasurer_photo' => '',
            'sk_chairperson_photo' => '',
        ];
        
        // Add councilor fields
        for ($i = 1; $i <= 7; $i++) {
            $officials->{"councilor{$i}_name"} = '';
            $officials->{"councilor{$i}_committee"} = '';
            $officials->{"councilor{$i}_photo"} = '';
        }
        
        // Get existing officials from database
        $existingOfficials = Official::all();
        
        foreach ($existingOfficials as $official) {
            switch ($official->position) {
                case 'Captain':
                    $officials->captain_name = $official->name;
                    $officials->captain_photo = $official->profile_pic;
                    break;
                case 'Secretary':
                    $officials->secretary_name = $official->name;
                    $officials->secretary_photo = $official->profile_pic;
                    break;
                case 'Treasurer':
                    $officials->treasurer_name = $official->name;
                    $officials->treasurer_photo = $official->profile_pic;
                    break;
                case 'SK Chairman':
                    $officials->sk_chairperson_name = $official->name;
                    $officials->sk_chairperson_committee = $official->committee;
                    $officials->sk_chairperson_photo = $official->profile_pic;
                    break;
                case 'Councilor':
                    // Find an empty councilor slot
                    for ($i = 1; $i <= 7; $i++) {
                        if (empty($officials->{"councilor{$i}_name"})) {
                            $officials->{"councilor{$i}_name"} = $official->name;
                            $officials->{"councilor{$i}_committee"} = $official->committee;
                            $officials->{"councilor{$i}_photo"} = $official->profile_pic;
                            break;
                        }
                    }
                    break;
            }
        }
        
        return view('admin.officials.edit-single', compact('officials'));
    }

    public function update(Request $request)
    {
        // Validation rules for names, committees and photos
        $validated = $request->validate([
            'captain_name' => 'nullable|string',
            'secretary_name' => 'nullable|string',
            'sk_chairperson_name' => 'nullable|string',
            'sk_chairperson_committee' => 'nullable|string',
            'treasurer_name' => 'nullable|string',
            'councilor1_name' => 'nullable|string',
            'councilor1_committee' => 'nullable|string',
            'councilor2_name' => 'nullable|string',
            'councilor2_committee' => 'nullable|string',
            'councilor3_name' => 'nullable|string',
            'councilor3_committee' => 'nullable|string',
            'councilor4_name' => 'nullable|string',
            'councilor4_committee' => 'nullable|string',
            'councilor5_name' => 'nullable|string',
            'councilor5_committee' => 'nullable|string',
            'councilor6_name' => 'nullable|string',
            'councilor6_committee' => 'nullable|string',
            'councilor7_name' => 'nullable|string',
            'councilor7_committee' => 'nullable|string',
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

        // Store existing photos before clearing
        $existingOfficials = Official::all();
        $existingPhotos = [];
        
        // Store existing councilors in order
        $existingCouncilors = Official::where('position', 'Councilor')->get();
        
        foreach ($existingOfficials as $official) {
            switch ($official->position) {
                case 'Captain':
                    $existingPhotos['captain_photo'] = $official->profile_pic;
                    break;
                case 'Secretary':
                    $existingPhotos['secretary_photo'] = $official->profile_pic;
                    break;
                case 'Treasurer':
                    $existingPhotos['treasurer_photo'] = $official->profile_pic;
                    break;
                case 'SK Chairman':
                    $existingPhotos['sk_chairperson_photo'] = $official->profile_pic;
                    break;
            }
        }
        
        // Store councilor photos by their current index
        for ($i = 0; $i < min(7, $existingCouncilors->count()); $i++) {
            $existingPhotos["councilor" . ($i + 1) . "_photo"] = $existingCouncilors[$i]->profile_pic;
        }

        // Clear existing officials and recreate them
        Official::truncate();

        // Process Captain
        if ($request->captain_name) {
            $captainData = [
                'position' => 'Captain',
                'name' => $request->captain_name,
                'committee' => null,
                'profile_pic' => null,
            ];
            
            if ($request->hasFile('captain_photo')) {
                $captainData['profile_pic'] = $this->handlePhotoUpload($request->file('captain_photo'), 'captain');
            } else {
                // Preserve existing photo if no new photo is uploaded
                $captainData['profile_pic'] = $existingPhotos['captain_photo'] ?? null;
            }
            
            Official::create($captainData);
        }

        // Process Secretary
        if ($request->secretary_name) {
            $secretaryData = [
                'position' => 'Secretary',
                'name' => $request->secretary_name,
                'committee' => null,
                'profile_pic' => null,
            ];
            
            if ($request->hasFile('secretary_photo')) {
                $secretaryData['profile_pic'] = $this->handlePhotoUpload($request->file('secretary_photo'), 'secretary');
            } else {
                // Preserve existing photo if no new photo is uploaded
                $secretaryData['profile_pic'] = $existingPhotos['secretary_photo'] ?? null;
            }
            
            Official::create($secretaryData);
        }

        // Process Treasurer
        if ($request->treasurer_name) {
            $treasurerData = [
                'position' => 'Treasurer',
                'name' => $request->treasurer_name,
                'committee' => null,
                'profile_pic' => null,
            ];
            
            if ($request->hasFile('treasurer_photo')) {
                $treasurerData['profile_pic'] = $this->handlePhotoUpload($request->file('treasurer_photo'), 'treasurer');
            } else {
                // Preserve existing photo if no new photo is uploaded
                $treasurerData['profile_pic'] = $existingPhotos['treasurer_photo'] ?? null;
            }
            
            Official::create($treasurerData);
        }

        // Process SK Chairperson
        if ($request->sk_chairperson_name) {
            $skData = [
                'position' => 'SK Chairman',
                'name' => $request->sk_chairperson_name,
                'committee' => $request->sk_chairperson_committee,
                'profile_pic' => null,
            ];
            
            if ($request->hasFile('sk_chairperson_photo')) {
                $skData['profile_pic'] = $this->handlePhotoUpload($request->file('sk_chairperson_photo'), 'sk_chairperson');
            } else {
                // Preserve existing photo if no new photo is uploaded
                $skData['profile_pic'] = $existingPhotos['sk_chairperson_photo'] ?? null;
            }
            
            Official::create($skData);
        }

        // Process Councilors
        for ($i = 1; $i <= 7; $i++) {
            $nameField = "councilor{$i}_name";
            $committeeField = "councilor{$i}_committee";
            $photoField = "councilor{$i}_photo";
            
            if ($request->$nameField) {
                $councilorData = [
                    'position' => 'Councilor',
                    'name' => $request->$nameField,
                    'committee' => $request->$committeeField,
                    'profile_pic' => null,
                ];
                
                if ($request->hasFile($photoField)) {
                    $councilorData['profile_pic'] = $this->handlePhotoUpload($request->file($photoField), "councilor{$i}");
                } else {
                    // Preserve existing photo if no new photo is uploaded
                    $councilorData['profile_pic'] = $existingPhotos["councilor{$i}_photo"] ?? null;
                }
                
                Official::create($councilorData);
            }
        }

        return redirect()->route('admin.officials.edit-single')->with('success', 'Officials information updated successfully.');
    }

    /**
     * Handle photo upload
     */
    private function handlePhotoUpload($file, $prefix)
    {
        $extension = $file->getClientOriginalExtension();
        $filename = $prefix . '_' . time() . '.' . $extension;
        $file->storeAs('officials', $filename, 'public');
        return $filename;
    }

    /**
     * Delete an official's photo
     */
    public function deletePhoto(Request $request, $field)
    {
        // Map field names to positions
        $fieldToPosition = [
            'captain_photo' => 'Captain',
            'secretary_photo' => 'Secretary',
            'treasurer_photo' => 'Treasurer',
            'sk_chairperson_photo' => 'SK Chairman',
        ];
        
        // Handle councilor photos
        if (preg_match('/councilor(\d+)_photo/', $field, $matches)) {
            $councilors = Official::where('position', 'Councilor')->get();
            $councilorIndex = (int)$matches[1] - 1;
            
            if (isset($councilors[$councilorIndex])) {
                $official = $councilors[$councilorIndex];
                if ($official->profile_pic) {
                    if (Storage::disk('public')->exists('officials/' . $official->profile_pic)) {
                        Storage::disk('public')->delete('officials/' . $official->profile_pic);
                    }
                    $official->profile_pic = null;
                    $official->save();
                    return response()->json(['success' => true, 'message' => 'Photo deleted successfully.']);
                }
            }
        } else {
            // Handle other positions
            $position = $fieldToPosition[$field] ?? null;
            if ($position) {
                $official = Official::where('position', $position)->first();
                if ($official && $official->profile_pic) {
                    if (Storage::disk('public')->exists('officials/' . $official->profile_pic)) {
                        Storage::disk('public')->delete('officials/' . $official->profile_pic);
                    }
                    $official->profile_pic = null;
                    $official->save();
                    return response()->json(['success' => true, 'message' => 'Photo deleted successfully.']);
                }
            }
        }
        
        return response()->json(['success' => false, 'message' => 'Photo not found.']);
    }
}
