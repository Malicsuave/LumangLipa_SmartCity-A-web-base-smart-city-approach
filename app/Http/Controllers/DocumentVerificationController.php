<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentRequest;
use Carbon\Carbon;

class DocumentVerificationController extends Controller
{
    public function show($uuid)
    {
        $document = DocumentRequest::with('resident')->where('uuid', $uuid)->first();
        if (!$document) {
            return view('public.verify-document', [
                'valid' => false,
                'message' => 'Document not found or invalid.',
            ]);
        }
        $expired = false;
        $expiration = null;
        if ($document->approved_at) {
            // 3 months validity
            $expiration = Carbon::parse($document->approved_at)->addMonths(3);
            $expired = now()->greaterThan($expiration);
        }
        return view('public.verify-document', [
            'valid' => !$expired,
            'document' => $document,
            'resident' => $document->resident,
            'expiration' => $expiration,
        ]);
    }
} 