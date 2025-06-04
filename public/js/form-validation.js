/**
 * Enhanced Form Validation System
 * Provides real-time client-side validation with security focus
 */

class FormValidator {
    constructor() {
        this.forms = document.querySelectorAll('form[data-validate]');
        this.init();
    }

    init() {
        this.forms.forEach(form => {
            this.setupFormValidation(form);
        });
    }

    setupFormValidation(form) {
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            // Real-time validation on input
            input.addEventListener('input', () => this.validateField(input));
            input.addEventListener('blur', () => this.validateField(input));
            
            // Security enhancements
            this.addSecurityListeners(input);
        });

        // Form submission validation
        form.addEventListener('submit', (e) => {
            if (!this.validateForm(form)) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
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
        }

        // Type-specific validations
        if (value && isValid) {
            switch (fieldType) {
                case 'email':
                    isValid = this.validateEmail(value);
                    errorMessage = isValid ? '' : 'Please enter a valid email address.';
                    break;
                
                case 'password':
                    const passwordResult = this.validatePassword(value, field);
                    isValid = passwordResult.isValid;
                    errorMessage = passwordResult.message;
                    break;
                
                case 'text':
                    if (fieldName === 'name') {
                        isValid = this.validateName(value);
                        errorMessage = isValid ? '' : 'Name can only contain letters, spaces, dots, hyphens, and apostrophes.';
                    }
                    break;
                
                case 'file':
                    if (field.files.length > 0) {
                        const fileResult = this.validateFile(field.files[0], field);
                        isValid = fileResult.isValid;
                        errorMessage = fileResult.message;
                    }
                    break;
                
                case 'textarea':
                    if (fieldName === 'reason' || fieldName === 'denial_reason') {
                        isValid = this.validateTextContent(value);
                        errorMessage = isValid ? '' : 'Please remove any potentially harmful content.';
                    }
                    break;
            }
        }

        // Length validations
        if (value && isValid) {
            const minLength = field.getAttribute('minlength');
            const maxLength = field.getAttribute('maxlength');
            
            if (minLength && value.length < parseInt(minLength)) {
                isValid = false;
                errorMessage = `Must be at least ${minLength} characters.`;
            }
            
            if (maxLength && value.length > parseInt(maxLength)) {
                isValid = false;
                errorMessage = `Must not exceed ${maxLength} characters.`;
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
        const password = form.querySelector('input[name="new_password"]');
        const passwordConfirm = form.querySelector('input[name="new_password_confirmation"]');
        
        if (password && passwordConfirm) {
            if (password.value !== passwordConfirm.value) {
                this.applyFieldValidation(passwordConfirm, false, 'Passwords do not match.');
                isFormValid = false;
            }
        }

        return isFormValid;
    }

    validateEmail(email) {
        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        return emailRegex.test(email) && email.length <= 255;
    }

    validatePassword(password, field) {
        const minLength = 8;
        const hasLetter = /[a-zA-Z]/.test(password);
        const hasNumber = /\d/.test(password);
        const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
        const hasUpperCase = /[A-Z]/.test(password);
        const hasLowerCase = /[a-z]/.test(password);

        if (password.length < minLength) {
            return { isValid: false, message: `Password must be at least ${minLength} characters.` };
        }

        if (!hasLetter) {
            return { isValid: false, message: 'Password must contain at least one letter.' };
        }

        if (!hasNumber) {
            return { isValid: false, message: 'Password must contain at least one number.' };
        }

        if (!hasSpecial) {
            return { isValid: false, message: 'Password must contain at least one special character.' };
        }

        if (!hasUpperCase) {
            return { isValid: false, message: 'Password must contain at least one uppercase letter.' };
        }

        if (!hasLowerCase) {
            return { isValid: false, message: 'Password must contain at least one lowercase letter.' };
        }

        return { isValid: true, message: '' };
    }

    validateName(name) {
        const nameRegex = /^[a-zA-Z\s\.\-\']+$/;
        return nameRegex.test(name) && name.length >= 2 && name.length <= 255;
    }

    validateFile(file, field) {
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        const maxSize = 1024 * 1024; // 1MB
        
        if (!allowedTypes.includes(file.type)) {
            return { isValid: false, message: 'Please select a valid image file (JPG, PNG, or WebP).' };
        }
        
        if (file.size > maxSize) {
            return { isValid: false, message: 'File size must not exceed 1MB.' };
        }
        
        return { isValid: true, message: '' };
    }

    validateTextContent(text) {
        // Check for potentially harmful content
        const suspiciousPatterns = [
            /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi,
            /javascript:/gi,
            /on\w+\s*=/gi,
            /<iframe/gi,
            /<object/gi,
            /<embed/gi
        ];
        
        for (const pattern of suspiciousPatterns) {
            if (pattern.test(text)) {
                return false;
            }
        }
        
        return true;
    }

    addSecurityListeners(input) {
        // Prevent common XSS attempts
        input.addEventListener('paste', (e) => {
            setTimeout(() => {
                const value = input.value;
                if (value && !this.validateTextContent(value)) {
                    input.value = this.sanitizeInput(value);
                    this.applyFieldValidation(input, false, 'Potentially harmful content was removed.');
                }
            }, 10);
        });

        // Rate limiting for form submissions
        if (input.closest('form')) {
            this.addRateLimiting(input.closest('form'));
        }
    }

    sanitizeInput(input) {
        return input
            .replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '')
            .replace(/javascript:/gi, '')
            .replace(/on\w+\s*=/gi, '')
            .replace(/<iframe/gi, '')
            .replace(/<object/gi, '')
            .replace(/<embed/gi, '');
    }

    addRateLimiting(form) {
        if (form.dataset.rateLimited) return;
        
        form.dataset.rateLimited = 'true';
        let submitCount = 0;
        let lastSubmit = 0;
        
        form.addEventListener('submit', (e) => {
            const now = Date.now();
            const timeDiff = now - lastSubmit;
            
            if (timeDiff < 3000) { // 3 seconds between submissions
                submitCount++;
                if (submitCount > 3) {
                    e.preventDefault();
                    this.showAlert('Too many submission attempts. Please wait before trying again.', 'warning');
                    return false;
                }
            } else {
                submitCount = 0;
            }
            
            lastSubmit = now;
        });
    }

    clearFieldValidation(field) {
        field.classList.remove('is-valid', 'is-invalid');
        const feedback = field.parentNode.querySelector('.invalid-feedback, .valid-feedback');
        if (feedback) {
            feedback.textContent = '';
        }
    }

    applyFieldValidation(field, isValid, message) {
        field.classList.remove('is-valid', 'is-invalid');
        field.classList.add(isValid ? 'is-valid' : 'is-invalid');
        
        let feedback = field.parentNode.querySelector('.invalid-feedback');
        if (!feedback && !isValid) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            field.parentNode.appendChild(feedback);
        }
        
        if (feedback) {
            feedback.textContent = message;
            feedback.style.display = isValid ? 'none' : 'block';
        }
    }

    showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;
        
        const container = document.querySelector('.container, .container-fluid') || document.body;
        container.insertBefore(alertDiv, container.firstChild);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new FormValidator();
});

// Additional security measures
document.addEventListener('DOMContentLoaded', () => {
    // Disable right-click on sensitive forms
    document.querySelectorAll('form[data-secure]').forEach(form => {
        form.addEventListener('contextmenu', e => e.preventDefault());
    });
    
    // Clear sensitive form data when page is unloaded
    window.addEventListener('beforeunload', () => {
        document.querySelectorAll('input[type="password"]').forEach(input => {
            input.value = '';
        });
    });
});