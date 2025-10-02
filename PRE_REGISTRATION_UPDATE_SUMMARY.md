# Pre-Registration System Update Summary

## Date: October 2, 2025

## Overview
Updated the pre-registration system to match the new form structure with **3 steps** (Personal Info → Contact Info → Photo Upload → Review). Senior citizen pre-registration will be a separate independent system.

---

## Database Changes

### Migration: `2025_10_02_030102_update_pre_registrations_table_for_new_form.php`

#### Updated Enum Fields:
1. **`type_of_resident`**: Changed from `['Permanent', 'Temporary', 'Boarder/Transient']` to `['Non-Migrant', 'Migrant', 'Transient']`
2. **`sex`**: Changed from `['Male', 'Female']` to `['Male', 'Female', 'Non-binary', 'Transgender', 'Other']`
3. **`citizenship_type`**: Changed from `['FILIPINO', 'Dual Citizen', 'Foreigner']` to `['FILIPINO', 'DUAL', 'NATURALIZED', 'FOREIGN']`

#### New Fields Added:
- `emergency_contact_name` (nullable, string)
- `emergency_contact_relationship` (nullable, string, 50 chars)
- `emergency_contact_number` (nullable, string, 11 chars)

#### Modified Fields:
- `email_address`: Now nullable (optional)
- `profession_occupation`: Now nullable

#### Removed Fields (Not used in current form):
- `monthly_income` - Will be in separate forms if needed
- `philsys_id` - Not collected in current form
- `population_sectors` - Not applicable (senior citizens have separate system)
- `mother_first_name`, `mother_middle_name`, `mother_last_name` - Not collected
- `senior_info` - Moved to separate senior citizen pre-registration system

---

## Form Structure

### **Step 1: Personal Information**
Collects:
- Type of Resident (Non-Migrant, Migrant, Transient)
- Full Name (First, Middle, Last, Suffix)
- Birthdate & Birthplace
- Gender (Male, Female, Non-binary, Transgender, Other)
- Civil Status (Single, Married, Divorced, Widowed, Separated)
- Citizenship Type & Country (if applicable)
- Educational Attainment & Status
- Religion (optional)
- Profession/Occupation (optional)

### **Step 2: Contact & Address Information**
Collects:
- Contact Number (11 digits required)
- Email Address (optional, must be unique)
- Current Address (complete address)
- Emergency Contact Name (required)
- Emergency Contact Relationship (Parent, Spouse, Child, Sibling, Relative, Friend, Other)

### **Step 3: Photo & Signature Upload**
Collects:
- Photo (required, max 5MB, JPG/PNG)
- Signature (optional, max 2MB, JPG/PNG)
- Features:
  - Live camera capture option
  - Upload from file option
  - Preview restoration when navigating back
  - Session-based temporary storage
  - Quality validation

### **Review Page**
- Shows all collected information from Steps 1-3
- Final confirmation checkbox
- Submit button

**Note**: Step 4 structure exists for optional family members but is not currently implemented. Senior citizen pre-registration will be a **separate independent system**.

---

## Controller Updates

### **PreRegistrationController.php**

#### Updated Methods:

1. **`storeStep1()`**
   - Validates all Step 1 fields including citizenship and education
   - Added validation for citizenship_country when DUAL or FOREIGN
   - Stores all data in session

2. **`storeStep2()`**
   - Validates contact number (11 digits)
   - Optional email with uniqueness check
   - Validates emergency contact information
   - Stores all data in session

3. **`storeStep3()`**
   - Handles photo and signature uploads
   - Converts files to base64 for session storage
   - Creates preview data for persistence
   - Checks if user is senior citizen (age >= 60)
   - Redirects accordingly

4. **`store()`** (Final Submission)
   - Maps all form data to database columns correctly
   - Processes photos and signatures using Intervention Image
   - Saves to permanent storage
   - Handles emergency contact data
   - Comprehensive error handling and logging
   - No senior citizen detection (separate system)

---

## Model Updates

### **PreRegistration.php**

Added to `$fillable` array:
- `emergency_contact_name`
- `emergency_contact_relationship`
- `emergency_contact_number`

---

## JavaScript Updates

### **resident-registration.js**

Updated photo and signature preview restoration:
- Added console logging for debugging
- Explicitly sets `display: block` on preview images
- Explicitly hides placeholders when previews exist
- Handles session data from `data-preview` attribute

---

## Session Management

### Session Keys Used:
- `pre_registration.step1` - Personal information
- `pre_registration.step2` - Contact & address information
- `pre_registration.step3` - Photo & signature data (base64 encoded)
- `pre_registration.step4` - Family members (optional, for future use)
- `temp_photo_preview` - Photo preview for display
- `temp_signature_preview` - Signature preview for display

