<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BarangayOfficial;

class BarangayOfficialController extends Controller
{
    public function edit()
    {
        // Always use the first (and only) record
        $officials = BarangayOfficial::first();
        if (!$officials) {
            $officials = BarangayOfficial::create([]); // create empty if not exists
        }
        return view('admin.officials.edit-single', compact('officials'));
    }

    public function update(Request $request)
    {
        $officials = BarangayOfficial::first();
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
        ]);
        $officials->update($validated);
        return redirect()->route('admin.officials.edit-single')->with('success', 'Officials updated successfully.');
    }
}
