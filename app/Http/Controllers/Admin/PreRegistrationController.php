<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreRegistration;
use App\Models\SeniorPreRegistration;
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
        // Get both regular and senior registrations
        $regularQuery = PreRegistration::query();
        $seniorQuery = SeniorPreRegistration::query();

        // Apply filters to both queries
        $this->applyFilters($regularQuery, $request);
        $this->applyFilters($seniorQuery, $request);

        // Get the data from both queries
        $regularRegistrations = $regularQuery->get()->map(function ($registration) {
            $registration->is_senior = false;
            $registration->registration_type = 'Regular';
            return $registration;
        });

        $seniorRegistrations = $seniorQuery->get()->map(function ($registration) {
            $registration->is_senior = true;
            $registration->registration_type = 'Senior Citizen';
            return $registration;
        });

        // Combine and sort the collections
        $allRegistrations = $regularRegistrations->concat($seniorRegistrations);
        
        // Apply sorting
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        if ($sortField === 'age') {
            // Sort by birthdate in reverse order for age
            $allRegistrations = $allRegistrations->sortBy(function ($registration) {
                return $registration->birthdate;
            }, SORT_REGULAR, $sortDirection === 'desc');
        } else {
            $allRegistrations = $allRegistrations->sortBy($sortField, SORT_REGULAR, $sortDirection === 'desc');
        }

        // Manual pagination since we're combining collections
        $perPage = 20;
        $currentPage = $request->get('page', 1);
        $currentItems = $allRegistrations->slice(($currentPage - 1) * $perPage, $perPage)->values();
        
        $preRegistrations = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $allRegistrations->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Get combined statistics
        $stats = [
            'total' => PreRegistration::count() + SeniorPreRegistration::count(),
            'pending' => PreRegistration::where('status', 'pending')->count() + SeniorPreRegistration::where('status', 'pending')->count(),
            'approved' => PreRegistration::where('status', 'approved')->count() + SeniorPreRegistration::where('status', 'approved')->count(),
            'rejected' => PreRegistration::where('status', 'rejected')->count() + SeniorPreRegistration::where('status', 'rejected')->count(),
        ];

        return view('admin.pre-registrations.index', compact('preRegistrations', 'stats'));
    }

    /**
     * Apply filters to a query builder
     */
    private function applyFilters($query, Request $request)
    {
        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by resident type
        if ($request->has('type_of_resident') && !empty($request->type_of_resident)) {
            $query->where('type_of_resident', $request->type_of_resident);
        }
        
        // Filter by gender
        if ($request->has('gender') && !empty($request->gender)) {
            $query->where('sex', $request->gender);
        }
        
        // Filter by civil status
        if ($request->has('civil_status') && !empty($request->civil_status)) {
            $query->where('civil_status', $request->civil_status);
        }
        
        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email_address', 'like', "%{$search}%")
                  ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }
    }

    /**
     * Display the specified pre-registration.
     */
    public function show(PreRegistration $preRegistration)
    {
        return view('admin.pre-registrations.show', compact('preRegistration'));
    }

    /**
     * Display the specified senior pre-registration.
     */
    public function showSenior(SeniorPreRegistration $seniorPreRegistration)
    {
        // Use the same variable name as the view expects
        $preRegistration = $seniorPreRegistration;
        return view('admin.pre-registrations.show-senior', compact('preRegistration'));
    }

    /**
     * Approve a pre-registration and create resident record.
     */
    public function approve(PreRegistration $preRegistration)
    {
        // Add debugging
        \Log::info('Approve method called', [
            'registration_id' => $preRegistration->id,
            'current_status' => $preRegistration->status,
            'user_id' => auth()->id()
        ]);

        if ($preRegistration->status !== 'pending') {
            \Log::warning('Attempted to approve non-pending registration', [
                'registration_id' => $preRegistration->id,
                'status' => $preRegistration->status
            ]);
            return redirect()->back()
                ->with('error', 'Only pending registrations can be approved.');
        }

        DB::beginTransaction();

        try {
            \Log::info('Starting approval process', ['registration_id' => $preRegistration->id]);
            
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
            
            // Map educational attainment to enum values
            $educationMapping = [
                'Elementary Level' => 'Elementary',
                'Elementary Graduate' => 'Elementary',
                'High School Level' => 'Highschool',
                'High School Graduate' => 'Highschool',
                'College Level' => 'College',
                'College Graduate' => 'College',
                'Post Graduate' => 'Post Graduate',
                'Vocational' => 'Vocational',
                'Not Applicable' => 'not applicable'
            ];
            $resident->educational_attainment = $educationMapping[$preRegistration->educational_attainment] ?? 'College';
            
            // Map education status to enum values
            $educationStatusMapping = [
                'Studying' => 'Undergraduate',
                'Graduated' => 'Graduate',
                'Stopped Schooling' => 'Undergraduate',
                'Not Applicable' => 'not applicable'
            ];
            $resident->education_status = $educationStatusMapping[$preRegistration->education_status] ?? 'Graduate';
            $resident->address = $preRegistration->address;
            $resident->philsys_id = $preRegistration->philsys_id;
            $resident->population_sectors = $preRegistration->population_sectors;
            $resident->mother_first_name = $preRegistration->mother_first_name;
            $resident->mother_middle_name = $preRegistration->mother_middle_name;
            $resident->mother_last_name = $preRegistration->mother_last_name;

            // Move photos and signatures to resident directories
            \Log::info('Processing photo and signature files', [
                'has_photo' => !empty($preRegistration->photo),
                'has_signature' => !empty($preRegistration->signature),
                'photo_filename' => $preRegistration->photo,
                'signature_filename' => $preRegistration->signature
            ]);
            
            if ($preRegistration->photo) {
                $resident->photo = $this->movePhotoToResident($preRegistration->photo);
                \Log::info('Photo moved', ['new_filename' => $resident->photo]);
            }
            
            if ($preRegistration->signature) {
                $resident->signature = $this->moveSignatureToResident($preRegistration->signature);
                \Log::info('Signature moved', ['new_filename' => $resident->signature]);
            }

            // Generate barangay ID and set status
            $resident->barangay_id = Resident::generateBarangayId();
            $resident->status = 'active';
            
            \Log::info('About to save resident record', ['registration_id' => $preRegistration->id]);
            $resident->save();
            \Log::info('Resident record saved successfully', ['resident_id' => $resident->id]);

            // Check if resident is a senior citizen (60 years or older)
            $age = Carbon::parse($resident->birthdate)->diffInYears(now());
            \Log::info('Checking age for senior citizen status', ['age' => $age]);
            
            if ($age >= 60) {
                \Log::info('Creating independent senior citizen record');
                // Create independent senior citizen record with all resident data
                $seniorCitizen = SeniorCitizen::create([
                    // Copy resident data to senior citizen (since they're now independent)
                    'type_of_resident' => $resident->type_of_resident,
                    'first_name' => $resident->first_name,
                    'middle_name' => $resident->middle_name,
                    'last_name' => $resident->last_name,
                    'suffix' => $resident->suffix,
                    'birthdate' => $resident->birthdate,
                    'birthplace' => $resident->birthplace,
                    'sex' => $resident->sex,
                    'civil_status' => $resident->civil_status,
                    'citizenship_type' => $resident->citizenship_type,
                    'citizenship_country' => $resident->citizenship_country,
                    'educational_attainment' => $resident->educational_attainment,
                    'religion' => $resident->religion,
                    'profession_occupation' => $resident->profession_occupation,
                    'contact_number' => $resident->contact_number,
                    'email_address' => $resident->email_address,
                    'current_address' => $resident->current_address ?? $resident->address,
                    'photo' => $resident->photo,
                    'signature' => $resident->signature,
                    'senior_id_number' => SeniorCitizen::generateSeniorIdNumber(),
                    'senior_id_status' => 'issued',
                    'senior_id_issued_at' => now(),
                    'senior_id_expires_at' => now()->addYears(5),
                ]);
                
                \Log::info('Senior citizen record created', [
                    'senior_id' => $seniorCitizen->id,
                    'senior_id_number' => $seniorCitizen->senior_id_number,
                    'senior_id_status' => $seniorCitizen->senior_id_status
                ]);
                
                // Add senior citizen to population sectors if not already present
                $sectors = $resident->population_sectors ?? [];
                
                // Ensure $sectors is an array (in case it's a JSON string)
                if (!is_array($sectors)) {
                    $sectors = json_decode($sectors, true) ?? [];
                }
                
                if (!in_array('Senior Citizen', $sectors)) {
                    $sectors[] = 'Senior Citizen';
                    $resident->population_sectors = $sectors;
                    $resident->save();
                }
            }

            // Update pre-registration status
            \Log::info('Updating pre-registration status');
            $preRegistration->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'resident_id' => $resident->id,
            ]);
            \Log::info('Pre-registration status updated successfully');

            DB::commit();
            \Log::info('Database transaction committed successfully');

            // Send approval notification with digital ID
            \Log::info('About to send digital ID');
            $this->sendDigitalId($preRegistration, $resident);
            \Log::info('Digital ID sending process completed');

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

        \Log::info('Moving photo', [
            'old_path' => $oldPath,
            'new_path' => $newPath,
            'old_filename' => $photoFilename,
            'new_filename' => $newFilename,
            'file_exists' => Storage::disk('public')->exists($oldPath)
        ]);

        if (Storage::disk('public')->exists($oldPath)) {
            // Ensure target directory exists
            $targetDir = dirname(storage_path('app/public/' . $newPath));
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
                \Log::info('Created target directory', ['target_dir' => $targetDir]);
            }

            // Copy file instead of move to preserve original
            try {
                Storage::disk('public')->copy($oldPath, $newPath);
                \Log::info('Photo copied successfully', ['from' => $oldPath, 'to' => $newPath]);
                return $newFilename;
            } catch (\Exception $e) {
                \Log::error('Failed to copy photo', ['error' => $e->getMessage()]);
                return null;
            }
        } else {
            \Log::warning('Photo file not found', ['path' => $oldPath]);
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

        \Log::info('Moving signature', [
            'old_path' => $oldPath,
            'new_path' => $newPath,
            'old_filename' => $signatureFilename,
            'new_filename' => $newFilename,
            'file_exists' => Storage::disk('public')->exists($oldPath)
        ]);

        if (Storage::disk('public')->exists($oldPath)) {
            // Ensure target directory exists
            $targetDir = dirname(storage_path('app/public/' . $newPath));
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
                \Log::info('Created target directory', ['target_dir' => $targetDir]);
            }

            // Copy file instead of move to preserve original
            try {
                Storage::disk('public')->copy($oldPath, $newPath);
                \Log::info('Signature copied successfully', ['from' => $oldPath, 'to' => $newPath]);
                return $newFilename;
            } catch (\Exception $e) {
                \Log::error('Failed to copy signature', ['error' => $e->getMessage()]);
                return null;
            }
        } else {
            \Log::warning('Signature file not found', ['path' => $oldPath]);
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

    /**
     * Approve a senior pre-registration and create senior citizen record.
     */
    public function approveSenior(SeniorPreRegistration $seniorPreRegistration)
    {
        if ($seniorPreRegistration->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending registrations can be approved.');
        }

        DB::beginTransaction();

        try {
            // Create senior citizen record directly from senior pre-registration
            $seniorCitizen = new SeniorCitizen();
            
            // Copy data from senior pre-registration
            $seniorCitizen->type_of_resident = $seniorPreRegistration->type_of_resident;
            $seniorCitizen->first_name = $seniorPreRegistration->first_name;
            $seniorCitizen->middle_name = $seniorPreRegistration->middle_name;
            $seniorCitizen->last_name = $seniorPreRegistration->last_name;
            $seniorCitizen->suffix = $seniorPreRegistration->suffix;
            $seniorCitizen->birthdate = $seniorPreRegistration->birthdate;
            $seniorCitizen->birthplace = $seniorPreRegistration->birthplace;
            $seniorCitizen->sex = $seniorPreRegistration->sex;
            $seniorCitizen->civil_status = $seniorPreRegistration->civil_status;
            $seniorCitizen->contact_number = $seniorPreRegistration->contact_number;
            $seniorCitizen->email_address = $seniorPreRegistration->email_address;
            $seniorCitizen->address = $seniorPreRegistration->address;
            $seniorCitizen->emergency_contact_name = $seniorPreRegistration->emergency_contact_name;
            $seniorCitizen->emergency_contact_relationship = $seniorPreRegistration->emergency_contact_relationship;
            $seniorCitizen->emergency_contact_number = $seniorPreRegistration->emergency_contact_number;
            $seniorCitizen->emergency_contact_address = $seniorPreRegistration->emergency_contact_address;
            
            // Senior-specific fields
            $seniorCitizen->health_condition = $seniorPreRegistration->health_condition;
            $seniorCitizen->mobility_status = $seniorPreRegistration->mobility_status;
            $seniorCitizen->medical_conditions = $seniorPreRegistration->medical_conditions;
            $seniorCitizen->receiving_pension = $seniorPreRegistration->receiving_pension;
            $seniorCitizen->pension_type = $seniorPreRegistration->pension_type;
            $seniorCitizen->pension_amount = $seniorPreRegistration->pension_amount;
            $seniorCitizen->has_philhealth = $seniorPreRegistration->has_philhealth;
            $seniorCitizen->philhealth_number = $seniorPreRegistration->philhealth_number;
            $seniorCitizen->has_senior_discount_card = $seniorPreRegistration->has_senior_discount_card;
            $seniorCitizen->services = $seniorPreRegistration->services;

            // System fields
            $seniorCitizen->status = 'active';
            $seniorCitizen->approved_at = now();
            $seniorCitizen->approved_by = auth()->id();
            
            $seniorCitizen->save();

            // Update senior pre-registration status
            $seniorPreRegistration->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'senior_citizen_id' => $seniorCitizen->id,
            ]);

            DB::commit();

            // Send approval notification
            \Illuminate\Support\Facades\Notification::route('mail', $seniorPreRegistration->email_address)
                ->notify(new PreRegistrationApproved($seniorPreRegistration, $seniorCitizen, null, true));

            return redirect()->route('admin.pre-registrations.index')
                ->with('success', 'Senior citizen pre-registration approved successfully. Senior citizen record created and notification sent.');

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error approving senior registration: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Error approving registration: ' . $e->getMessage());
        }
    }

    /**
     * Reject a senior pre-registration.
     */
    public function rejectSenior(Request $request, SeniorPreRegistration $seniorPreRegistration)
    {
        if ($seniorPreRegistration->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending registrations can be rejected.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            $seniorPreRegistration->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            // Send rejection notification
            \Illuminate\Support\Facades\Notification::route('mail', $seniorPreRegistration->email_address)
                ->notify(new PreRegistrationRejected($seniorPreRegistration));

            return redirect()->route('admin.pre-registrations.index')
                ->with('success', 'Senior citizen pre-registration rejected successfully. Notification sent to applicant.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error rejecting registration: ' . $e->getMessage());
        }
    }
}