### Session Cleanup:
- Clears all data when starting new registration
- Clears preview data when navigating backward
- Clears all data after successful submission

---

## Data Flow

1. **User starts registration** → `createStep1()` → Clears old session data
2. **User fills Step 1** → `storeStep1()` → Validates & stores in session
3. **User fills Step 2** → `storeStep2()` → Validates & stores in session
4. **User uploads photo/signature** → `storeStep3()` → Base64 encodes & stores in session
5. **User reviews and confirms** → `store()` → Processes files, saves to database, clears session

---

## Photo Processing

### Photo Specifications:
- **Required**: Yes
- **Max Size**: 5MB
- **Formats**: JPG, PNG
- **Processing**: Resized to 600x600px (2x2 inches at 300dpi)
- **Storage**: `/storage/pre-registrations/photos/`
- **Filename Format**: `prereg_{timestamp}_{random}.jpg`

### Signature Specifications:
- **Required**: No (optional)
- **Max Size**: 2MB
- **Formats**: JPG, PNG
- **Processing**: Resized maintaining aspect ratio
- **Storage**: `/storage/pre-registrations/signatures/`
- **Filename Format**: `prereg_sig_{timestamp}_{random}.png`

---

## Senior Citizen Detection

- **Age Threshold**: 60 years
- **Calculation**: Based on birthdate from Step 1
- **Action**: Automatically adds "Senior Citizen" to population_sectors
- **Special Flow**: If age >= 60, redirects to step4-senior (optional senior info)

---

## Validation Rules Summary

### Step 1:
- type_of_resident: required, specific enum values
- first_name, last_name: required, max 100 chars, letters/spaces/dots/hyphens only
- middle_name, suffix: optional, same character rules
- birthdate: required, must be <= today
- birthplace: required, max 255 chars
- sex: required, 5 options
- civil_status: required, 5 options
- citizenship_type: required, 4 options
- citizenship_country: required if DUAL or FOREIGN
- educational_attainment: required
- education_status: required, 4 options
- religion: optional, max 100 chars
- profession_occupation: optional, max 100 chars

### Step 2:
- contact_number: required, exactly 11 digits
- email_address: optional, must be valid email and unique
- address: required, text field
- emergency_contact_name: required, max 255 chars
- emergency_contact_relationship: required, 7 options

### Step 3:
- photo: required, image, max 5MB
- signature: optional, image, max 2MB

---

## Error Handling

### Types of Errors Handled:
1. **Validation Errors**: Field-specific error messages
2. **Database Errors**: Duplicate email, connection issues
3. **File System Errors**: Upload/storage permission issues
4. **Session Errors**: Missing data, incomplete registration
5. **General Exceptions**: Comprehensive logging and user-friendly messages

### Logging:
- All errors logged to Laravel log
- PDO exceptions include SQL error codes
- Stack traces included in development mode
- User-friendly messages in production

---

## Testing Checklist

- [ ] Test complete registration flow (all 3 steps)
- [ ] Test navigation backward (data should persist)
- [ ] Test photo upload via file
- [ ] Test photo capture via camera (requires HTTPS)
- [ ] Test signature upload (optional)
- [ ] Test form validation (required fields, formats)
- [ ] Test email uniqueness validation
- [ ] Test senior citizen detection (age >= 60)
- [ ] Test session persistence
- [ ] Test session cleanup on new registration
- [ ] Test error handling (duplicate email, missing data)
- [ ] Test database insertion
- [ ] Test photo/signature file storage

---

## Files Modified

1. `/app/Http/Controllers/Public/PreRegistrationController.php`
2. `/app/Models/PreRegistration.php`
3. `/resources/views/public/pre-registration/resident/step3.blade.php`
4. `/public/js/resident-registration.js`
5. `/database/migrations/2025_10_02_030102_update_pre_registrations_table_for_new_form.php`

---

## Migration Commands

```bash
# Run the migration
php artisan migrate

# Rollback if needed
php artisan migrate:rollback

# Check migration status
php artisan migrate:status
```

---

## Notes

- Email is now optional (nullable) in the form
- Emergency contact phone uses primary contact number for now
- Step 4 (family members) structure exists but is optional
- Senior citizen flow redirects to step4-senior if age >= 60
- Photo and signature previews persist when navigating back
- All session data cleared after successful submission
- Base64 encoding used for temporary file storage in session

---

## Future Enhancements

1. Add separate emergency contact phone number field in Step 2
2. Implement Step 4 for family members with dynamic form fields
3. Add email verification step
4. Add SMS verification for contact number
5. Implement draft save functionality
6. Add progress bar indicator
7. Add ability to upload multiple photos
8. Implement image cropping tool
9. Add barcode/QR code for registration tracking
10. Add batch approval functionality for admins
