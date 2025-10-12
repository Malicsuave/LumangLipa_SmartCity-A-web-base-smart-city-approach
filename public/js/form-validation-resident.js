/*
 * Registration Form Validation (Resident Only)
 * This is a copy of form-validation.js for resident registration step 1.
 */

$(document).ready(function() {
    // Clear any existing validation alerts
    $('.validation-alert').remove();
    // Initialize form validation
    initializeFormValidation();
    // Real-time validation
    setupRealTimeValidation();
    // Form submission handling
    setupFormSubmission();
});

function initializeFormValidation() {
    $('input[required], select[required], textarea[required]').each(function() {
        $(this).on('blur', function() {
            validateField($(this));
        });
    });
    $('input[type="email"]').on('blur', function() {
        validateEmail($(this));
    });
    $('input[name="contact_number"], input[name="emergency_contact_number"]').on('input', function() {
        formatPhoneNumber($(this));
        validatePhoneNumber($(this));
    });
    $('input[type="date"]').on('change', function() {
        validateDate($(this));
    });
    $('input[name="birthdate"]').on('input change blur', function() {
        validateDate($(this));
    });
}

function setupRealTimeValidation() {
    $('.form-control').on('input', function() {
        clearFieldError($(this));
    });
    $('select.form-control').on('change', function() {
        validateField($(this));
    });
}

function setupFormSubmission() {
    $('form').on('submit', function(e) {
        var form = $(this);
        var isValid = true;
        // Validate all required fields
        form.find('input[required], select[required], textarea[required]').each(function() {
            if (!validateField($(this))) {
                isValid = false;
            }
        });
        form.find('input[type="email"]').each(function() {
            if ($(this).val() && !validateEmail($(this))) {
                isValid = false;
            }
        });
        form.find('input[name="contact_number"], input[name="emergency_contact_number"]').each(function() {
            if (!validatePhoneNumber($(this))) {
                isValid = false;
            }
        });
        if (!isValid) {
            e.preventDefault();
            scrollToFirstError();
            return false;
        }
        var submitBtn = form.find('button[type="submit"]');
        setLoadingState(submitBtn, true);
    });
}

function validateField($field) {
    var value = $field.val().trim();
    var isRequired = $field.prop('required');
    if (isRequired && !value) {
        setFieldError($field, 'This field is required.');
        return false;
    }
    var fieldName = $field.attr('name');
    switch (fieldName) {
        case 'first_name':
        case 'last_name':
            if (value && !isValidName(value)) {
                setFieldError($field, 'Please enter a valid name (letters only).');
                return false;
            }
            break;
    }
    setFieldSuccess($field);
    return true;
}

function validateEmail($field) {
    var email = $field.val().trim();
    if (!email) {
        clearFieldError($field);
        return true;
    }
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        setFieldError($field, 'Please enter a valid email address.');
        return false;
    }
    setFieldSuccess($field);
    return true;
}

function formatPhoneNumber($field) {
    var value = $field.val().replace(/\D/g, '');
    if (value.length > 11) {
        value = value.substring(0, 11);
    }
    $field.val(value);
    if (value.length > 0 && value.length < 11) {
        $field.removeClass('is-valid').addClass('is-invalid');
        updateFieldFeedback($field, 'Please enter all 11 digits of your phone number.', 'invalid');
    } else if (value.length === 11 && /^09\d{9}$/.test(value)) {
        $field.removeClass('is-invalid').addClass('is-valid');
        updateFieldFeedback($field, 'Valid Philippine mobile number.', 'valid');
    } else if (value.length === 11) {
        $field.removeClass('is-valid').addClass('is-invalid');
        updateFieldFeedback($field, 'Philippine mobile numbers must start with 09.', 'invalid');
    } else if (value.length === 0) {
        $field.removeClass('is-valid is-invalid');
        clearFieldError($field);
    }
}

