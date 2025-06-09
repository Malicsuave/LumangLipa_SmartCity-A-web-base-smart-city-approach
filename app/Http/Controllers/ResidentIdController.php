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
     * @return \Illuminate\View\View
     */
    public function show(Resident $resident)
    {
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
        
        // Generate PDF
        $pdf = Pdf::loadView('admin.residents.id-pdf', [
            'resident' => $resident,
            'qrCode' => $qrCode,
        ]);
        
        // Set paper size to standard ID card dimensions (CR-80 format)
        // 3.375" × 2.125" (85.6mm × 54mm) - standard credit card size
        $pdf->setPaper([0, 0, 243, 153], 'landscape');
        
        // Record download activity
        Activity::causedBy(auth()->user())
            ->performedOn($resident)
            ->log('downloaded_id_card');

        return $pdf->download($resident->barangay_id . '_ID.pdf');
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
     * Display a listing of residents with IDs pending issuance or renewal.
     *
     * @return \Illuminate\View\View
     */
    public function pendingIds()
    {
        // Get residents who don't have an ID issue date yet
        $residents = Resident::whereNull('id_issued_at')
            ->where('status', 'active')
            ->orderBy('last_name')
            ->paginate(25);
            
        Activity::log('Viewed pending ID issuance list');
        
        return view('admin.residents.pending-ids', compact('residents'));
    }

    /**
     * Batch action to issue multiple IDs.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function batchIssue(Request $request)
    {
        $request->validate([
            'residents' => 'required|array',
            'residents.*' => 'exists:residents,id',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
        ]);
        
        $issueDate = Carbon::parse($request->issue_date);
        $expiryDate = Carbon::parse($request->expiry_date);
        $count = 0;
        
        foreach ($request->residents as $residentId) {
            $resident = Resident::find($residentId);
            if ($resident) {
                $resident->id_issued_at = $issueDate;
                $resident->id_expiry = $expiryDate;
                $resident->save();
                $count++;
            }
        }
        
        Activity::log("Batch issued {$count} resident IDs");
        
        return redirect()->route('admin.residents.id.pending')
            ->with('success', "{$count} resident IDs have been successfully issued.");
    }

    /**
     * Get ID card preview data for modal display via Ajax
     *
     * @param Resident $resident
     * @return \Illuminate\Http\JsonResponse
     */
    public function getIdPreviewData(Resident $resident)
    {
        // Generate QR code data (contains barangay ID and name)
        $qrData = json_encode([
            'id' => $resident->barangay_id,
            'name' => $resident->full_name,
            'dob' => $resident->birthdate ? $resident->birthdate->format('Y-m-d') : null,
        ]);
        
        $qrCode = QrCode::generateQrCode($qrData, 200);
        
        // Get resident data needed for the ID
        $residentData = [
            'full_name' => $resident->full_name,
            'barangay_id' => $resident->barangay_id,
            'address' => $resident->address,
            'birthdate' => $resident->birthdate ? $resident->birthdate->format('M d, Y') : 'N/A',
            'birthplace' => $resident->birthplace,
            'age' => $resident->age,
            'sex' => $resident->sex,
            'civil_status' => $resident->civil_status,
            'contact_number' => $resident->contact_number,
            'issue_date' => $resident->id_issued_at ? $resident->id_issued_at->format('m/d/Y') : date('m/d/Y'),
            'expiry_date' => $resident->id_expires_at ? $resident->id_expires_at->format('m/d/Y') : Carbon::now()->addYears(15)->format('m/d/Y'),
            'id_status' => $resident->id_status,
            'photo_url' => $resident->photo_url,
            'signature_url' => $resident->signature ? url('storage/residents/signatures/' . $resident->signature) : null,
            'is_senior' => in_array('Senior Citizen', (array)$resident->population_sectors),
        ];
        
        // Include the household and emergency contact data
        $householdData = null;
        if ($resident->household) {
            $householdData = [
                'emergency_contact_name' => $resident->household->emergency_contact_name,
                'emergency_relationship' => $resident->household->emergency_relationship,
                'emergency_phone' => $resident->household->emergency_phone
            ];
        }
        
        return response()->json([
            'resident' => $residentData,
            'household' => $householdData,
            'qr_code' => $qrCode,
        ]);
    }

    /**
     * Update the ID information for a resident.
     *
     * @param Request $request
     * @param Resident $resident
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateIdInfo(Request $request, Resident $resident)
    {
        $request->validate([
            'barangay_id' => 'nullable|string|max:50',
            'id_expires_at' => 'nullable|date|after_or_equal:today',
        ]);

        try {
            // Update the resident record with ID information
            $resident->update([
                'barangay_id' => $request->barangay_id,
                'id_expires_at' => $request->id_expires_at,
            ]);
            
            // Record activity
            Activity::causedBy(auth()->user())
                ->performedOn($resident)
                ->withProperties([
                    'barangay_id' => $request->barangay_id,
                    'id_expires_at' => $request->id_expires_at,
                ])
                ->log('updated_id_info');

            return back()->with('success', 'ID information updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating resident ID info: ' . $e->getMessage());
            return back()->with('error', 'Failed to update ID information. Please try again.');
        }
    }

    /**
     * Revoke a previously issued ID card.
     *
     * @param Resident $resident
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revokeId(Resident $resident)
    {
        // Update resident record to revoke the ID
        $resident->update([
            'id_status' => null,
            'id_issued_at' => null,
            'id_expires_at' => null,
        ]);
        
        // Record activity
        Activity::causedBy(auth()->user())
            ->performedOn($resident)
            ->log('revoked_id_card');

        return back()->with('success', 'ID card has been successfully revoked.');
    }
}