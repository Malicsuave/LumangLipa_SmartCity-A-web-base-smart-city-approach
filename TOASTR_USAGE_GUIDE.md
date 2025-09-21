# Global Toastr Notification System - Usage Guide

## Overview
The Lumanglipa system now uses Toastr notifications globally across all admin pages. Toastr is already included in the master layout, so you don't need to include it separately on individual pages.

## Available Global Functions

### Basic Notification Functions
```javascript
// Success notification
showSuccess('Document approved successfully!');
showSuccess('Document approved successfully!', 'Success'); // with custom title

// Error notification
showError('Failed to process request');
showError('Failed to process request', 'Error'); // with custom title

// Warning notification
showWarning('Please fill all required fields');
showWarning('Please fill all required fields', 'Warning'); // with custom title

// Info notification
showInfo('Document is being processed');
showInfo('Document is being processed', 'Information'); // with custom title
```

### AJAX Response Helpers
```javascript
// For handling standard AJAX responses
$.ajax({
    url: '/api/endpoint',
    method: 'POST',
    data: formData,
    success: function(response) {
        handleAjaxResponse(response, function(res) {
            // Success callback (optional)
            console.log('Success:', res);
            window.location.reload();
        }, function(res) {
            // Error callback (optional)
            console.log('Error:', res);
        });
    },
    error: function(xhr, status, error) {
        handleAjaxError(xhr, status, error, 'Custom error message');
    }
});
```

### Direct Toastr Usage
If you need more control, you can still use toastr directly:
```javascript
toastr.success('Message', 'Title', {
    timeOut: 10000,
    closeButton: true
});
```

## Configuration
Global Toastr options are set in the master layout:
- Position: Top-right corner
- Duration: 5 seconds (5000ms)
- Progress bar: Enabled
- Close button: Enabled
- Animation: Fade in/out

## Migration from SweetAlert2
Replace SweetAlert2 calls with Toastr equivalents:

### Before (SweetAlert2):
```javascript
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: 'Document approved successfully!',
    timer: 3000
});
```

### After (Toastr):
```javascript
showSuccess('Document approved successfully!');
```

## Examples in Different Scenarios

### Form Submission
```javascript
$('#myForm').on('submit', function(e) {
    e.preventDefault();
    
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            handleAjaxResponse(response, function() {
                $('#myModal').modal('hide');
                window.location.reload();
            });
        },
        error: function(xhr, status, error) {
            handleAjaxError(xhr, status, error);
        }
    });
});
```

### Button Click Actions
```javascript
$('#approveBtn').on('click', function() {
    $.ajax({
        url: '/admin/approve/' + recordId,
        method: 'POST',
        success: function(response) {
            if (response.success) {
                showSuccess(response.message);
                // Additional success actions
            } else {
                showError(response.message);
            }
        },
        error: function(xhr, status, error) {
            handleAjaxError(xhr, status, error, 'Failed to approve record');
        }
    });
});
```

### Validation Errors
```javascript
// The handleAjaxError function automatically handles Laravel validation errors
// It will show the first validation error message in a toastr notification
```

## Best Practices
1. Use the global helper functions for consistency
2. Keep messages concise and user-friendly
3. Use appropriate notification types (success, error, warning, info)
4. Don't include redundant Toastr CSS/JS in individual pages
5. Use handleAjaxError for consistent error handling
6. Provide meaningful error messages for users

## Browser Support
Toastr works in all modern browsers including:
- Chrome 
- Firefox
- Safari
- Edge
- Internet Explorer 9+
