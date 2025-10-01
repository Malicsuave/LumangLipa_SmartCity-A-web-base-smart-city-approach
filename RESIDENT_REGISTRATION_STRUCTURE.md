# Resident Registration - Master File Structure

## Overview
This document describes the centralized CSS and JavaScript structure for the resident registration forms.

## File Structure

### 1. Master Layout
**Location:** `/resources/views/layouts/public/resident-registration.blade.php`

This is the master layout for all resident registration steps. It:
- Extends the main public master layout
- Includes all necessary CSS and JavaScript files
- Provides a consistent structure for all steps
- Defines sections that child views can override

**Usage in step views:**
```blade
@extends('layouts.public.resident-registration')

@section('title', 'Resident Pre-Registration - Step X')
@section('form-title', 'Resident Pre-Registration')
@section('step-indicator', 'Step X of 4')

@section('form-content')
  <!-- Your form content here -->
@endsection
```

### 2. Centralized CSS
**Location:** `/public/css/resident-registration.css`

**Includes:**
- Header styles (personal, citizenship, education, etc.)
- Card styles
- Hero section styles
- Form control styles (rounded inputs)
- Bootstrap Select customization
- Validation state styles (is-valid, is-invalid)
- Material Kit floating label styles
- Responsive adjustments
- Button styles

**Features:**
- ✅ Rounded input styles with `rounded-pill`
- ✅ Material Kit success/error states with icons
- ✅ Proper validation feedback styling
- ✅ No red outline on invalid fields (only icon + message)
- ✅ Consistent spacing and padding
- ✅ Responsive design for mobile devices

### 3. Centralized JavaScript
**Location:** `/public/js/resident-registration.js`

**Includes:**
- Material Kit component initialization
- Floating label handling
- Form validation logic
- Field-specific validation (email, phone, date)
- Dynamic field behavior
- Bootstrap Select initialization

**Features:**
- ✅ Auto-validates on blur for required fields
- ✅ Real-time validation for text inputs
- ✅ Philippine phone number validation
- ✅ Email format validation
- ✅ Birthdate validation (not in future)
- ✅ Dynamic field requirements (citizenship country)
- ✅ Smooth scroll to first error on submit
- ✅ Material Kit floating label support

## How to Use

### For Existing Steps (Step 1, Step 2)
The files have been updated to use the master layout. No additional changes needed.

### For New Steps (Step 3, Step 4, etc.)

1. Create a new step file:
```blade
@extends('layouts.public.resident-registration')

@section('title', 'Resident Pre-Registration - Step X')
@section('form-title', 'Resident Pre-Registration')
@section('step-indicator', 'Step X of 4')

@section('form-content')
<div class="card-header bg-white border-0 pb-0">
  <h5 class="personal-header"><i class="fas fa-icon mr-2"></i>Section Title</h5>
</div>
<form role="form" id="residentPreRegStepXForm" method="POST" action="{{ route('public.pre-registration.stepX.store') }}" autocomplete="off">
  @csrf
  <div class="card-body">
    <!-- Your form fields here -->
    
    <div class="row">
      <div class="col-md-6 mt-2">
        <a href="{{ route('public.pre-registration.step[X-1]') }}" class="btn btn-outline-secondary w-100">Previous</a>
      </div>
      <div class="col-md-6 mt-2">
        <button type="submit" class="btn bg-gradient-dark w-100">Next</button>
      </div>
    </div>
  </div>
</form>
@endsection
```

## Input Field Patterns

### Text Input (with floating label)
```blade
<div class="col-md-6 mb-3">
  <div class="input-group input-group-dynamic">
    <label class="form-label">Field Label <span class="text-danger">*</span></label>
    <input class="form-control rounded-pill @error('field_name') is-invalid @enderror" 
           type="text" 
           name="field_name" 
           value="{{ old('field_name') }}" 
           required>
    @error('field_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>
```

### Select Dropdown (static label)
```blade
<div class="col-md-6 mb-3">
  <div class="input-group input-group-static">
    <label for="field_name">Field Label <span class="text-danger">*</span></label>
    <select class="form-control selectpicker rounded-pill @error('field_name') is-invalid @enderror" 
            id="field_name" 
            name="field_name" 
            required 
            data-style="btn-white">
      <option value="">Select option</option>
      <option value="option1" {{ old('field_name') == 'option1' ? 'selected' : '' }}>Option 1</option>
    </select>
    @error('field_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>
```

### Date Input (static label)
```blade
<div class="col-md-6 mb-3">
  <div class="input-group input-group-static">
    <label for="date_field">Date Label <span class="text-danger">*</span></label>
    <input class="form-control rounded-pill @error('date_field') is-invalid @enderror" 
           type="date" 
           id="date_field" 
           name="date_field" 
           value="{{ old('date_field') }}" 
           required>
    @error('date_field')<div class="invalid-feedback">{{ $message }}</div>@enderror
  </div>
</div>
```

## Section Headers

Available header classes:
- `.personal-header` - Personal Information
- `.citizenship-header` - Citizenship Information
- `.education-header` - Education Information
- `.additional-header` - Additional Information
- `.address-header` - Address Information
- `.contact-header` - Contact Information
- `.emergency-header` - Emergency Contact
- `.household-header` - Household Information

All headers are pre-styled in the CSS file.

## Validation States

The JavaScript automatically handles:
- Adding `is-valid` class when field is correctly filled
- Adding `is-invalid` class when field has errors
- Displaying error messages below fields
- Removing validation when field is corrected

**Server-side errors** are preserved with `[data-server]` attribute on feedback divs.

## Benefits

1. **Consistency** - All steps look and behave the same
2. **Maintainability** - Update CSS/JS in one place
3. **Performance** - Files are cached by browser
4. **Clean Code** - No duplicate styles or scripts in views
5. **Easy Updates** - Add new validation rules or styles globally
6. **Scalability** - Easy to add more steps

## Notes

- Material Kit and Bootstrap Select are still loaded
- The master layout automatically includes all necessary files
- Validation works automatically for all forms with `id*="residentPreReg"`
- Floating labels auto-activate when fields have values
