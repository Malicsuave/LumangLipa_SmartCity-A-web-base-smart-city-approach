/**
 * Global Phone Number Validation for Philippine Numbers
 * Restricts input to numbers only and limits to 11 digits
 */

$(document).ready(function() {
    // Apply validation to all phone number fields
    initializePhoneValidation();
});

function initializePhoneValidation() {
    // Target phone number fields by common patterns
    const phoneSelectors = [
        'input[name*="phone"]',
        'input[name*="contact"]',
        'input[name*="mobile"]',
        'input[id*="phone"]',
        'input[id*="contact"]',
        'input[id*="mobile"]',
        'input[placeholder*="09"]',
        'input[placeholder*="phone"]',
        'input[placeholder*="contact"]',
        '.phone-input'
    ].join(', ');

    // Apply validation to existing phone fields
    $(phoneSelectors).each(function() {
        applyPhoneValidation($(this));
    });

    // Also apply to dynamically added phone fields
    $(document).on('focus', phoneSelectors, function() {
        if (!$(this).hasClass('phone-validated')) {
            applyPhoneValidation($(this));
        }
    });
}

function applyPhoneValidation($input) {
    // Mark as validated to avoid duplicate bindings
    $input.addClass('phone-validated');
    
    // Set input attributes
    $input.attr({
        'type': 'tel',
        'maxlength': '11',
        'pattern': '[0-9]{11}',
        'inputmode': 'numeric'
    });

    // Add placeholder if not set
    if (!$input.attr('placeholder') || $input.attr('placeholder') === '') {
        $input.attr('placeholder', '09XXXXXXXXX');
    }

    // Prevent non-numeric input
    $input.on('input', function(e) {
        let value = $(this).val();
        
        // Remove any non-numeric characters
        value = value.replace(/\D/g, '');
        
        // Limit to 11 digits
        if (value.length > 11) {
            value = value.substring(0, 11);
        }
        
        $(this).val(value);
        
        // Real-time validation feedback
        validatePhoneNumber($(this));
    });

    // Prevent non-numeric keypress
    $input.on('keypress', function(e) {
        // Allow backspace, delete, tab, escape, enter, and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
            (e.keyCode === 65 && e.ctrlKey === true) ||
            (e.keyCode === 67 && e.ctrlKey === true) ||
            (e.keyCode === 86 && e.ctrlKey === true) ||
            (e.keyCode === 88 && e.ctrlKey === true)) {
            return;
        }
        
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    // Paste event handler
    $input.on('paste', function(e) {
        setTimeout(() => {
            let value = $(this).val();
            value = value.replace(/\D/g, '');
            if (value.length > 11) {
                value = value.substring(0, 11);
            }
            $(this).val(value);
            validatePhoneNumber($(this));
        }, 1);
    });

    // Blur validation
    $input.on('blur', function() {
        validatePhoneNumber($(this));
    });
}

function validatePhoneNumber($input) {
    const value = $input.val();
    const $feedback = $input.siblings('.invalid-feedback');
    const $validFeedback = $input.siblings('.valid-feedback');
    
    // Remove existing validation classes
    $input.removeClass('is-valid is-invalid');
    
    if (value === '') {
        // Empty field - no validation needed if not required
        if ($input.prop('required')) {
            $input.addClass('is-invalid');
            updateFeedback($input, 'Phone number is required.', 'invalid');
        }
        return;
    }
    
    // Check if it's a valid Philippine mobile number
    if (value.length === 11 && value.startsWith('09')) {
        $input.addClass('is-valid');
        updateFeedback($input, 'Valid Philippine mobile number.', 'valid');
    } else if (value.length === 11 && (value.startsWith('02') || value.startsWith('03') || value.startsWith('04') || value.startsWith('05') || value.startsWith('06') || value.startsWith('07') || value.startsWith('08'))) {
        $input.addClass('is-valid');
        updateFeedback($input, 'Valid Philippine landline number.', 'valid');
    } else {
        $input.addClass('is-invalid');
        if (value.length < 11) {
            updateFeedback($input, `Phone number must be 11 digits. You entered ${value.length} digits.`, 'invalid');
        } else if (!value.startsWith('09') && !value.match(/^0[2-8]/)) {
            updateFeedback($input, 'Philippine numbers must start with 09 (mobile) or 02-08 (landline).', 'invalid');
        } else {
            updateFeedback($input, 'Please enter a valid Philippine phone number.', 'invalid');
        }
    }
}

function updateFeedback($input, message, type) {
    let $feedback = $input.siblings(`.${type}-feedback`);
    
    if ($feedback.length === 0) {
        $feedback = $(`<div class="${type}-feedback"></div>`);
        $input.after($feedback);
    }
    
    $feedback.text(message);
    
    // Hide the opposite feedback type
    const oppositeType = type === 'valid' ? 'invalid' : 'valid';
    $input.siblings(`.${oppositeType}-feedback`).hide();
    $feedback.show();
}

// Export functions for manual use
window.PhoneValidation = {
    initialize: initializePhoneValidation,
    applyTo: applyPhoneValidation,
    validate: validatePhoneNumber
};