function validatePhoneNumber($field) {
    var phone = $field.val().trim();
    var isRequired = $field.prop('required');
    if (!phone && !isRequired) {
        clearFieldError($field);
        return true;
    }
    if (!phone && isRequired) {
        setFieldError($field, 'Phone number is required.');
        return false;
    }
    if (!/^\d{11}$/.test(phone)) {
        setFieldError($field, 'Please enter exactly 11 digits.');
        return false;
    }
    if (!/^09\d{9}$/.test(phone)) {
        setFieldError($field, 'Philippine mobile numbers must start with 09 (e.g., 09123456789).');
        return false;
    }
    setFieldSuccess($field);
    return true;
}

function updateFieldFeedback($field, message, type) {
    var feedbackClass = type === 'valid' ? 'valid-feedback' : 'invalid-feedback';
    var existingFeedback = $field.next('.' + feedbackClass);
    if (existingFeedback.length) {
        existingFeedback.text(message);
    } else {
        $field.next('.valid-feedback, .invalid-feedback').remove();
        $field.after('<div class="' + feedbackClass + '">' + message + '</div>');
    }
}

function validateDate($field) {
    var date = $field.val();
    var fieldName = $field.attr('name');
    if (!date) {
        if ($field.prop('required')) {
            setFieldError($field, 'This field is required.');
            return false;
        }
        return true;
    }
    var selectedDate = new Date(date);
    var today = new Date();
    // Only block future dates and ages over 150
    if (selectedDate >= today) {
        setFieldError($field, 'Birthdate cannot be in the future.');
        return false;
    }
    var age = today.getFullYear() - selectedDate.getFullYear();
    var m = today.getMonth() - selectedDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < selectedDate.getDate())) {
        age--;
    }
    if (age > 150) {
        setFieldError($field, 'Please enter a valid birthdate.');
        return false;
    }
    if (fieldName === 'birthdate' && age >= 60) {
        setFieldError($field, 'Residents aged 60 and above must be registered through the Senior Citizen registration form.');
        return false;
    }
    setFieldSuccess($field);
    return true;
}

function isValidName(name) {
    return /^[a-zA-Z\s\-'.]+$/.test(name);
}

function isValidBirthdate(date) {
    var birthDate = new Date(date);
    var today = new Date();
    return birthDate < today && birthDate > new Date('1900-01-01');
}

function setFieldError($field, message) {
    $field.removeClass('is-valid').addClass('is-invalid');
    $field.closest('.form-group, .mb-3, .col-md-6, .col-md-12').addClass('has-error').removeClass('has-success');
    var $feedback = $field.siblings('.invalid-feedback');
    if ($feedback.length === 0) {
        $feedback = $('<div class="invalid-feedback"></div>');
        $field.after($feedback);
    }
    $feedback.text(message).show();
    $field.siblings('.valid-feedback').hide();
}

function setFieldSuccess($field) {
    $field.removeClass('is-invalid').addClass('is-valid');
    $field.closest('.form-group, .mb-3, .col-md-6, .col-md-12').addClass('has-success').removeClass('has-error');
    $field.siblings('.invalid-feedback').hide();
}

function clearFieldError($field) {
    $field.removeClass('is-invalid is-valid');
    $field.closest('.form-group, .mb-3, .col-md-6, .col-md-12').removeClass('has-error has-success');
    $field.siblings('.invalid-feedback, .valid-feedback').hide();
}

function scrollToFirstError() {
    var $firstError = $('.form-control.is-invalid').first();
    if ($firstError.length > 0) {
        $('html, body').animate({
            scrollTop: $firstError.offset().top - 100
        }, 500);
        setTimeout(function() {
            $firstError.focus();
        }, 600);
    }
}

function setLoadingState($button, loading) {
    if (loading) {
        $button.prop('disabled', true).addClass('loading');
        var originalText = $button.html();
        $button.data('original-text', originalText);
        $button.html('<i class="fas fa-spinner fa-spin mr-2"></i>Processing...');
    } else {
        $button.prop('disabled', false).removeClass('loading');
        $button.html($button.data('original-text') || 'Submit');
    }
}
