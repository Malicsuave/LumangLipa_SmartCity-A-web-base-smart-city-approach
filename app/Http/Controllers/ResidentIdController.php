<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use App\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Activitylog\Facades\Activity;

class ResidentIdController extends Controller
{
    /**
     * Display the ID management page for a specific resident.
     *
     * @param Resident $resident
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Resident $resident)
    {
        // Check if this resident is a senior citizen
        if ($resident->seniorCitizen) {
            // Automatically redirect to senior citizen ID management
            return redirect()->route('admin.senior-citizens.id-management', $resident->seniorCitizen)
                ->with('info', 'Redirected to Senior Citizen ID Management - this resident has both Resident ID and Senior Citizen ID available.');
        }
        
        return view('admin.residents.id', compact('resident'));
    }

    /**
     * Upload a photo for a resident's ID.
     *
     * @param Request $request
     * @param Resident $resident
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadPhoto(Request $request, Resident $resident)
    {
        $request->validate([
            'photo' => 'required|image|max:5000',
        ]);

        try {
            // Delete old photo if exists
            if ($resident->photo) {
                Storage::disk('public')->delete('residents/photos/' . $resident->photo);
            }

            // Process and save the new photo
            $image = Image::make($request->file('photo'));
            
            // Resize to a standard ID photo size (2x2 inches at 300dpi = 600x600 pixels)
            $image->fit(600, 600);
            
            // Generate unique filename
            $filename = $resident->barangay_id . '_' . Str::random(10) . '.jpg';
            
            // Save the processed image
            $path = storage_path('app/public/residents/photos/' . $filename);
            
            // Ensure directory exists
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }
            
            // Save image with compression
            $image->save($path, 80);
            
            // Update the resident record
            $resident->update([
                'photo' => $filename
            ]);

            return back()->with('success', 'Resident photo uploaded successfully.');
        } catch (\Exception $e) {
            Log::error('Error uploading resident photo: ' . $e->getMessage());
            return back()->with('error', 'Failed to upload photo. Please try again.');
        }
    }

    /**
     * Upload a signature for a resident's ID.
     *
     * @param Request $request
     * @param Resident $resident
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadSignature(Request $request, Resident $resident)
    {
        $request->validate([
            'signature' => 'required|image|max:2000',
        ]);

        try {
            // Delete old signature if exists
            if ($resident->signature) {
                Storage::disk('public')->delete('residents/signatures/' . $resident->signature);
            }

            // Process and save the signature
            $image = Image::make($request->file('signature'));
            
            // Resize to a standard signature size
            $image->resize(400, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            // Generate unique filename
            $filename = $resident->barangay_id . '_' . Str::random(10) . '.png';
            
            // Save the processed signature
            $path = storage_path('app/public/residents/signatures/' . $filename);
            
            // Ensure directory exists
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }
            
            // Save with transparency (PNG format)
            $image->save($path);
            
            // Update the resident record
            $resident->update([
                'signature' => $filename
            ]);

            return back()->with('success', 'Signature uploaded successfully.');
        } catch (\Exception $e) {
            Log::error('Error uploading signature: ' . $e->getMessage());
            return back()->with('error', 'Failed to upload signature. Please try again.');
        }
    }

    /**
     * Issue a new ID card for the resident.
     *
     * @param Request $request
     * @param Resident $resident
     * @return \Illuminate\Http\RedirectResponse
     */
    public function issueId(Request $request, Resident $resident)
    {
        // Validate required fields for ID issuance
        if (!$resident->photo) {
            return back()->with('error', 'Unable to issue ID card: Resident photo is required.');
        }

        // Set ID expiration (valid for 5 years)
        $issuedAt = Carbon::now();
        $expiresAt = $issuedAt->copy()->addYears(5);
        
        // Update resident record with ID information
        $resident->update([
            'id_status' => 'issued',
            'id_issued_at' => $issuedAt,
            'id_expires_at' => $expiresAt,
        ]);
        
        // Record activity
        Activity::causedBy(auth()->user())
            ->performedOn($resident)
            ->withProperties([
                'id_issued_at' => $issuedAt,
                'id_expires_at' => $expiresAt,
            ])
            ->log('issued_id_card');

        return back()->with('success', 'ID card has been successfully issued. Valid until ' . $expiresAt->format('M d, Y') . '.');
    }

