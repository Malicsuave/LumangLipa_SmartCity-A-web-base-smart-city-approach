<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreRegistration;
use App\Models\Resident;
use App\Models\SeniorCitizen;
use App\Notifications\PreRegistrationApproved;
use App\Notifications\PreRegistrationRejected;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PreRegistrationController extends Controller
{
    /**
     * Display a listing of pre-registrations.
     */
    public function index(Request $request)
    {
        $query = PreRegistration::query();

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->search($search);
        }

        // Order by creation date (newest first)
        $query->orderBy('created_at', 'desc');

        $preRegistrations = $query->paginate(20);
        $preRegistrations->appends($request->query());

        // Get statistics
        $stats = [
            'total' => PreRegistration::count(),
            'pending' => PreRegistration::where('status', 'pending')->count(),
            'approved' => PreRegistration::where('status', 'approved')->count(),
            'rejected' => PreRegistration::where('status', 'rejected')->count(),
        ];

        return view('admin.pre-registrations.index', compact('preRegistrations', 'stats'));
    }

    /**
     * Display the specified pre-registration.
     */
    public function show(PreRegistration $preRegistration)
    {
        return view('admin.pre-registrations.show', compact('preRegistration'));
    }

    /**
     * Approve a pre-registration and create resident record.
     */
    public function approve(PreRegistration $preRegistration)
    {
        if ($preRegistration->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending registrations can be approved.');
        }

        DB::beginTransaction();

        try {
            // Create resident record
            $resident = new Resident();
            
            // Copy all data from pre-registration to resident
            $resident->type_of_resident = $preRegistration->type_of_resident;
            $resident->first_name = $preRegistration->first_name;
            $resident->middle_name = $preRegistration->middle_name;
            $resident->last_name = $preRegistration->last_name;
            $resident->suffix = $preRegistration->suffix;
            $resident->birthplace = $preRegistration->birthplace;
            $resident->birthdate = $preRegistration->birthdate;
            $resident->sex = $preRegistration->sex;
            $resident->civil_status = $preRegistration->civil_status;
            $resident->citizenship_type = $preRegistration->citizenship_type;
            $resident->citizenship_country = $preRegistration->citizenship_country;
            $resident->profession_occupation = $preRegistration->profession_occupation;
            $resident->monthly_income = $preRegistration->monthly_income;
            $resident->contact_number = $preRegistration->contact_number;
            $resident->email_address = $preRegistration->email_address;
            $resident->religion = $preRegistration->religion;
            $resident->educational_attainment = $preRegistration->educational_attainment;
            $resident->education_status = $preRegistration->education_status;
            $resident->address = $preRegistration->address;
            $resident->philsys_id = $preRegistration->philsys_id;
            $resident->population_sectors = $preRegistration->population_sectors;
            $resident->mother_first_name = $preRegistration->mother_first_name;
            $resident->mother_middle_name = $preRegistration->mother_middle_name;
            $resident->mother_last_name = $preRegistration->mother_last_name;

            // Move photos and signatures to resident directories
            if ($preRegistration->photo) {
                $resident->photo = $this->movePhotoToResident($preRegistration->photo);
            }
            
            if ($preRegistration->signature) {
                $resident->signature = $this->moveSignatureToResident($preRegistration->signature);
            }

            // Generate barangay ID and set status
            $resident->barangay_id = Resident::generateBarangayId();
            $resident->status = 'active';
            $resident->save();

            // Check if resident is a senior citizen (60 years or older)
            $age = $resident->birthdate->diffInYears(now());
            
            if ($age >= 60) {
                // Create senior citizen record
                $seniorCitizen = new SeniorCitizen();
                $seniorCitizen->resident_id = $resident->id;
                $seniorCitizen->senior_id_number = SeniorCitizen::generateSeniorIdNumber();
                $seniorCitizen->senior_id_status = 'issued';
                $seniorCitizen->senior_id_issued_at = now();
                $seniorCitizen->senior_id_expires_at = now()->addYears(5);
                
                // Add senior citizen to population sectors if not already present
                $sectors = $resident->population_sectors ?? [];
                if (!in_array('Senior Citizen', $sectors)) {
                    $sectors[] = 'Senior Citizen';
                    $resident->population_sectors = $sectors;
                    $resident->save();
                }
                
                $seniorCitizen->save();
            }

            // Update pre-registration status
            $preRegistration->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'resident_id' => $resident->id,
            ]);

            DB::commit();

            // Send approval notification with digital ID
            $this->sendDigitalId($preRegistration, $resident);

            return redirect()->route('admin.pre-registrations.index')
                ->with('success', 'Pre-registration approved successfully! Digital ID has been sent to the applicant\'s email.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()
                ->with('error', 'Error approving registration: ' . $e->getMessage());
        }
    }

    /**
     * Reject a pre-registration.
     */
    public function reject(Request $request, PreRegistration $preRegistration)
    {
        if ($preRegistration->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending registrations can be rejected.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            $preRegistration->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            // Send rejection notification
            \Illuminate\Support\Facades\Notification::route('mail', $preRegistration->email_address)
                ->notify(new PreRegistrationRejected($preRegistration));

            return redirect()->route('admin.pre-registrations.index')
                ->with('success', 'Pre-registration rejected successfully. Notification sent to applicant.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error rejecting registration: ' . $e->getMessage());
        }
    }

    /**
     * Delete a pre-registration.
     */
    public function destroy(PreRegistration $preRegistration)
    {
        try {
            // Delete associated photos
            if ($preRegistration->photo) {
                Storage::disk('public')->delete('pre-registrations/photos/' . $preRegistration->photo);
            }
            
            if ($preRegistration->signature) {
                Storage::disk('public')->delete('pre-registrations/signatures/' . $preRegistration->signature);
            }

            $preRegistration->delete();

            return redirect()->route('admin.pre-registrations.index')
                ->with('success', 'Pre-registration deleted successfully.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting registration: ' . $e->getMessage());
        }
    }

    /**
     * Move photo from pre-registration to resident directory.
     */
    private function movePhotoToResident($photoFilename)
    {
        $oldPath = 'pre-registrations/photos/' . $photoFilename;
        $newFilename = str_replace('prereg_', 'resident_', $photoFilename);
        $newPath = 'residents/photos/' . $newFilename;

        if (Storage::disk('public')->exists($oldPath)) {
            // Ensure target directory exists
            $targetDir = dirname(storage_path('app/public/' . $newPath));
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            // Move file
            Storage::disk('public')->move($oldPath, $newPath);
            return $newFilename;
        }

        return null;
    }

    /**
     * Move signature from pre-registration to resident directory.
     */
    private function moveSignatureToResident($signatureFilename)
    {
        $oldPath = 'pre-registrations/signatures/' . $signatureFilename;
        $newFilename = str_replace('prereg_sig_', 'resident_sig_', $signatureFilename);
        $newPath = 'residents/signatures/' . $newFilename;

        if (Storage::disk('public')->exists($oldPath)) {
            // Ensure target directory exists
            $targetDir = dirname(storage_path('app/public/' . $newPath));
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            // Move file
            Storage::disk('public')->move($oldPath, $newPath);
            return $newFilename;
        }

        return null;
    }

    /**
     * Send digital ID to approved applicant.
     */
    private function sendDigitalId($preRegistration, $resident)
    {
        try {
            // Check if this is a senior citizen
            $isSenior = $resident->seniorCitizen !== null;
            
            if ($isSenior) {
                // Generate senior citizen digital ID
                $qrData = json_encode([
                    'id' => $resident->seniorCitizen->senior_id_number,
                    'name' => $resident->full_name,
                    'dob' => $resident->birthdate ? $resident->birthdate->format('Y-m-d') : null,
                ]);
                
                $qrCode = \App\Facades\QrCode::generateQrCode($qrData, 300);
                
                if (strpos($qrCode, 'data:image/png;base64,') === 0) {
                    $qrCode = substr($qrCode, 22);
                }
                
                // Generate PDF for senior citizen ID
                $pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadView('admin.senior-citizens.id-for-image', [
                    'seniorCitizen' => $resident->seniorCitizen,
                    'qrCode' => $qrCode,
                ]);
                
                $pdf->setOptions([
                    'page-width' => '148mm',
                    'page-height' => '180mm',
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
                ]);
                
            } else {
                // Generate regular resident digital ID
                $qrData = json_encode([
                    'id' => $resident->barangay_id,
                    'name' => $resident->full_name,
                    'dob' => $resident->birthdate ? $resident->birthdate->format('Y-m-d') : null,
                ]);
                
                $qrCode = \App\Facades\QrCode::generateQrCode($qrData, 300);
                
                if (strpos($qrCode, 'data:image/png;base64,') === 0) {
                    $qrCode = substr($qrCode, 22);
                }
                
                // Generate PDF for regular resident ID
                $pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadView('admin.residents.id-pdf', [
                    'resident' => $resident,
                    'qrCode' => $qrCode,
                ]);
                
                $pdf->setOptions([
                    'page-width' => '148mm',
                    'page-height' => '180mm',
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
                ]);
            }
            
            // Create temporary file for PDF
            $tempPath = storage_path('app/temp');
            if (!file_exists($tempPath)) {
                mkdir($tempPath, 0755, true);
            }
            
            $idType = $isSenior ? 'senior_id' : 'resident_id';
            $pdfFileName = $idType . '_' . $resident->id . '_' . time() . '.pdf';
            $pdfPath = $tempPath . '/' . $pdfFileName;
            
            // Save PDF to temp directory
            $pdf->save($pdfPath);
            
            // Send notification with PDF attached
            \Illuminate\Support\Facades\Notification::route('mail', $preRegistration->email_address)
                ->notify(new PreRegistrationApproved($preRegistration, $resident, $pdfPath, $isSenior));
            
            // Clean up temp file after a delay
            \Illuminate\Support\Facades\Queue::later(now()->addMinutes(5), function () use ($pdfPath) {
                if (file_exists($pdfPath)) {
                    unlink($pdfPath);
                }
            });
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error sending digital ID: ' . $e->getMessage());
        }
    }
}
