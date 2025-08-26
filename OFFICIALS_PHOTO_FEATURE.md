# Barangay Officials Photo Upload Feature

## Overview
This feature enhancement allows administrators to upload, manage, and display photos for all barangay officials. Photos are displayed in the public home page officials tree structure and can be managed through the admin panel.

## Features Added

### 1. Database Changes
- **Migration**: Added photo fields to `barangay_officials` table
- **Fields Added**:
  - `captain_photo`
  - `secretary_photo`
  - `treasurer_photo`
  - `sk_chairperson_photo`
  - `councilor1_photo` through `councilor7_photo`

### 2. File Storage
- **Storage Location**: `storage/app/public/officials/`
- **Public Access**: Via `/storage/officials/` URL
- **File Validation**: JPEG, PNG, JPG formats, max 2MB
- **File Naming**: `{position}_{timestamp}.{extension}`

### 3. Admin Panel Enhancements
**Location**: `/admin/officials/edit-single`

**Features**:
- ✅ Upload photos for all officials
- ✅ Preview current photos with thumbnails
- ✅ Delete existing photos with confirmation
- ✅ Form validation for file types and sizes
- ✅ Automatic old photo cleanup when uploading new ones
- ✅ Organized layout by position (Captain, Councilors, SK Chairman, Staff)

**Controls**:
- File input fields for each official position
- Delete buttons for existing photos
- Real-time preview of uploaded images
- Success/error messaging

### 4. Public Display Integration
**Location**: Public home page officials tree structure

**Features**:
- ✅ Dynamic photo display in tree structure
- ✅ Fallback to initials when no photo available
- ✅ Enhanced CSS styling for photo display
- ✅ Responsive design maintained
- ✅ Hover effects for photos

### 5. Technical Implementation

#### Controller Updates (`BarangayOfficialController.php`)
```php
- Added file upload handling
- Added photo validation rules
- Added automatic file cleanup
- Added photo deletion endpoint
- Enhanced error handling
```

#### Model Updates (`BarangayOfficial.php`)
```php
- Added photo fields to $fillable array
- Maintains relationship with existing name fields
```

#### View Updates
- **Admin View**: Enhanced form with file upload capabilities
- **Public View**: Dynamic photo display with fallbacks
- **CSS Updates**: Enhanced styling for photo presentation

#### Route Updates (`web.php`)
```php
- Added photo deletion route: DELETE /admin/officials/photo/{field}
- Maintained existing admin role restrictions
```

### 6. File Management
- **Automatic Cleanup**: Old photos are deleted when new ones are uploaded
- **Manual Deletion**: Admin can delete photos individually
- **Storage Optimization**: Files stored in organized directory structure
- **Security**: File type validation and size restrictions

### 7. User Experience
**Admin Users**:
- Intuitive upload interface
- Real-time photo previews
- One-click photo deletion
- Clear success/error feedback

**Public Users**:
- Professional photo display in officials tree
- Graceful fallback to initials when no photo
- Consistent visual design
- Mobile-responsive layout

## Usage Instructions

### For Administrators:
1. Navigate to `/admin/officials/edit-single`
2. Fill in official names
3. Click "Choose File" for any position to upload a photo
4. Select an image file (JPEG, PNG, JPG, max 2MB)
5. Click "Save Officials Information"
6. To delete a photo, click the "Delete Photo" button under the preview

### For Visitors:
- Visit the homepage to see the updated officials tree
- Photos will automatically display when available
- Initials will show when no photo is uploaded

## File Structure
```
storage/app/public/officials/
├── captain_1234567890.jpg
├── councilor1_1234567890.png
├── secretary_1234567890.jpg
└── ... (other official photos)

public/storage/officials/ (symlinked)
├── captain_1234567890.jpg
├── councilor1_1234567890.png
├── secretary_1234567890.jpg
└── ... (accessible via web)
```

## Database Schema
```sql
ALTER TABLE barangay_officials ADD COLUMN (
    captain_photo VARCHAR(255) NULL,
    secretary_photo VARCHAR(255) NULL,
    treasurer_photo VARCHAR(255) NULL,
    sk_chairperson_photo VARCHAR(255) NULL,
    councilor1_photo VARCHAR(255) NULL,
    councilor2_photo VARCHAR(255) NULL,
    councilor3_photo VARCHAR(255) NULL,
    councilor4_photo VARCHAR(255) NULL,
    councilor5_photo VARCHAR(255) NULL,
    councilor6_photo VARCHAR(255) NULL,
    councilor7_photo VARCHAR(255) NULL
);
```

## Security Considerations
- ✅ File type validation (images only)
- ✅ File size limits (2MB maximum)
- ✅ Admin role restrictions
- ✅ CSRF protection
- ✅ Secure file storage location
- ✅ Automatic file cleanup

## Testing Checklist
- [x] Photo upload functionality
- [x] Photo deletion functionality
- [x] File validation (type and size)
- [x] Public display integration
- [x] Responsive design
- [x] Admin role restrictions
- [x] Error handling
- [x] File cleanup operations

## Future Enhancements
- Image resizing/optimization on upload
- Bulk photo upload functionality
- Photo cropping interface
- Alternative image formats (WebP)
- Photo gallery view in admin