    /**
     * Preview the ID card for the resident.
     *
     * @param Resident $resident
     * @return \Illuminate\View\View
     */
    public function previewId(Resident $resident)
    {
        // Generate QR code data (contains barangay ID and name)
        $qrData = json_encode([
            'id' => $resident->barangay_id,
            'name' => $resident->full_name,
            'dob' => $resident->birthdate ? $resident->birthdate->format('Y-m-d') : null,
        ]);
        
        // Generate QR code, ensure it's base64 encoded without the data URI prefix
        // The view template will add the proper prefix
        $qrCodeRaw = QrCode::generateQrCode($qrData, 200);
        
        // If the QR code already has the data URI prefix, extract just the base64 part
        $qrCode = $qrCodeRaw;
        if (strpos($qrCodeRaw, 'data:image/png;base64,') === 0) {
            $qrCode = substr($qrCodeRaw, 22); // Remove the prefix
        }
        
        return view('admin.residents.id-preview', [
            'resident' => $resident,
            'qrCode' => $qrCode,
        ]);
    }

    /**
     * Generate and download the ID card as PDF.
     *
     * @param Resident $resident
     * @return \Illuminate\Http\Response
     */
    public function downloadId(Resident $resident)
    {
        // Generate QR code data
        $qrData = json_encode([
            'id' => $resident->barangay_id,
            'name' => $resident->full_name,
            'dob' => $resident->birthdate ? $resident->birthdate->format('Y-m-d') : null,
        ]);
        
        $qrCode = QrCode::generateQrCode($qrData, 300);
        
        // If the QR code already has the data URI prefix, extract just the base64 part
        if (strpos($qrCode, 'data:image/png;base64,') === 0) {
            $qrCode = substr($qrCode, 22); // Remove the prefix to match senior citizen handling
        }
        
        // Generate PDF using Snappy with same settings as senior citizen ID
        $pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadView('admin.residents.id-pdf', [
            'resident' => $resident,
            'qrCode' => $qrCode,
        ]);
        
        // Set PDF options to match senior citizen ID exactly
        $pdf->setOptions([
            'page-width' => '148mm',    // Same as senior citizen ID
            'page-height' => '180mm',   // Same as senior citizen ID
            'orientation' => 'Portrait',
            'margin-top' => '8mm',
            'margin-right' => '8mm',
            'margin-bottom' => '8mm',
            'margin-left' => '8mm',
            'encoding' => 'UTF-8',
            'enable-local-file-access' => true,
            'disable-smart-shrinking' => true,
            'dpi' => 300,
            'image-quality' => 100,
            'zoom' => 1.0,
            'load-error-handling' => 'ignore',
            'load-media-error-handling' => 'ignore',
            'enable-external-links' => true,
            'enable-internal-links' => true,
            'javascript-delay' => 1000,
            'no-stop-slow-scripts' => true,
            'debug-javascript' => true
        ]);
        
        // Record download activity
        Activity::causedBy(auth()->user())
            ->performedOn($resident)
            ->log('downloaded_id_card');

        $filename = 'RESIDENT_ID_' . $resident->last_name . '_' . $resident->first_name . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Mark an ID card as needing renewal.
     *
     * @param Resident $resident
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markForRenewal(Resident $resident)
    {
        $resident->update([
            'id_status' => 'needs_renewal',
        ]);
        
        Activity::causedBy(auth()->user())
            ->performedOn($resident)
            ->log('marked_id_for_renewal');

        return back()->with('success', 'Resident ID has been marked for renewal.');
    }

    /**
     * Remove a resident from the renewal queue.
     *
     * @param Resident $resident
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeFromRenewal(Resident $resident)
    {
        $resident->update([
            'id_status' => 'issued', // Change back to issued status
        ]);
        
        Activity::causedBy(auth()->user())
            ->performedOn($resident)
            ->log('removed_from_renewal_queue');

        return back()->with('success', 'Resident has been removed from the renewal queue.');
    }

    /**
     * Display residents with pending ID issuance or renewal.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function pendingIds(Request $request)
    {
        // Status filter (Default, All, Issued, Not Issued)
        $statusFilter = $request->input('status_filter', 'default');
        
        // Expiry timeframe filter
        $expiryFilter = $request->input('expiry_filter', 'default');
        
        // New: Custom date range filters
        $startDate = $request->input('expiry_start_date');
        $endDate = $request->input('expiry_end_date');
        $customDateRange = ($startDate && $endDate);
        
        // Start with all residents
        $residents = Resident::query();
        
        // Apply status filter
        if ($statusFilter === 'issued') {
            $residents->where('id_status', 'issued');
        } elseif ($statusFilter === 'not_issued') {
            $residents->where(function($query) {
                $query->whereNull('id_status')
                    ->orWhere('id_status', '!=', 'issued');
            });
        } elseif ($statusFilter === 'default') {
            // Default filter shows those with photos and ready for issuance
            $residents->where('photo', '!=', '');
        }
        
        // Get three collections
        
        // 1. Pending ID Issuance
        $pendingIssuance = clone $residents;
        $pendingIssuance = $pendingIssuance->where('id_status', '!=', 'needs_renewal')
            ->where('photo', '!=', '')
            ->paginate(10, ['*'], 'issuance_page')
            ->appends(request()->except('issuance_page'));
        
        // 2. Pending renewals
        $pendingRenewal = Resident::where('id_status', 'needs_renewal')
            ->paginate(10, ['*'], 'renewal_page')
            ->appends(request()->except('renewal_page'));
        
        // 3. Expiring soon
        $expiringQuery = Resident::where('id_status', 'issued')
            ->whereNotNull('id_expires_at');
        
        // Apply expiry filter timeframe
        if ($expiryFilter === 'default' && !$customDateRange) {
            $expiringQuery->where('id_expires_at', '<=', Carbon::now()->addMonths(3));
        } elseif ($expiryFilter === '1month' && !$customDateRange) {
            $expiringQuery->where('id_expires_at', '<=', Carbon::now()->addMonth());
        } elseif ($expiryFilter === '6months' && !$customDateRange) {
            $expiringQuery->where('id_expires_at', '<=', Carbon::now()->addMonths(6));
        } elseif ($expiryFilter === '1year' && !$customDateRange) {
            $expiringQuery->where('id_expires_at', '<=', Carbon::now()->addYear());
        } elseif ($customDateRange) {
            // Apply custom date range filter if both dates are provided
            $expiringQuery->whereBetween('id_expires_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }
        
        $expiringSoon = $expiringQuery->paginate(10, ['*'], 'expiring_page')
            ->appends(request()->except('expiring_page'));
        
        return view('admin.residents.pending-ids', compact('pendingIssuance', 'pendingRenewal', 'expiringSoon'));
    }

    /**
     * Display the bulk photo upload form.
     *
     * @return \Illuminate\View\View
     */
    public function bulkUpload()
    {
        // Get residents who are ready for ID issuance
        $readyForIssuance = Resident::where('status', 'active')
            ->orderBy('last_name')
            ->get();
            
        Activity::log('Viewed bulk ID photo upload page');
        
        return view('admin.residents.bulk-upload', compact('readyForIssuance'));
    }

    /**
     * Process bulk photo uploads.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processBulkUpload(Request $request)
    {
        // First check if there are any files in the request
        if (!$request->hasFile('photos')) {
            return redirect()->route('admin.residents.id.bulk-upload')
                ->with('error', 'No photos were selected. Please select at least one photo file.');
        }

        // Validate the uploaded files
        $request->validate([
            'photos.*' => 'required|image|max:5120', // 5MB max
            'naming_pattern' => 'required|in:barangay_id,full_name,last_first',
        ]);

        $uploadedCount = 0;
        $errors = [];
        $debugInfo = [];

        // Process each uploaded photo
        foreach ($request->file('photos') as $photo) {
            try {
                $filename = $photo->getClientOriginalName();
                $pattern = $request->naming_pattern;
                
                // Find the resident based on naming pattern
                $resident = $this->findResidentByFilename($filename, $pattern);
                
                if ($resident) {
                    // Process and save the photo
                    $image = Image::make($photo);
                    
                    // Resize to a standard ID photo size (2x2 inches at 300dpi = 600x600 pixels)
                    $image->fit(600, 600);
                    
                    // Generate unique filename for storage
                    $storedFilename = $resident->barangay_id . '_' . Str::random(10) . '.jpg';
                    
                    // Ensure directory exists
                    $directory = 'app/public/residents/photos';
                    $storagePath = storage_path($directory);
                    
                    if (!file_exists($storagePath)) {
                        mkdir($storagePath, 0755, true);
                    }
                    
                    // Save the processed image
                    $path = $storagePath . '/' . $storedFilename;
                    
                    // Save image with compression
                    $image->save($path, 80);
                    
                    // Update the resident record
                    $resident->update([
                        'photo' => $storedFilename
                    ]);
                    
                    $uploadedCount++;
                    
                    // Log the activity
                    Activity::causedBy(auth()->user())
                        ->performedOn($resident)
                        ->log('uploaded_id_photo_via_bulk');
                } else {
                    $errors[] = "No matching resident found for file: {$filename}";
                }
            } catch (\Exception $e) {
                Log::error('Error processing bulk upload photo: ' . $e->getMessage());
                $errors[] = "Error processing {$filename}: " . $e->getMessage();
            }
        }

        if ($uploadedCount > 0) {
            return redirect()->route('admin.residents.id.bulk-upload')
                ->with('success', "{$uploadedCount} photos uploaded successfully.")
                ->with('errors', $errors);
        }

        // If we get here, no photos were successfully uploaded
        return redirect()->route('admin.residents.id.bulk-upload')
            ->with('error', 'No photos were uploaded. Please check the file names match the selected pattern.')
            ->with('errors', $errors);
    }

    /**
     * Process bulk signature uploads.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processBulkSignatureUpload(Request $request)
    {
        // First check if there are any files in the request
        if (!$request->hasFile('signatures')) {
            return redirect()->route('admin.residents.id.bulk-upload')
                ->with('error', 'No signatures were selected. Please select at least one signature file.');
        }

        // Validate the uploaded files
        $request->validate([
            'signatures.*' => 'required|image|max:2048', // 2MB max
            'signature_naming_pattern' => 'required|in:barangay_id,full_name,last_first',
        ]);

        $uploadedCount = 0;
        $errors = [];

        // Process each uploaded signature
        foreach ($request->file('signatures') as $signature) {
            try {
                $filename = $signature->getClientOriginalName();
                $pattern = $request->signature_naming_pattern;
                
                // Find the resident based on naming pattern
                $resident = $this->findResidentByFilename($filename, $pattern);
                
                if ($resident) {
                    // Delete old signature if exists
                    if ($resident->signature) {
                        Storage::disk('public')->delete('residents/signatures/' . $resident->signature);
                    }

                    // Process and save the signature
                    $image = Image::make($signature);
                    
                    // Resize to a standard signature size
                    $image->resize(400, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    
                    // Generate unique filename for storage
                    $storedFilename = $resident->barangay_id . '_' . Str::random(10) . '.png';
                    
                    // Ensure directory exists
                    $directory = 'app/public/residents/signatures';
                    $storagePath = storage_path($directory);
                    
                    if (!file_exists($storagePath)) {
                        mkdir($storagePath, 0755, true);
                    }
                    
                    // Save the processed signature
                    $path = $storagePath . '/' . $storedFilename;
                    
                    // Save with transparency (PNG format)
                    $image->save($path);
                    
                    // Update the resident record
                    $resident->update([
                        'signature' => $storedFilename
                    ]);
                    
                    $uploadedCount++;
                    
                    // Log the activity
                    Activity::causedBy(auth()->user())
                        ->performedOn($resident)
                        ->log('uploaded_signature_via_bulk');
                } else {
                    $errors[] = "No matching resident found for file: {$filename}";
                }
            } catch (\Exception $e) {
                Log::error('Error processing bulk upload signature: ' . $e->getMessage());
                $errors[] = "Error processing {$filename}: " . $e->getMessage();
            }
        }

        if ($uploadedCount > 0) {
            return redirect()->route('admin.residents.id.bulk-upload')
                ->with('success', "{$uploadedCount} signatures uploaded successfully.")
                ->with('errors', $errors);
        }

        // If we get here, no signatures were successfully uploaded
        return redirect()->route('admin.residents.id.bulk-upload')
            ->with('error', 'No signatures were uploaded. Please check the file names match the selected pattern.')
            ->with('errors', $errors);
    }

    /**
     * Issue ID cards in bulk to residents.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkIssue(Request $request)
    {
        $request->validate([
            'resident_ids' => 'required|array',
            'resident_ids.*' => 'exists:residents,id',
        ]);

        $issuedCount = 0;
        
        foreach ($request->resident_ids as $residentId) {
            $resident = Resident::findOrFail($residentId);
            
            // Only issue ID if resident has photo
            if ($resident->photo && $resident->id_status !== 'issued') {
                // Set ID expiration (valid for 5 years)
                $issuedAt = Carbon::now();
                $expiresAt = $issuedAt->copy()->addYears(5);
                
                $resident->id_status = 'issued';
                $resident->id_issued_at = $issuedAt;
                $resident->id_expires_at = $expiresAt;
                $resident->save();
                
                $issuedCount++;
                
                // Record activity
                Activity::causedBy(auth()->user())
                    ->performedOn($resident)
                    ->withProperties([
                        'id_issued_at' => $issuedAt,
                        'id_expires_at' => $expiresAt,
                    ])
                    ->log('issued_id_card_via_bulk');
            }
        }

        return redirect()->route('admin.residents.id.bulk-upload')
            ->with('success', "ID cards issued for {$issuedCount} residents.");
    }

    /**
     * Update Resident ID information.
     *
     * @param Request $request
     * @param Resident $resident
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateIdInfo(Request $request, Resident $resident)
    {
        // Merge the current id_status if it's not provided in the request
        if (!$request->has('id_status') && $resident->id_status) {
            $request->merge(['id_status' => $resident->id_status]);
        }

        $request->validate([
            'id_number' => 'nullable|string|max:50',
            'id_issued_at' => 'nullable|date',
            'id_expires_at' => 'nullable|date|after_or_equal:id_issued_at',
            'id_status' => 'required|string|in:issued,needs_renewal,expired',
        ]);

        // Update resident ID information
        $resident->update([
            'id_number' => $request->id_number,
            'id_issued_at' => $request->id_issued_at,
            'id_expires_at' => $request->id_expires_at,
            'id_status' => $request->id_status,
        ]);
        
        // Record activity
        Activity::causedBy(auth()->user())
            ->performedOn($resident)
            ->withProperties($request->only(['id_number', 'id_issued_at', 'id_expires_at', 'id_status']))
            ->log('updated_id_information');

        return back()->with('success', 'ID information has been updated successfully.');
    }

    /**
     * Helper method to find resident by filename based on naming pattern.
     *
     * @param string $filename
     * @param string $pattern
     * @return \App\Models\Resident|null
     */
    private function findResidentByFilename($filename, $pattern)
    {
        // Remove file extension
        $name = pathinfo($filename, PATHINFO_FILENAME);
        
        switch ($pattern) {
            case 'barangay_id':
                return Resident::where('barangay_id', $name)->first();
                
            case 'full_name':
                // Assumes format "FirstnameLastname" with no spaces
                $name = str_replace([' ', '_', '-'], '', $name);
                return Resident::whereRaw("CONCAT(REPLACE(LOWER(first_name), ' ', ''), REPLACE(LOWER(last_name), ' ', '')) = ?", [strtolower($name)])
                    ->orWhereRaw("CONCAT(REPLACE(LOWER(last_name), ' ', ''), REPLACE(LOWER(first_name), ' ', '')) = ?", [strtolower($name)])
                    ->first();
                
            case 'last_first':
                // Assumes format "Lastname_Firstname"
                $parts = explode('_', $name);
                if (count($parts) >= 2) {
                    $lastName = $parts[0];
                    $firstName = $parts[1];
                    return Resident::whereRaw("LOWER(last_name) = ? AND LOWER(first_name) LIKE ?", [strtolower($lastName), strtolower($firstName).'%'])
                        ->first();
                }
                return null;
                
            default:
                return null;
        }
    }
}