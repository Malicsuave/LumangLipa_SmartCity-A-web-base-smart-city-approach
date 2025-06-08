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
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

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
        activity()
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
        
        $qrCode = base64_encode(QrCode::format('png')->size(200)->generate($qrData));
        
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
        
        $qrCode = base64_encode(QrCode::format('png')->size(300)->generate($qrData));
        
        // Generate PDF
        $pdf = PDF::loadView('admin.residents.id-pdf', [
            'resident' => $resident,
            'qrCode' => $qrCode,
        ]);
        
        // Set custom paper size for ID card (standard ID size 3.375" x 2.125")
        $pdf->setPaper([0, 0, 243, 153], 'landscape');
        
        // Record download activity
        activity()
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
        
        activity()
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
        $pendingIssuance = Resident::where('id_status', 'pending')
            ->orWhere(function($query) {
                $query->where('id_status', 'not_issued')
                      ->whereNotNull('photo');
            })
            ->latest()
            ->get();
            
        $pendingRenewal = Resident::where('id_status', 'needs_renewal')
            ->latest()
            ->get();
            
        $expiringSoon = Resident::where('id_status', 'issued')
            ->whereNotNull('id_expires_at')
            ->where('id_expires_at', '<=', Carbon::now()->addMonths(3))
            ->latest()
            ->get();
            
        return view('admin.residents.pending-ids', [
            'pendingIssuance' => $pendingIssuance,
            'pendingRenewal' => $pendingRenewal,
            'expiringSoon' => $expiringSoon,
        ]);
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
            'resident_ids' => 'required|array',
            'resident_ids.*' => 'exists:residents,id'
        ]);
        
        $count = 0;
        $issuedAt = Carbon::now();
        $expiresAt = $issuedAt->copy()->addYears(5);
        
        foreach ($request->resident_ids as $id) {
            $resident = Resident::find($id);
            
            // Skip residents without photos
            if (!$resident || !$resident->photo) {
                continue;
            }
            
            $resident->update([
                'id_status' => 'issued',
                'id_issued_at' => $issuedAt,
                'id_expires_at' => $expiresAt,
            ]);
            
            $count++;
        }
        
        return back()->with('success', "{$count} resident ID cards have been successfully issued.");
    }
}