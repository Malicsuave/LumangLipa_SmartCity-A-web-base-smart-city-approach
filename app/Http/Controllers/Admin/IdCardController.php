<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resident;
use App\Models\SeniorCitizen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;

class IdCardController extends Controller
{
    /**
     * Display ID management dashboard
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Stats for ID dashboard
        $stats = [
            'total_ids' => Resident::whereNotNull('id_status')->count(),
            'issued_ids' => Resident::where('id_status', 'issued')->count(),
            'pending_ids' => Resident::where('id_status', 'pending')->count(),
            'renewal_ids' => Resident::where('id_status', 'needs_renewal')->count(),
            'senior_ids' => SeniorCitizen::where('senior_id_status', 'issued')->count(),
        ];
        
        // Get residents with pending IDs
        $pendingIds = Resident::where('id_status', 'pending')
            ->orderBy('created_at', 'asc')
            ->paginate(10);
        
        return view('admin.id-cards.index', compact('stats', 'pendingIds'));
    }
    
    /**
     * Show the form for generating a new ID card
     * 
     * @param int $residentId
     * @return \Illuminate\View\View
     */
    public function create($residentId)
    {
        $resident = Resident::with('seniorCitizen')->findOrFail($residentId);
        
        return view('admin.id-cards.create', compact('resident'));
    }
    
    /**
     * Generate and store an ID card for a resident
     * 
     * @param Request $request
     * @param int $residentId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $residentId)
    {
        $request->validate([
            'photo' => 'nullable|image|max:2048',
            'signature' => 'nullable|image|max:2048',
        ]);
        
        $resident = Resident::findOrFail($residentId);
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('residents/photos', 'public');
            $resident->photo = basename($photoPath);
        }
        
        // Handle signature upload
        if ($request->hasFile('signature')) {
            $signaturePath = $request->file('signature')->store('residents/signatures', 'public');
            $resident->signature = basename($signaturePath);
        }
        
        // Set ID status and dates
        $resident->id_status = 'issued';
        $resident->id_issued_at = now();
        $resident->id_expires_at = now()->addYears(3); // Valid for 3 years
        
        $resident->save();
        
        // Check if this is a senior citizen (60+ years old)
        if ($resident->isSeniorCitizen) {
            // Check if senior record exists, if not create it
            if (!$resident->seniorCitizen) {
                $senior = new SeniorCitizen();
                $senior->resident_id = $resident->id;
                $senior->senior_id_number = SeniorCitizen::generateSeniorIdNumber();
                $senior->senior_id_issued_at = now();
                $senior->senior_id_expires_at = now()->addYears(5); // Senior IDs valid for 5 years
                $senior->senior_id_status = 'issued';
                $senior->save();
            } else {
                // Update existing senior record with new ID format
                if (!$resident->seniorCitizen->senior_id_number || strpos($resident->seniorCitizen->senior_id_number, 'SC-LUM-') !== false) {
                    $resident->seniorCitizen->senior_id_number = SeniorCitizen::generateSeniorIdNumber();
                }
                $resident->seniorCitizen->senior_id_status = 'issued';
                $resident->seniorCitizen->senior_id_issued_at = now();
                $resident->seniorCitizen->senior_id_expires_at = now()->addYears(5);
                $resident->seniorCitizen->save();
            }
        }
        
        return redirect()->route('admin.id-cards.show', $resident->id)
            ->with('success', 'ID card generated successfully!');
    }
    
    /**
     * Display the ID card for a resident
     * 
     * @param int $residentId
     * @return \Illuminate\View\View
     */
    public function show($residentId)
    {
        $resident = Resident::with('seniorCitizen')->findOrFail($residentId);
        
        if ($resident->id_status !== 'issued') {
            return redirect()->route('admin.id-cards.create', $resident->id)
                ->with('error', 'This resident does not have an issued ID card yet.');
        }
        
        return view('admin.id-cards.show', compact('resident'));
    }
    
    /**
     * Generate a PDF version of the ID card
     * 
     * @param int $residentId
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function generatePdf($residentId)
    {
        $resident = Resident::with('seniorCitizen', 'household')->findOrFail($residentId);
        
        if ($resident->id_status !== 'issued') {
            return redirect()->route('admin.id-cards.create', $resident->id)
                ->with('error', 'This resident does not have an issued ID card yet.');
        }
        
        // Generate QR code
        $qrCode = base64_encode(QrCode::format('png')
            ->size(200)
            ->generate($resident->barangay_id));
        
        // Determine if this is a senior citizen ID
        $isSenior = $resident->isSeniorCitizen && $resident->seniorCitizen;
        
        // Get the appropriate view based on whether this is a senior ID
        $view = $isSenior ? 'admin.residents.senior-citizen-id-pdf' : 'admin.residents.id-pdf';
        
        // Generate PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, [
            'resident' => $resident,
            'qrCode' => $qrCode
        ]);
        
        // Set PDF options
        $pdf->setPaper([0, 0, 243, 310], 'portrait');
        $pdf->setOption('margin-bottom', 0);
        $pdf->setOption('margin-top', 0);
        $pdf->setOption('margin-right', 0);
        $pdf->setOption('margin-left', 0);
        
        // Generate filename
        $filename = ($isSenior ? 'SENIOR_' : 'ID_') . $resident->last_name . '_' . $resident->first_name . '.pdf';
        
        // Download the PDF
        return $pdf->download($filename);
    }
    
    /**
     * Mark an ID card as needing renewal
     * 
     * @param int $residentId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markForRenewal($residentId)
    {
        $resident = Resident::findOrFail($residentId);
        $resident->id_status = 'needs_renewal';
        $resident->save();
        
        // If senior citizen, also mark senior ID
        if ($resident->seniorCitizen) {
            $resident->seniorCitizen->senior_id_status = 'needs_renewal';
            $resident->seniorCitizen->save();
        }
        
        return redirect()->route('admin.id-cards.index')
            ->with('success', 'ID card marked for renewal.');
    }
}