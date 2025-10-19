/**
 * Show the bulk upload page
 */
public function showBulkUploadForm()
{
    $readyForIssuance = Resident::where(function($query) {
        $query->whereNotNull('photo')
              ->whereNotNull('signature')
              ->where('id_status', '!=', 'issued');
    })->get();

    return view('admin.residents.bulk-upload', compact('readyForIssuance'));
}

/**
 * Process bulk photo upload
 */
public function processBulkUpload(Request $request)
{
    $request->validate([
        'photos' => 'required|array',
        'photos.*' => 'image|max:5120', // 5MB max
        'naming_pattern' => 'required|in:barangay_id,full_name,last_first',
    ]);

    $successCount = 0;
    $failedCount = 0;
    $notFoundCount = 0;

    // Process each uploaded photo
    foreach ($request->file('photos') as $photo) {
        $originalName = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
        
        // Find the resident based on the naming pattern
        $resident = null;
        switch ($request->naming_pattern) {
            case 'barangay_id':
                $resident = Resident::where('barangay_id', $originalName)->first();
                break;
                
            case 'full_name':
                $resident = Resident::whereRaw("CONCAT(first_name, last_name) LIKE ?", ["%{$originalName}%"])
                            ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$originalName}%"])
                            ->first();
                break;
                
            case 'last_first':
                $nameParts = explode('_', $originalName);
                if (count($nameParts) >= 2) {
                    $lastName = $nameParts[0];
                    $firstName = $nameParts[1];
                    $resident = Resident::where('last_name', 'like', $lastName)
                                ->where('first_name', 'like', $firstName.'%')
                                ->first();
                }
                break;
        }

        // If resident found, save the photo
        if ($resident) {
            try {
                // Generate a unique filename
                $filename = $resident->id . '-' . uniqid() . '.' . $photo->getClientOriginalExtension();
                
                // Store the photo
                $photo->storeAs('residents/photos', $filename, 'public');
                
                // Update the resident record
                $resident->photo = $filename;
                $resident->save();
                
                $successCount++;
            } catch (\Exception $e) {
                $failedCount++;
            }
        } else {
            $notFoundCount++;
        }
    }

    if ($successCount > 0) {
        return back()->with('success', "{$successCount} photos uploaded successfully. " . 
            ($failedCount > 0 ? "{$failedCount} failed. " : "") . 
            ($notFoundCount > 0 ? "{$notFoundCount} residents not found." : ""));
    } else {
        return back()->with('error', "No photos were uploaded. " . 
            ($notFoundCount > 0 ? "{$notFoundCount} residents not found. " : "") .
            "Please check the naming pattern and try again.");
    }
}

/**
 * Process bulk ID issuance
 */
public function bulkIssueIds(Request $request)
{
    $request->validate([
        'resident_ids' => 'required|array',
        'resident_ids.*' => 'exists:residents,id',
    ]);
    
    $count = 0;
    $today = now();
    $expiryDate = now()->addYears(3); // 3-year validity
    
    foreach ($request->resident_ids as $residentId) {
        $resident = Resident::find($residentId);
        
        if ($resident && $resident->photo && $resident->signature && $resident->id_status !== 'issued') {
            $resident->id_status = 'issued';
            $resident->id_issued_at = $today;
            $resident->id_expires_at = $expiryDate;
            $resident->save();
            $count++;
        }
    }
    
    if ($count > 0) {
        return back()->with('success', "Successfully issued {$count} resident IDs.");
    } else {
        return back()->with('error', "No IDs were issued. Please make sure selected residents have photos and signatures.");
    }
}