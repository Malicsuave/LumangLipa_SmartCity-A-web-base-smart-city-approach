<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeniorCitizen;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SeniorCitizenController extends Controller
{
    /**
     * Display a listing of senior citizens.
     */
    public function index(Request $request)
    {
        $query = SeniorCitizen::with(['resident' => function($q) {
            $q->where('status', 'active');
        }])->whereHas('resident', function($q) {
            $q->where('status', 'active');
        });

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('resident', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('middle_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('barangay_id', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%");
            })->orWhere('senior_id_number', 'like', "%{$search}%");
        }

        // Order by resident's last name
        $query->join('residents', 'senior_citizens.resident_id', '=', 'residents.id')
              ->orderBy('residents.last_name', 'asc')
              ->select('senior_citizens.*');

        $seniorCitizens = $query->paginate(15);
        $seniorCitizens->appends($request->query());

        return view('admin.senior-citizens.index', compact('seniorCitizens'));
    }

    /**
     * Show the form for editing the specified senior citizen.
     */
    public function edit(SeniorCitizen $seniorCitizen)
    {
        $seniorCitizen->load('resident');
        return view('admin.senior-citizens.edit', compact('seniorCitizen'));
    }

    /**
     * Update the specified senior citizen in storage.
     */
    public function update(Request $request, SeniorCitizen $seniorCitizen)
    {
        $maxBirthYear = now()->subYears(120)->year; // Max age 120 years
        $today = now()->format('Y-m-d');

        $validated = $request->validate([
            // ID information with enhanced validation
            'senior_id_number' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9\-]+$/', // Only alphanumeric characters and hyphens
                'unique:senior_citizens,senior_id_number,' . $seniorCitizen->id
            ],
            'senior_id_expires_at' => [
                'nullable', 
                'date', 
                'after_or_equal:today'
            ],

            // Health Information with sanitization and validation
            'health_conditions' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (preg_match('/<script\b[^>]*>.*<\/script>/is', $value)) {
                        $fail('Health conditions cannot contain script tags.');
                    }
                }
            ],
            'medications' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (preg_match('/<script\b[^>]*>.*<\/script>/is', $value)) {
                        $fail('Medications cannot contain script tags.');
                    }
                }
            ],
            'allergies' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (preg_match('/<script\b[^>]*>.*<\/script>/is', $value)) {
                        $fail('Allergies cannot contain script tags.');
                    }
                }
            ],
            'blood_type' => [
                'nullable', 
                'string', 
                'max:10',
                'regex:/^(A|B|AB|O)[+-]?$/'
            ],
            'living_arrangement' => [
                'nullable',
                'string',
                'min:3',
                'max:100',
                'regex:/^[A-Za-z0-9\s\.,\-\']+$/' // Letters, numbers, spaces, commas, dots, hyphens, apostrophes
            ],

            // Emergency Contact with enhanced validation
            'emergency_contact_name' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[A-Za-z\s\.\-\']+$/' // Only letters, spaces, dots, hyphens, and apostrophes
            ],
            'emergency_contact_number' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/' // Phone number format
            ],
            'emergency_contact_relationship' => [
                'nullable',
                'string',
                'max:100',
                'regex:/^[A-Za-z\s\-]+$/' // Only letters, spaces, and hyphens
            ],

            // Benefits information with enhanced numeric validation
            'receiving_pension' => 'nullable|boolean',
            'pension_type' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[A-Za-z0-9\s\-\.]+$/' // Only letters, numbers, spaces, hyphens, and dots
            ],
            'pension_amount' => [
                'nullable',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/' // Ensure only numeric with up to 2 decimal places
            ],
            'has_philhealth' => 'nullable|boolean',
            'philhealth_number' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9\-]+$/' // Only numbers and hyphens
            ],
            'has_senior_discount_card' => 'nullable|boolean',
        ], [
            // Custom error messages
            'senior_id_number.regex' => 'Senior ID number can only contain letters, numbers, and hyphens.',
            'blood_type.regex' => 'Blood type must be a valid format (A+, B-, AB+, O-, etc.).',
            'living_arrangement.min' => 'Living arrangement must be at least 3 characters.',
            'living_arrangement.regex' => 'Living arrangement can only contain letters, numbers, spaces, commas, dots, hyphens, and apostrophes.',
            'emergency_contact_name.regex' => 'Emergency contact name can only contain letters, spaces, dots, hyphens, and apostrophes.',
            'emergency_contact_number.regex' => 'Please enter a valid phone number format.',
            'emergency_contact_relationship.regex' => 'Relationship can only contain letters, spaces, and hyphens.',
            'pension_type.regex' => 'Pension type can only contain letters, numbers, spaces, hyphens, and dots.',
            'pension_amount.min' => 'Pension amount cannot be negative.',
            'pension_amount.regex' => 'Pension amount must be a valid number with up to 2 decimal places.',
            'philhealth_number.regex' => 'PhilHealth number can only contain numbers and hyphens.',
        ]);

        // Cross-field validation
        if ($request->has_philhealth && empty($request->philhealth_number)) {
            return redirect()->back()
                ->withErrors(['philhealth_number' => 'PhilHealth number is required when PhilHealth is checked.'])
                ->withInput();
        }

        if ($request->receiving_pension) {
            if (empty($request->pension_type)) {
                return redirect()->back()
                    ->withErrors(['pension_type' => 'Pension type is required when receiving pension is checked.'])
                    ->withInput();
            }
            
            if (is_null($request->pension_amount)) {
                return redirect()->back()
                    ->withErrors(['pension_amount' => 'Pension amount is required when receiving pension is checked.'])
                    ->withInput();
            }
        }

        // Ensure emergency contact information consistency
        if (!empty($request->emergency_contact_name) && empty($request->emergency_contact_number)) {
            return redirect()->back()
                ->withErrors(['emergency_contact_number' => 'Emergency contact number is required when contact name is provided.'])
                ->withInput();
        }

        if (empty($request->emergency_contact_name) && !empty($request->emergency_contact_number)) {
            return redirect()->back()
                ->withErrors(['emergency_contact_name' => 'Emergency contact name is required when contact number is provided.'])
                ->withInput();
        }

        try {
            $seniorCitizen->update($validated);

            return redirect()->route('admin.senior-citizens.index')
                ->with('success', 'Senior citizen information updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating senior citizen information: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display senior citizen statistics dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_seniors' => SeniorCitizen::whereHas('resident', function($q) {
                $q->where('status', 'active');
            })->count(),
            'issued_ids' => SeniorCitizen::where('senior_id_status', 'issued')->count(),
            'pending_ids' => SeniorCitizen::where('senior_id_status', 'not_issued')->count(),
            'expiring_soon' => SeniorCitizen::where('senior_id_expires_at', '<=', now()->addMonths(3))
                ->where('senior_id_expires_at', '>', now())
                ->count(),
            'with_pension' => SeniorCitizen::where('receiving_pension', true)->count(),
            'with_philhealth' => SeniorCitizen::where('has_philhealth', true)->count(),
        ];

        return view('admin.senior-citizens.dashboard', compact('stats'));
    }

    /**
     * Issue senior citizen ID.
     */
    public function issueId(SeniorCitizen $seniorCitizen)
    {
        if (!$seniorCitizen->senior_id_number) {
            $seniorCitizen->senior_id_number = SeniorCitizen::generateSeniorIdNumber();
        }

        $seniorCitizen->update([
            'senior_id_status' => 'issued',
            'senior_id_issued_at' => now(),
            'senior_id_expires_at' => now()->addYears(5),
        ]);

        return redirect()->back()
            ->with('success', 'Senior citizen ID issued successfully!');
    }

    /**
     * Mark senior citizen ID for renewal.
     */
    public function markForRenewal(SeniorCitizen $seniorCitizen)
    {
        $seniorCitizen->update([
            'senior_id_status' => 'needs_renewal',
        ]);

        return redirect()->back()
            ->with('success', 'Senior citizen ID marked for renewal successfully!');
    }

    /**
     * Revoke senior citizen ID.
     */
    public function revokeId(SeniorCitizen $seniorCitizen)
    {
        $seniorCitizen->update([
            'senior_id_status' => 'not_issued',
            'senior_id_issued_at' => null,
            'senior_id_expires_at' => null,
        ]);

        // Log the activity
        \Spatie\Activitylog\Facades\Activity::causedBy(auth()->user())
            ->performedOn($seniorCitizen)
            ->log('revoked senior citizen ID');

        return redirect()->back()
            ->with('success', 'Senior citizen ID has been revoked successfully!');
    }

    /**
     * Preview Senior Citizen ID card.
     */
    public function previewId(SeniorCitizen $seniorCitizen)
    {
        $seniorCitizen->load('resident');
        
        // Check if senior citizen has an issued ID
        if ($seniorCitizen->senior_id_status !== 'issued') {
            return redirect()->back()
                ->with('error', 'This senior citizen does not have an issued ID card yet.');
        }

        // Generate QR code data (contains senior ID, name and DOB like regular resident IDs)
        $qrData = json_encode([
            'id' => $seniorCitizen->senior_id_number,
            'name' => $seniorCitizen->resident->full_name,
            'dob' => $seniorCitizen->resident->birthdate ? $seniorCitizen->resident->birthdate->format('Y-m-d') : null,
        ]);
        
        // Generate QR code using the application's custom QR code facade
        $qrCode = \App\Facades\QrCode::generateQrCode($qrData, 200);
        
        // If the QR code already has the data URI prefix, extract just the base64 part
        if (strpos($qrCode, 'data:image/png;base64,') === 0) {
            $qrCode = substr($qrCode, 22); // Remove the prefix
        }

        return view('admin.senior-citizens.id-preview', compact('seniorCitizen', 'qrCode'));
    }

    /**
     * Generate Senior Citizen ID card by converting HTML to image first, then to PDF.
     */
    public function downloadId(SeniorCitizen $seniorCitizen)
    {
        $seniorCitizen->load('resident');
        
        // Check if senior citizen has an issued ID
        if ($seniorCitizen->senior_id_status !== 'issued') {
            return redirect()->back()
                ->with('error', 'This senior citizen does not have an issued ID card yet.');
        }

        // Generate QR code data
        $qrData = json_encode([
            'id' => $seniorCitizen->senior_id_number,
            'name' => $seniorCitizen->resident->full_name,
            'dob' => $seniorCitizen->resident->birthdate ? $seniorCitizen->resident->birthdate->format('Y-m-d') : null,
        ]);
        
        $qrCode = \App\Facades\QrCode::generateQrCode($qrData, 200);
        
        if (strpos($qrCode, 'data:image/png;base64,') === 0) {
            $qrCode = substr($qrCode, 22);
        }

        try {
            // Generate PDF using the template that exactly matches the preview
            $pdf = \Barryvdh\Snappy\Facades\SnappyPdf::loadView('admin.senior-citizens.id-for-image', [
                'seniorCitizen' => $seniorCitizen,
                'qrCode' => $qrCode
            ]);
            
            // Set PDF options for custom shorter paper size
            $pdf->setOptions([
                'page-width' => '148mm',    // Custom width
                'page-height' => '180mm',   // Custom shorter height
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

            $filename = 'SENIOR_ID_' . $seniorCitizen->resident->last_name . '_' . $seniorCitizen->resident->first_name . '.pdf';

            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error generating senior ID PDF: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error generating ID card: ' . $e->getMessage());
        }
    }

    /**
     * Show the ID management page for a senior citizen.
     *
     * @param SeniorCitizen $seniorCitizen
     * @return \Illuminate\View\View
     */
    public function showIdManagement(SeniorCitizen $seniorCitizen)
    {
        $seniorCitizen->load('resident');
        return view('admin.senior-citizens.id-management', compact('seniorCitizen'));
    }

    /**
     * Upload a photo for a senior citizen's ID.
     *
     * @param Request $request
     * @param SeniorCitizen $seniorCitizen
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadPhoto(Request $request, SeniorCitizen $seniorCitizen)
    {
        $request->validate([
            'photo' => 'required|image|max:5000',
        ]);

        try {
            $resident = $seniorCitizen->resident;

            // Delete old photo if exists
            if ($resident->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('residents/photos/' . $resident->photo);
            }

            // Process and save the new photo
            $image = \Intervention\Image\Facades\Image::make($request->file('photo'));
            
            // Resize to a standard ID photo size (2x2 inches at 300dpi = 600x600 pixels)
            $image->fit(600, 600);
            
            // Generate unique filename
            $filename = $resident->barangay_id . '_' . \Illuminate\Support\Str::random(10) . '.jpg';
            
            // Save the processed image
            $path = storage_path('app/public/residents/photos/' . $filename);
            
            // Ensure directory exists
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }
            
            // Save image with compression
            $image->save($path, 80);
            
            // Update the resident record
            $resident->photo = $filename;
            $resident->save();
            
            // Log the activity
            \Spatie\Activitylog\Facades\Activity::causedBy(auth()->user())
                ->performedOn($resident)
                ->withProperties(['photo' => $filename])
                ->log('uploaded photo for senior citizen ID');

            return redirect()->route('admin.senior-citizens.id-management', $seniorCitizen)
                ->with('success', 'Photo uploaded successfully!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error uploading photo: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error uploading photo: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Upload a signature for a senior citizen's ID.
     *
     * @param Request $request
     * @param SeniorCitizen $seniorCitizen
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadSignature(Request $request, SeniorCitizen $seniorCitizen)
    {
        $request->validate([
            'signature' => 'required|image|max:2000',
        ]);

        try {
            $resident = $seniorCitizen->resident;

            // Delete old signature if exists
            if ($resident->signature) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete('residents/signatures/' . $resident->signature);
            }

            // Process and save the new signature
            $image = \Intervention\Image\Facades\Image::make($request->file('signature'));
            
            // Resize signature to standard size
            $image->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            // Generate unique filename
            $filename = $resident->barangay_id . '_sig_' . \Illuminate\Support\Str::random(10) . '.png';
            
            // Save the processed image
            $path = storage_path('app/public/residents/signatures/' . $filename);
            
            // Ensure directory exists
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0755, true);
            }
            
            // Save image with compression
            $image->save($path, 80);
            
            // Update the resident record
            $resident->signature = $filename;
            $resident->save();
            
            // Log the activity
            \Spatie\Activitylog\Facades\Activity::causedBy(auth()->user())
                ->performedOn($resident)
                ->withProperties(['signature' => $filename])
                ->log('uploaded signature for senior citizen ID');

            return redirect()->route('admin.senior-citizens.id-management', $seniorCitizen)
                ->with('success', 'Signature uploaded successfully!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error uploading signature: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error uploading signature: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Update senior citizen ID information.
     *
     * @param Request $request
     * @param SeniorCitizen $seniorCitizen
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateIdInfo(Request $request, SeniorCitizen $seniorCitizen)
    {
        $request->validate([
            'senior_id_number' => 'nullable|string|max:50|unique:senior_citizens,senior_id_number,' . $seniorCitizen->id,
            'senior_issue_id' => 'nullable|string|max:50',
            'senior_id_expires_at' => 'nullable|date|after_or_equal:today',
        ]);

        try {
            $seniorCitizen->update([
                'senior_id_number' => $request->senior_id_number,
                'senior_issue_id' => $request->senior_issue_id,
                'senior_id_expires_at' => $request->senior_id_expires_at,
            ]);

            // Log the activity
            \Spatie\Activitylog\Facades\Activity::causedBy(auth()->user())
                ->performedOn($seniorCitizen)
                ->withProperties([
                    'senior_id_number' => $request->senior_id_number,
                    'senior_issue_id' => $request->senior_issue_id,
                    'senior_id_expires_at' => $request->senior_id_expires_at,
                ])
                ->log('updated senior citizen ID information');

            return redirect()->route('admin.senior-citizens.id-management', $seniorCitizen)
                ->with('success', 'Senior citizen ID information updated successfully!');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error updating senior citizen ID info: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error updating ID information: ' . $e->getMessage())
                ->withInput();
        }
    }
}