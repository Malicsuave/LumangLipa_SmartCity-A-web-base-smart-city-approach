<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangayOfficial;
use Illuminate\Support\Facades\Storage;

class BarangayOfficialController extends Controller
{
    public function edit()
    {
        // Get the single row from barangay_officials
        $officials = BarangayOfficial::first();
        
        // If no officials record exists, create a default one
        if (!$officials) {
            $officials = BarangayOfficial::create([
                'captain_name' => '',
                'secretary_name' => '',
                'treasurer_name' => '',
                'sk_chairperson_name' => '',
                'councilor1_name' => '',
                'councilor2_name' => '',
                'councilor3_name' => '',
                'councilor4_name' => '',
                'councilor5_name' => '',
                'councilor6_name' => '',
                'councilor7_name' => '',
            ]);
        }
        
        return view('admin.officials.edit-single', compact('officials'));
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

        // Find the existing officials record or create new one
        $officials = BarangayOfficial::first();

        if (!$officials) {
            // If no record exists, create a new one
            $officials = new BarangayOfficial();
        }

        // Update officials data
        $officials->captain_name = $request->captain_name;
        $officials->secretary_name = $request->secretary_name;
        $officials->treasurer_name = $request->treasurer_name;
        $officials->sk_chairperson_name = $request->sk_chairperson_name;

        // Process each councilor
        for ($i = 1; $i <= 7; $i++) {
            $nameField = "councilor{$i}_name";
            $officials->{"councilor{$i}_name"} = $request->$nameField;
        }

        // Handle photo uploads
        $photoFields = [
            'captain_photo' => 'captain_name',
            'secretary_photo' => 'secretary_name',
            'treasurer_photo' => 'treasurer_name',
            'sk_chairperson_photo' => 'sk_chairperson_name',
        ];

        foreach ($photoFields as $photoField => $nameField) {
            if ($request->hasFile($photoField)) {
                // Delete old photo if exists
                if ($officials->$photoField) {
                    Storage::disk('public')->delete('officials/' . $officials->$photoField);
                }

                $file = $request->file($photoField);
                $extension = $file->getClientOriginalExtension();
                $filename = strtolower(str_replace('_photo', '', $photoField)) . '_' . time() . '.' . $extension;
                $path = $file->storeAs('officials', $filename, 'public');
                $officials->$photoField = $filename;
            }
        }

        // Process councilors - more sophisticated approach
        for ($i = 1; $i <= 7; $i++) {
            $nameField = "councilor{$i}_name";
            $photoField = "councilor{$i}_photo";

            if ($request->hasFile($photoField)) {
                // Delete old photo if exists
                if ($officials->$photoField) {
                    Storage::disk('public')->delete('officials/' . $officials->$photoField);
                }

                $file = $request->file($photoField);
                $extension = $file->getClientOriginalExtension();
                $filename = "councilor{$i}_" . time() . '.' . $extension;
                $path = $file->storeAs('officials', $filename, 'public');
                $officials->$photoField = $filename;
            }
        }

        $officials->save();

        return redirect()->route('admin.officials.edit-single')->with('success', 'Officials information updated successfully.');
    }

    /**
     * Delete an official's photo
     */
    public function deletePhoto(Request $request, $field)
    {
        $officials = BarangayOfficial::first();
        if ($officials && isset($officials->$field)) {
            if ($officials->$field) {
                if (Storage::disk('public')->exists('officials/' . $officials->$field)) {
                    Storage::disk('public')->delete('officials/' . $officials->$field);
                }
                $officials->$field = null;
                $officials->save();
                return response()->json(['success' => true, 'message' => 'Photo deleted successfully.']);
            }
        }
        return response()->json(['success' => false, 'message' => 'Photo not found.']);
    }
}
