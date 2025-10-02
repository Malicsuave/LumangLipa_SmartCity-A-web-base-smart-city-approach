# Proof of Residency Implementation Summary

## Overview
Added proof of residency document upload requirement to the resident pre-registration form (Step 3).

## Changes Made

### 1. Frontend Updates

#### Step 3 View (`resources/views/public/pre-registration/resident/step3.blade.php`)
- **Updated title**: Changed from "Photo & Signature Upload" to "Photo & Documents Upload"
- **Added new section**: "Proof of Residency Upload" with:
  - File upload input accepting images (JPG, PNG) and PDFs
  - Maximum file size: 5MB
  - Preview container for uploaded documents
  - Information alert listing accepted documents:
    - Utility bills (Electric, Water)
    - Barangay Clearance or Certificate
    - Lease/Rental Agreement
    - Tax Declaration or Property Title
    - Any official document showing current address
  - Upload guidelines section updated to include proof of residency requirements

#### Review Page (`resources/views/public/pre-registration/resident/review.blade.php`)
- **Updated section title**: Changed from "Photo & Signature" to "Photo, Signature & Documents"
- **Updated layout**: Changed from 2-column to 3-column layout to accommodate proof of residency
- **Added preview**: Shows uploaded proof of residency document preview

#### JavaScript (`public/js/resident-registration.js`)
- **Added preview handler** for proof of residency uploads
- **Handles both file types**:
  - Images: Shows image preview
  - PDFs: Shows PDF icon with filename
- **Session restoration**: Restores preview when user navigates back to edit

### 2. Backend Updates

#### Controller (`app/Http/Controllers/Public/PreRegistrationController.php`)

**Updated `storeStep3` method**:
- Added validation for `proof_of_residency` field:
  - Required field (unless already uploaded in session)
  - Accepts: JPEG, JPG, PNG, PDF
  - Maximum size: 5MB
- Added file processing and session storage for proof of residency
- Stores base64 encoded file data in session
- Creates preview for images (not for PDFs)

**Updated `store` method**:
- Added processing for proof of residency file before final submission
- Saves processed file to database

**Added new method `processProofOfResidency`**:
- Decodes base64 file data
- Handles both images and PDFs:
  - **PDFs**: Saves directly without processing
  - **Images**: 
    - Resizes to maximum 1600px width (maintains aspect ratio)
    - Compresses to 85% quality
    - Saves as JPG/PNG
- Saves to: `storage/app/public/pre-registrations/proof-of-residency/`
- Returns unique filename

### 3. Database Updates

#### Model (`app/Models/PreRegistration.php`)
- Added `'proof_of_residency'` to the `$fillable` array

#### Migration (`database/migrations/2025_10_02_163826_add_proof_of_residency_to_pre_registrations_table.php`)
- Added `proof_of_residency` column to `pre_registrations` table
- Column type: `string` (stores filename)
- Nullable: Yes (but required through validation)
- Position: After `signature` column

### 4. File Storage Structure

```
storage/app/public/pre-registrations/
├── photos/                    (existing)
├── signatures/                (existing)
└── proof-of-residency/        (new)
    ├── prereg_proof_1234567890_abc123xyz.jpg
    ├── prereg_proof_1234567890_def456uvw.png
    └── prereg_proof_1234567890_ghi789rst.pdf
```

## Workflow

### User Experience:
1. User fills out Step 1 (Personal Information)
2. User fills out Step 2 (Contact & Address)
3. User uploads:
   - **Photo** (Required)
   - **Signature** (Optional)
   - **Proof of Residency** (Required) ← NEW
4. User reviews all information
5. User submits pre-registration

### Admin Experience (Next Steps):
1. Admin receives pre-registration submission
2. Admin can view all uploaded documents including proof of residency
3. Admin verifies proof of residency document
4. Admin approves or rejects application

## Next Steps (To Be Implemented)

### Admin Dashboard Updates Needed:
1. **Pre-registration detail view**: 
   - Display proof of residency document
   - Add download/view button for proof of residency
   - Show document type (image vs PDF)

2. **Approval workflow**:
   - Verify proof of residency before approval
   - Add verification checklist item for proof of residency
   - Allow admin to mark document as "verified"

3. **Document viewer**:
   - Implement PDF viewer for PDF documents
   - Implement image viewer for image documents
   - Add zoom/download functionality

## Testing Checklist

- [x] Upload image file (JPG/PNG) - shows preview
- [ ] Upload PDF file - shows PDF icon
- [ ] File size validation (max 5MB)
- [ ] Required field validation
- [ ] Session restoration when navigating back
- [ ] Final submission saves file to storage
- [ ] Database record includes proof of residency filename
- [ ] File is accessible from storage path
- [ ] Admin can view uploaded document

## Files Modified

1. `/resources/views/public/pre-registration/resident/step3.blade.php`
2. `/resources/views/public/pre-registration/resident/review.blade.php`
3. `/public/js/resident-registration.js`
4. `/app/Http/Controllers/Public/PreRegistrationController.php`
5. `/app/Models/PreRegistration.php`
6. `/database/migrations/2025_10_02_163826_add_proof_of_residency_to_pre_registrations_table.php`

## Migration Status

✅ Migration has been run successfully:
```
2025_10_02_163826_add_proof_of_residency_to_pre_registrations_table
```

## Notes

- Proof of residency is now required for all new pre-registrations
- PDFs are saved as-is without processing
- Images are resized and compressed to optimize storage
- Files are stored with unique names to prevent conflicts
- Session data allows users to navigate back without re-uploading
