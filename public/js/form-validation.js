/**
 * Enhanced Form Validation System
 * Provides real-time client-side validation with security focus
 */

class FormValidator {
    constructor() {
        // Apply to ALL forms, not just those with data-validate
        this.forms = document.querySelectorAll('form');
        this.init();
    }

    init() {
        this.forms.forEach(form => {
            // Disable browser's default validation
            form.setAttribute('novalidate', 'true');
            
            // Add submit event listener to intercept form submission
            form.addEventListener('submit', (event) => {
                // Always prevent default form submission first
                event.preventDefault();
                
                // Validate the form
                const isValid = this.validateForm(form);
                
                // If valid, submit the form programmatically
                if (isValid) {
                    form.submit();
                }
            });
            
            // Add input event listeners for real-time validation feedback
            const fields = form.querySelectorAll('input, textarea, select');
            fields.forEach(field => {
                field.addEventListener('input', () => this.validateField(field));
                field.addEventListener('blur', () => this.validateField(field));
            });
        });
    }

    validateField(field) {
        const value = field.value.trim();
        const fieldType = field.type || field.tagName.toLowerCase();
        const fieldName = field.name;
        
        // Clear previous validation states
        this.clearFieldValidation(field);
        
        let isValid = true;
        let errorMessage = '';

        // Required field validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'This field is required.';
        } else if (value) {
            // Only validate non-empty fields that aren't required
            
            // Email validation
            if (fieldType === 'email') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid email address.';
                }
            }
            
            // Password validation
            else if (fieldType === 'password') {
                if (value.length < 8) {
                    isValid = false;
                    errorMessage = 'Password must be at least 8 characters.';
                }
            }
            
            // Phone number validation for common field names
            else if (fieldName.includes('phone') || fieldName.includes('contact')) {
                const phoneRegex = /^[0-9+\-\s()]{7,15}$/;
                if (!phoneRegex.test(value)) {
                    isValid = false;
                    errorMessage = 'Please enter a valid phone number.';
                }
            }
            
            // Date validation
            else if (fieldType === 'date') {
                const date = new Date(value);
                if (isNaN(date.getTime())) {
                    isValid = false;
                    errorMessage = 'Please enter a valid date.';
                }
            }
            
            // Number validation
            else if (fieldType === 'number') {
                const min = parseFloat(field.getAttribute('min'));
                const max = parseFloat(field.getAttribute('max'));
                
                if (!isNaN(min) && parseFloat(value) < min) {
                    isValid = false;
                    errorMessage = `Value must be at least ${min}.`;
                } else if (!isNaN(max) && parseFloat(value) > max) {
                    isValid = false;
                    errorMessage = `Value must be at most ${max}.`;
                }
            }
        }

        // Apply validation feedback
        this.applyFieldValidation(field, isValid, errorMessage);
        
        return isValid;
    }

    validateForm(form) {
        const fields = form.querySelectorAll('input, textarea, select');
        let isFormValid = true;

        fields.forEach(field => {
            if (!this.validateField(field)) {
                isFormValid = false;
            }
        });

        // Password confirmation validation
        const password = form.querySelector('input[name="password"], input[name="new_password"]');
        const passwordConfirm = form.querySelector('input[name="password_confirmation"], input[name="new_password_confirmation"]');
        
        if (password && passwordConfirm && password.value && passwordConfirm.value) {
            if (password.value !== passwordConfirm.value) {
                this.applyFieldValidation(passwordConfirm, false, 'Passwords do not match.');
                isFormValid = false;
            }
        }

        // Scroll to the first error field if form is invalid
        if (!isFormValid) {
            const firstInvalidField = form.querySelector('.is-invalid');
            if (firstInvalidField) {
                firstInvalidField.focus();
                firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        return isFormValid;
    }

    clearFieldValidation(field) {
        field.classList.remove('is-valid', 'is-invalid');
        
        // Find and remove existing feedback elements
        const parentElement = field.parentElement;
        const feedback = parentElement.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.remove();
        }
    }

    applyFieldValidation(field, isValid, message) {
        // Add appropriate class based on validation result
        field.classList.add(isValid ? 'is-valid' : 'is-invalid');
        
        // Add error message for invalid fields
        if (!isValid) {
            const parentElement = field.parentElement;
            let feedback = parentElement.querySelector('.invalid-feedback');
            
            // Create feedback element if it doesn't exist
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                parentElement.appendChild(feedback);
            }
            
            feedback.textContent = message;
            feedback.style.display = 'block';
        }
    }
}

// Fix asterisk positioning for required fields
function fixAsteriskPositioning() {
    const requiredLabels = document.querySelectorAll('label[for]');
    requiredLabels.forEach(label => {
        const forAttribute = label.getAttribute('for');
        if (forAttribute) {
            const field = document.getElementById(forAttribute);
            if (field && field.hasAttribute('required')) {
                label.classList.add('required-field');
            }
        }
    });
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new FormValidator();
    
    // Apply styling for required fields
    fixAsteriskPositioning();
});