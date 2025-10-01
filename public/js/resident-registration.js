/**
 * Resident Registration Form - Shared JavaScript
 * Handles validation, form interactions, and Material Kit integration
 */

(function() {
  'use strict';

  // Initialize when DOM is ready
  document.addEventListener('DOMContentLoaded', function() {
    initializeMaterialKit();
    initializeFormValidation();
    initializeDynamicFields();
  });

  /**
   * Initialize Material Kit components
   */
  function initializeMaterialKit() {
    // Fix Material Kit floating label overlap on reload with value
    document.querySelectorAll('.input-group-dynamic input, .input-group-dynamic textarea').forEach(function(input) {
      if (input.value && input.value.trim()) {
        input.parentElement.classList.add('is-filled');
      } else {
        input.parentElement.classList.remove('is-filled');
      }

      // Add event listeners for floating labels
      input.addEventListener('focus', function() {
        this.parentElement.classList.add('is-focused');
      });

      input.addEventListener('blur', function() {
        this.parentElement.classList.remove('is-focused');
        if (this.value && this.value.trim()) {
          this.parentElement.classList.add('is-filled');
        } else {
          this.parentElement.classList.remove('is-filled');
        }
      });
    });

    // Initialize Bootstrap Select if available (check for jQuery first)
    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.selectpicker !== 'undefined') {
      jQuery('.selectpicker').selectpicker('refresh');
    }
  }

  /**
   * Initialize form validation
   */
  function initializeFormValidation() {
    const forms = document.querySelectorAll('form[id*="residentPreReg"]');
    
    forms.forEach(function(form) {
      // Add validation on input/change
      const inputs = form.querySelectorAll('input, select, textarea');
      
      inputs.forEach(function(input) {
        // Skip validation on page load - only validate after user interaction
        let hasInteracted = false;
        
        // Mark field as interacted on focus
        input.addEventListener('focus', function() {
          hasInteracted = true;
        });
        
        // Validate on blur for all fields (only if user has interacted)
        input.addEventListener('blur', function() {
          if (hasInteracted) {
            validateField(this);
          }
        });

        // Validate on change for select fields (only if user has interacted)
        if (input.tagName === 'SELECT') {
          input.addEventListener('change', function() {
            if (hasInteracted) {
              validateField(this);
            }
          });
        }

        // Real-time validation for text inputs on typing
        if (input.type === 'text' || input.type === 'email' || input.type === 'tel') {
          input.addEventListener('input', function() {
            // Only validate if field already has a validation state
            if (this.classList.contains('is-invalid') || this.classList.contains('is-valid')) {
              validateField(this);
            }
          });
        }
      });

      // Validate on form submit
      form.addEventListener('submit', function(e) {
        let isValid = true;
        
        inputs.forEach(function(input) {
          if (!validateField(input)) {
            isValid = false;
          }
        });

        if (!isValid) {
          e.preventDefault();
          // Scroll to first error
          const firstError = form.querySelector('.is-invalid');
          if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstError.focus();
          }
        }
      });
    });
  }

  /**
   * Validate individual field
   */
  function validateField(field) {
    const value = field.value ? field.value.trim() : '';
    const isRequired = field.hasAttribute('required');
    let isValid = true;
    let errorMessage = '';

    console.log('Validating field:', field.name, 'Value:', value, 'Required:', isRequired);

    // Remove existing validation classes first
    field.classList.remove('is-valid', 'is-invalid');
    removeErrorMessage(field);

    // Skip empty optional fields
    if (!isRequired && !value) {
      console.log('Skipping optional empty field:', field.name);
      return true;
    }

    // Check if required field is empty
    if (isRequired && !value) {
      isValid = false;
      errorMessage = 'This field is required.';
      console.log('Required field empty:', field.name);
    }
    
    // Validate only if there's a value
    if (value) {
      // Name validation
      if (field.name === 'first_name' || field.name === 'last_name') {
        if (!/^[a-zA-Z\s\-'.]+$/.test(value)) {
          isValid = false;
          errorMessage = 'Please enter a valid name (letters only).';
        }
      }

      // Email validation
      if (field.type === 'email') {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(value)) {
          isValid = false;
          errorMessage = 'Please enter a valid email address.';
        }
      }

      // Phone validation (Philippine format)
      if (field.type === 'tel' || field.name === 'contact_number' || field.name === 'emergency_contact_number') {
        const phonePattern = /^09\d{9}$/;
        if (!phonePattern.test(value)) {
          isValid = false;
          errorMessage = 'Please enter a valid Philippine phone number (09XXXXXXXXX).';
        }
      }

      // Date validation (birthdate cannot be in future)
      if (field.type === 'date' && field.name === 'birthdate') {
        const selectedDate = new Date(value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (selectedDate >= today) {
          isValid = false;
          errorMessage = 'Birthdate cannot be in the future.';
        }
        
        // Check minimum age
        const age = today.getFullYear() - selectedDate.getFullYear();
        if (age > 150) {
          isValid = false;
          errorMessage = 'Please enter a valid birthdate.';
        }
      }
    }

    // Apply validation state
    if (isValid && value) {
      field.classList.add('is-valid');
      console.log('Field is VALID:', field.name, 'Classes:', field.className);
    } else if (!isValid) {
      field.classList.add('is-invalid');
      showErrorMessage(field, errorMessage);
      console.log('Field is INVALID:', field.name, 'Error:', errorMessage, 'Classes:', field.className);
    }

    return isValid;
  }

  /**
   * Show error message below field
   */
  function showErrorMessage(field, message) {
    removeErrorMessage(field);
    
    const feedback = document.createElement('div');
    feedback.className = 'invalid-feedback d-block';
    feedback.textContent = message;
    
    field.parentElement.appendChild(feedback);
  }

  /**
   * Remove error message
   */
  function removeErrorMessage(field) {
    const existingFeedback = field.parentElement.querySelector('.invalid-feedback:not([data-server])');
    if (existingFeedback) {
      existingFeedback.remove();
    }
  }

  /**
   * Initialize dynamic field behavior
   */
  function initializeDynamicFields() {
    // Citizenship country field visibility
    const citizenshipType = document.getElementById('citizenship_type');
    if (citizenshipType) {
      citizenshipType.addEventListener('change', function() {
        const countryField = document.querySelector('input[name="citizenship_country"]');
        const countryRequired = document.querySelector('.citizenship-country-required');
        
        if (this.value === 'DUAL' || this.value === 'FOREIGN') {
          if (countryField) {
            countryField.setAttribute('required', 'required');
            if (countryRequired) countryRequired.style.display = 'inline';
          }
        } else {
          if (countryField) {
            countryField.removeAttribute('required');
            countryField.classList.remove('is-invalid', 'is-valid');
            if (countryRequired) countryRequired.style.display = 'none';
            removeErrorMessage(countryField);
          }
        }
      });
      
      // Trigger on page load
      citizenshipType.dispatchEvent(new Event('change'));
    }

    // Age calculation from birthdate
    const birthdateField = document.getElementById('birthdate');
    if (birthdateField) {
      birthdateField.addEventListener('change', function() {
        const birthdate = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - birthdate.getFullYear();
        const monthDiff = today.getMonth() - birthdate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthdate.getDate())) {
          age--;
        }
        
        // You can use this age value for other logic
        console.log('Calculated age:', age);
      });
    }
  }

  /**
   * Utility: Format phone number
   */
  function formatPhoneNumber(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.startsWith('63')) {
      value = '+' + value;
    } else if (value.startsWith('09')) {
      value = value;
    }
    input.value = value;
  }

  // Export functions for external use if needed
  window.ResidentRegistration = {
    validateField: validateField,
    initializeMaterialKit: initializeMaterialKit
  };

})();
