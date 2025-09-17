# Backend Requirements for Document Request Modal System

## Required Routes
Add these routes to your `routes/web.php` file:

```php
// Document Request API endpoints
Route::prefix('admin/documents')->group(function () {
    Route::get('{id}/details', [DocumentController::class, 'getDetails'])->name('admin.documents.details');
    Route::get('{id}/preview', [DocumentController::class, 'preview'])->name('admin.documents.preview');
    Route::get('{id}/print', [DocumentController::class, 'print'])->name('admin.documents.print');
});
```

## Required Controller Methods
Add these methods to your `DocumentController` (or create the controller if it doesn't exist):

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DocumentRequest; // Adjust based on your model name

class DocumentController extends Controller
{
    public function getDetails($id)
    {
        try {
            $document = DocumentRequest::with('resident')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'request_id' => $document->id,
                    'document_type' => $document->document_type,
                    'status' => $document->status,
                    'date_requested' => $document->created_at->format('Y-m-d'),
                    'purpose' => $document->purpose,
                    'resident' => [
                        'name' => $document->resident->full_name,
                        'barangay_id' => $document->resident->barangay_id,
                        'address' => $document->resident->address,
                    ],
                    'payment_receipt' => $document->payment_receipt_path,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Document not found'
            ], 404);
        }
    }

    public function preview($id)
    {
        try {
            $document = DocumentRequest::findOrFail($id);
            
            // Generate or return the document preview URL
            $previewUrl = $this->generateDocumentPreview($document);
            
            return response()->json([
                'success' => true,
                'preview_url' => $previewUrl
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Preview not available'
            ], 404);
        }
    }

    public function print($id)
    {
        try {
            $document = DocumentRequest::findOrFail($id);
            
            // Generate or return the printable document URL
            $printUrl = $this->generatePrintableDocument($document);
            
            return response()->json([
                'success' => true,
                'print_url' => $printUrl
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Print document not available'
            ], 404);
        }
    }

    private function generateDocumentPreview($document)
    {
        // Implement your document preview generation logic
        // This could return a PDF URL, HTML preview, etc.
        return url("/storage/documents/previews/{$document->id}.pdf");
    }

    private function generatePrintableDocument($document)
    {
        // Implement your printable document generation logic
        return url("/storage/documents/prints/{$document->id}.pdf");
    }
}
```

## Database Requirements
Make sure your database has the following tables and relationships:

### document_requests table (example structure):
- id
- resident_id (foreign key)
- document_type
- status
- purpose
- payment_receipt_path
- created_at
- updated_at

### residents table (example structure):
- id
- full_name
- barangay_id
- address
- created_at
- updated_at

## Model Relationships
Ensure your models have the proper relationships:

```php
// DocumentRequest model
public function resident()
{
    return $this->belongsTo(Resident::class);
}

// Resident model
public function documentRequests()
{
    return $this->hasMany(DocumentRequest::class);
}
```

## Next Steps
1. Create the routes in `routes/web.php`
2. Create or update the `DocumentController`
3. Ensure your database models and relationships are set up
4. Test the API endpoints manually first
5. The frontend modal system is already complete and will work once these backend endpoints are available

## Testing the API
You can test the endpoints using:
```bash
curl http://your-domain/admin/documents/1/details
curl http://your-domain/admin/documents/1/preview
curl http://your-domain/admin/documents/1/print
```

The frontend JavaScript is already configured to handle these responses properly.
