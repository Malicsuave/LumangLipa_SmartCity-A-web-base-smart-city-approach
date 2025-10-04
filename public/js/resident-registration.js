/**
 * Unified Resident Registration Form JavaScript
 * 
 * This file handles validation, form interactions, and Material Kit integration
 * for both regular resident registration and senior citizen registration forms.
 * 
 * Features:
 * - Automatic form detection (residentPreReg* and seniorPreReg* forms)
 * - Universal validation system supporting both Bootstrap invalid-feedback and custom validation-error divs
 * - Server-side error handling with clean page load (errors show only after user interaction)
 * - Age validation (1+ for regular residents, 60+ for senior citizens)
 * - Citizenship country conditional validation
 * - Phone number validation (Philippine format)
 * - Email validation
 * - Step 3 photo/signature upload functionality
 * - Material Kit floating label integration
 * 
 * Usage in Blade templates:
 * 1. Include in layout: <script src="{{ asset('js/resident-registration.js') }}"></script>
 * 2. For enhanced validation, add to form page:
 *    <script>
 *      window.serverErrors = @json($errors->toArray());
 *      window.hasFormSubmission = {{ old('_token') ? 'true' : 'false' }};
 *    </script>
 * 
 * The script automatically detects form types and applies appropriate validation rules.
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

    // Initialize Bootstrap Select if available
    if (window.jQuery && window.jQuery.fn && window.jQuery.fn.selectpicker) {
      window.jQuery('.selectpicker').selectpicker('refresh');
    }
  }

  /**
   * Initialize form validation
   */
  function initializeFormValidation() {
    const forms = document.querySelectorAll('form[id*="residentPreReg"], form[id*="seniorPreReg"]');
    
    forms.forEach(function(form) {
      // Hide server-side validation errors on page load (they'll show after interaction)
      hideServerSideErrors(form);
      
      // Add validation on input/change
      const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
      
      inputs.forEach(function(input) {
        // Mark field as "touched" when user interacts with it
        let touched = false;
        
        // Validate on blur for required fields
        input.addEventListener('blur', function() {
          touched = true;
          validateField(this);
        });

        // Validate on change for select fields - only after user makes a selection
        if (input.tagName === 'SELECT') {
          input.addEventListener('change', function() {
            touched = true;
            validateField(this);
          });
          
          // For select fields, clear errors on focus but don't validate
          input.addEventListener('focus', function() {
            touched = true;
            clearAllErrorsForField(this.name || this.id);
          });
          
          // Skip the general focus handler for select fields
          return;
        }

        // Real-time validation for text inputs
        if (input.type === 'text' || input.type === 'email' || input.type === 'tel') {
          input.addEventListener('input', function() {
            if (touched || this.classList.contains('is-invalid') || this.classList.contains('is-valid')) {
              validateField(this);
            }
          });
        }
        
        // Show server-side error when user focuses on the field
        input.addEventListener('focus', function() {
          if (!touched && this.classList.contains('is-invalid')) {
            // Keep the server-side error visible now that user is interacting
            const feedback = this.parentElement.querySelector('.invalid-feedback[data-server]');
            if (feedback) {
              feedback.style.display = 'block';
            }
          }
        });
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
    const value = field.value.trim();
    const isRequired = field.hasAttribute('required');
    let isValid = true;
    let errorMessage = '';

    // Remove existing validation classes
    field.classList.remove('is-valid', 'is-invalid');

    // Skip validation for empty optional fields
    if (!isRequired && !value) {
      removeErrorMessage(field);
      return true;
    }

    // Check if field is required and empty
    if (isRequired && !value) {
      isValid = false;
      errorMessage = 'This field is required.';
    }

    // Email validation
    if (field.type === 'email' && value) {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(value)) {
        isValid = false;
        errorMessage = 'Please enter a valid email address.';
      }
    }

    // Phone validation (Philippine format)
    if ((field.type === 'tel' || field.name === 'contact_number') && value) {
      const phonePattern = /^(09|\+639)\d{9}$/;
      const cleanValue = value.replace(/[-\s]/g, '');
      if (!phonePattern.test(cleanValue)) {
        isValid = false;
        errorMessage = 'Please enter a valid Philippine phone number (09XXXXXXXXX).';
      }
    }

    // Date validation (not in future for birthdate)
    if (field.type === 'date' && field.name === 'birthdate' && value) {
      const selectedDate = new Date(value);
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      if (selectedDate > today) {
        isValid = false;
        errorMessage = 'Birthdate cannot be in the future.';
      }
      
      // Check minimum age based on form type
      const isSeniorForm = document.querySelector('form[id*="seniorPreReg"]');
      if (isSeniorForm) {
        // Senior citizen must be 60+
        const sixtyYearsAgo = new Date();
        sixtyYearsAgo.setFullYear(sixtyYearsAgo.getFullYear() - 60);
        if (selectedDate > sixtyYearsAgo) {
          isValid = false;
          errorMessage = 'You must be at least 60 years old to register as a senior citizen.';
        }
      } else {
        // Regular resident must be at least 1 year old
        const oneYearAgo = new Date();
        oneYearAgo.setFullYear(oneYearAgo.getFullYear() - 1);
        if (selectedDate > oneYearAgo) {
          isValid = false;
          errorMessage = 'Please enter a valid birthdate.';
        }
      }
    }

    // Select field validation - only show error if user has interacted and field is empty
    if (field.tagName === 'SELECT' && isRequired) {
      if (!value) {
        isValid = false;
        errorMessage = 'This field is required.';
      }
    }

    // Apply validation state
    if (isValid && value) {
      field.classList.add('is-valid');
      removeErrorMessage(field);
    } else if (!isValid) {
      field.classList.add('is-invalid');
      showErrorMessage(field, errorMessage);
    } else {
      removeErrorMessage(field);
    }

    return isValid;
  }

  /**
   * Show error message below field
   */
  function showErrorMessage(field, message) {
    // Remove all existing error messages first
    removeErrorMessage(field);
    
    // Only create one if none exists
    const existingFeedback = field.parentElement.querySelector('.invalid-feedback');
    if (!existingFeedback) {
      const feedback = document.createElement('div');
      feedback.className = 'invalid-feedback';
      feedback.textContent = message;
      feedback.style.display = 'block';
      feedback.setAttribute('data-client', 'true'); // Mark as client-created
      
      field.parentElement.appendChild(feedback);
    }
  }

  /**
   * Remove error message
   */
  function removeErrorMessage(field) {
    const allFeedback = field.parentElement.querySelectorAll('.invalid-feedback');
    allFeedback.forEach(function(feedback) {
      feedback.remove();
    });
  }

  /**
   * Show validation error (supports both systems)
   */
  function showValidationError(fieldName, message) {
    const input = document.querySelector(`[name="${fieldName}"]`) || document.getElementById(fieldName);
    
    if (input) {
      // First, aggressively clear ALL existing errors for this field
      clearAllErrorsForField(fieldName);
      
      // Then add validation classes
      input.classList.add('is-invalid');
      input.classList.remove('is-valid');
      
      // Try custom validation error div first (senior citizen forms)
      const errorDiv = document.querySelector(`[data-field="${fieldName}"]`);
      if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
      } else {
        // Fall back to standard invalid-feedback approach
        showErrorMessage(input, message);
      }
    }
  }
  
  /**
   * Clear validation error (supports both systems)
   */
  function clearValidationError(fieldName) {
    const input = document.querySelector(`[name="${fieldName}"]`) || document.getElementById(fieldName);
    
    if (input) {
      input.classList.remove('is-invalid');
      
      // Clear all possible error sources for this field
      clearAllErrorsForField(fieldName);
    }
  }
  
  /**
   * Clear all possible error sources for a specific field
   */
  function clearAllErrorsForField(fieldName) {
    const input = document.querySelector(`[name="${fieldName}"]`) || document.getElementById(fieldName);
    if (!input) return;
    
    // Remove validation classes from the input
    input.classList.remove('is-invalid', 'is-valid');
    
    // Clear custom validation error div (senior citizen forms)
    const errorDiv = document.querySelector(`[data-field="${fieldName}"]`);
    if (errorDiv) {
      errorDiv.style.display = 'none';
      errorDiv.textContent = '';
    }
    
    // Clear any invalid-feedback in the same parent
    const parentElement = input.parentElement;
    const allFeedback = parentElement.querySelectorAll('.invalid-feedback');
    allFeedback.forEach(function(feedback) {
      feedback.remove(); // Completely remove instead of just hiding
    });
    
    // Clear any validation-error elements
    const validationErrors = parentElement.querySelectorAll('.validation-error');
    validationErrors.forEach(function(error) {
      error.style.display = 'none';
      error.textContent = '';
    });
    
    // Clear any elements with data-field attribute matching this field
    const dataFieldErrors = document.querySelectorAll(`[data-field="${fieldName}"]`);
    dataFieldErrors.forEach(function(error) {
      error.style.display = 'none';
      error.textContent = '';
    });
  }
  
  /**
   * Add success styling (supports both systems)
   */
  function addSuccessStyling(fieldName) {
    const input = document.querySelector(`[name="${fieldName}"]`) || document.getElementById(fieldName);
    if (input) {
      input.classList.remove('is-invalid');
      input.classList.add('is-valid');
      clearAllErrorsForField(fieldName);
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
        
        if (this.value === 'DUAL' || this.value === 'FOREIGN' || this.value === 'NATURALIZED') {
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
      
      // DO NOT trigger on page load to prevent validation errors showing immediately
      // citizenshipType.dispatchEvent(new Event('change'));
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

  /**
   * Initialize Step 3 - Photo & Signature Upload
   */
  function initializeStep3PhotoUpload() {
    // Check if we're on step 3 (both resident and senior forms)
    const step3Form = document.getElementById('residentPreRegStep3Form') || document.getElementById('seniorPreRegStep3Form');
    if (!step3Form) return;

    // Get all required elements
    const uploadModeBtn = document.getElementById('upload-mode-btn');
    const cameraModeBtn = document.getElementById('camera-mode-btn');
    const uploadMode = document.getElementById('upload-mode');
    const cameraMode = document.getElementById('camera-mode');
    const photoInput = document.getElementById('photo');
    const capturedPhotoData = document.getElementById('captured-photo-data');
    
    // Restore preview from session if available
    const photoPreviewElement = document.getElementById('photo-preview');
    if (photoPreviewElement && photoPreviewElement.dataset.preview) {
      const previewData = photoPreviewElement.dataset.preview;
      console.log('Photo preview data found:', previewData.substring(0, 50) + '...');
      photoPreviewElement.src = previewData;
      photoPreviewElement.style.display = 'block';
      photoInput.required = false; // Don't require new photo if one exists
      const photoPlaceholder = document.getElementById('photo-placeholder');
      if (photoPlaceholder) {
        photoPlaceholder.style.display = 'none';
      }
    }

    // Camera elements
    const videoElement = document.getElementById('camera-stream');
    const canvasElement = document.getElementById('photo-canvas');
    const captureBtn = document.getElementById('capture-btn');
    const retakeBtn = document.getElementById('retake-btn');
    const usePhotoBtn = document.getElementById('use-photo-btn');
    const cameraError = document.getElementById('camera-error');
    const photoPreview = document.getElementById('photo-preview');
    const photoPlaceholder = document.getElementById('photo-placeholder');
    const capturedPreviewContainer = document.getElementById('captured-preview-container');
    const capturedPreview = document.getElementById('captured-preview');
    const photoQualityBadge = document.getElementById('photo-quality-badge');
    const photoSizeInfo = document.getElementById('photo-size-info');
    const photoSizeText = document.getElementById('photo-size-text');
    const flipCameraBtn = document.getElementById('flip-camera-btn');

    let mediaStream = null;
    let imageCapture = null;
    let currentFacingMode = 'user'; // 'user' = front camera, 'environment' = rear camera

    // Set initial button styles (Upload mode active by default) - override Material Kit CSS
    function setUploadModeActive() {
      uploadModeBtn.style.cssText = 'font-size: 0.75rem !important; padding: 4px 10px !important; line-height: 1.2 !important; background-color: #007bff !important; border: 1px solid #007bff !important; color: white !important;';
      cameraModeBtn.style.cssText = 'font-size: 0.75rem !important; padding: 4px 10px !important; line-height: 1.2 !important; background-color: white !important; border: 1px solid #007bff !important; color: #007bff !important;';
    }

    function setCameraModeActive() {
      cameraModeBtn.style.cssText = 'font-size: 0.75rem !important; padding: 4px 10px !important; line-height: 1.2 !important; background-color: #007bff !important; border: 1px solid #007bff !important; color: white !important;';
      uploadModeBtn.style.cssText = 'font-size: 0.75rem !important; padding: 4px 10px !important; line-height: 1.2 !important; background-color: white !important; border: 1px solid #007bff !important; color: #007bff !important;';
    }

    // Set initial styles
    setUploadModeActive();

    // Switch to upload mode
    uploadModeBtn.addEventListener('click', function() {
      uploadMode.style.display = 'block';
      cameraMode.style.display = 'none';
      setUploadModeActive();
      stopCamera();
      photoInput.required = true;
      capturedPhotoData.value = '';
    });

    // Switch to camera mode
    cameraModeBtn.addEventListener('click', function() {
      uploadMode.style.display = 'none';
      cameraMode.style.display = 'block';
      setCameraModeActive();
      photoInput.required = false;
      videoElement.style.display = 'block';
      captureBtn.style.display = 'none';
      retakeBtn.style.display = 'none';
      cameraError.style.display = 'none';
      capturedPhotoData.value = '';
      startCamera();
    });

    // Flip camera button - toggles between front and rear
    if (flipCameraBtn) {
      const flipIcon = document.getElementById('flip-camera-icon');
      let isFlipped = false;
      
      flipCameraBtn.addEventListener('click', function() {
        // Toggle between front and rear camera
        currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
        
        // Add horizontal flip animation to icon
        if (flipIcon) {
          isFlipped = !isFlipped;
          flipIcon.style.transform = isFlipped ? 'scaleX(-1)' : 'scaleX(1)';
        }
        
        // Restart camera with new facing mode
        stopCamera();
        startCamera();
      });
    }

    // Start camera
    async function startCamera() {
      try {
        cameraError.style.display = 'none';
        
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
          throw new Error('UNSUPPORTED_BROWSER');
        }
        
        if (location.protocol !== 'https:' && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
          throw new Error('HTTPS_REQUIRED');
        }
        
        const constraints = {
          video: {
            width: { ideal: 1280 },
            height: { ideal: 720 },
            facingMode: currentFacingMode // Use current facing mode (user = front, environment = rear)
          }
        };

        mediaStream = await navigator.mediaDevices.getUserMedia(constraints);
        videoElement.srcObject = mediaStream;
        
        const videoTrack = mediaStream.getVideoTracks()[0];
        
        if (typeof ImageCapture !== 'undefined') {
          imageCapture = new ImageCapture(videoTrack);
        } else {
          imageCapture = null;
        }
        
        captureBtn.style.display = 'inline-block';
        retakeBtn.style.display = 'none';
        
      } catch (error) {
        console.error('Camera error:', error);
        
        let errorMessage = '';
        
        if (error.message === 'UNSUPPORTED_BROWSER') {
          errorMessage = '📷 Camera not available. Please click "Upload" button above to select a photo from your device instead.';
        } else if (error.message === 'HTTPS_REQUIRED') {
          errorMessage = '🔒 Camera requires secure connection (HTTPS). Please use "Upload" instead.';
        } else if (error.name === 'NotAllowedError' || error.name === 'PermissionDeniedError') {
          errorMessage = '🚫 Camera access blocked. If using Brave browser, please click the Brave Shield icon and allow camera, or use "Upload" instead.';
        } else if (error.name === 'NotFoundError' || error.name === 'DevicesNotFoundError') {
          errorMessage = '📷 No camera detected. Please use "Upload" to select a photo from your device.';
        } else if (error.name === 'NotReadableError' || error.name === 'TrackStartError') {
          errorMessage = '⚠️ Camera is being used by another app. Please close other apps or use "Upload" instead.';
        } else if (error.name === 'OverconstrainedError' || error.name === 'ConstraintNotSatisfiedError') {
          errorMessage = 'Camera does not meet requirements. Trying with default settings...';
          tryFallbackCamera();
          return;
        } else {
          errorMessage = '❌ Camera unavailable: ' + error.message + '. Please use "Upload" button instead.';
        }
        
        setTimeout(function() {
          uploadModeBtn.click();
        }, 3000);
        
        cameraError.textContent = errorMessage;
        cameraError.style.display = 'block';
      }
    }

    // Fallback camera with simpler constraints
    async function tryFallbackCamera() {
      try {
        const constraints = { video: true };
        mediaStream = await navigator.mediaDevices.getUserMedia(constraints);
        videoElement.srcObject = mediaStream;
        
        const videoTrack = mediaStream.getVideoTracks()[0];
        if (typeof ImageCapture !== 'undefined') {
          imageCapture = new ImageCapture(videoTrack);
        }
        
        captureBtn.style.display = 'inline-block';
        retakeBtn.style.display = 'none';
        cameraError.style.display = 'none';
        
      } catch (error) {
        console.error('Fallback camera error:', error);
        cameraError.textContent = 'Unable to access camera. Please use upload mode.';
        cameraError.style.display = 'block';
      }
    }

    // Stop camera
    function stopCamera() {
      if (mediaStream) {
        mediaStream.getTracks().forEach(track => track.stop());
        mediaStream = null;
        imageCapture = null;
        videoElement.srcObject = null;
      }
    }

    // Capture photo
    captureBtn.addEventListener('click', async function() {
      try {
        let blob;
        
        if (imageCapture) {
          blob = await imageCapture.takePhoto();
        } else {
          canvasElement.width = videoElement.videoWidth;
          canvasElement.height = videoElement.videoHeight;
          const ctx = canvasElement.getContext('2d');
          ctx.drawImage(videoElement, 0, 0);
          
          blob = await new Promise(resolve => {
            canvasElement.toBlob(resolve, 'image/jpeg', 0.9);
          });
        }
        
        const fileSizeKB = (blob.size / 1024).toFixed(2);
        const fileSizeMB = (blob.size / (1024 * 1024)).toFixed(2);
        const sizeDisplay = fileSizeKB > 1024 ? `${fileSizeMB} MB` : `${fileSizeKB} KB`;
        
        const url = URL.createObjectURL(blob);
        
        const img = new Image();
        img.onload = function() {
          canvasElement.width = img.width;
          canvasElement.height = img.height;
          const ctx = canvasElement.getContext('2d');
          ctx.drawImage(img, 0, 0);
          
          const dataURL = canvasElement.toDataURL('image/jpeg', 0.9);
          capturedPhotoData.value = dataURL;
          
          photoPreview.src = dataURL;
          photoPreview.style.display = 'block';
          photoPlaceholder.style.display = 'none';
          
          capturedPreview.src = dataURL;
          capturedPreviewContainer.style.display = 'block';
          
          photoSizeText.textContent = sizeDisplay;
          photoSizeInfo.style.display = 'block';
          
          const isGoodSize = blob.size < 5000000;
          const isGoodResolution = img.width >= 640 && img.height >= 480;
          
          if (isGoodSize && isGoodResolution) {
            photoQualityBadge.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Good Quality';
            photoQualityBadge.style.background = 'rgba(40, 167, 69, 0.9)';
          } else if (!isGoodSize) {
            photoQualityBadge.innerHTML = '<i class="fas fa-exclamation-triangle mr-1"></i> File Too Large';
            photoQualityBadge.style.background = 'rgba(255, 193, 7, 0.9)';
          } else {
            photoQualityBadge.innerHTML = '<i class="fas fa-info-circle mr-1"></i> Low Resolution';
            photoQualityBadge.style.background = 'rgba(255, 193, 7, 0.9)';
          }
          
          URL.revokeObjectURL(url);
        };
        img.src = url;
        
        videoElement.style.display = 'none';
        captureBtn.style.display = 'none';
        retakeBtn.style.display = 'inline-block';
        usePhotoBtn.style.display = 'inline-block';
        
        stopCamera();
        
      } catch (error) {
        console.error('Capture error:', error);
        cameraError.textContent = 'Failed to capture photo. Please try again or use upload mode.';
        cameraError.style.display = 'block';
      }
    });

    // Retake photo
    retakeBtn.addEventListener('click', function() {
      videoElement.style.display = 'block';
      capturedPreviewContainer.style.display = 'none';
      captureBtn.style.display = 'inline-block';
      retakeBtn.style.display = 'none';
      usePhotoBtn.style.display = 'none';
      photoSizeInfo.style.display = 'none';
      capturedPhotoData.value = '';
      startCamera();
    });

    // Use this photo
    usePhotoBtn.addEventListener('click', async function() {
      try {
        const response = await fetch(capturedPhotoData.value);
        const blob = await response.blob();
        const file = new File([blob], 'captured-photo.jpg', { type: 'image/jpeg' });
        
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        photoInput.files = dataTransfer.files;
        photoInput.required = false;
        
        console.log('Photo converted to file successfully:', file);
      } catch (error) {
        console.error('Error converting photo:', error);
      }
      
      // Switch back to upload mode with proper styling
      uploadMode.style.display = 'block';
      cameraMode.style.display = 'none';
      setUploadModeActive();
      stopCamera();
    });

    // Photo upload preview
    photoInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        capturedPhotoData.value = '';
        
        const reader = new FileReader();
        reader.onload = function(e) {
          photoPreview.src = e.target.result;
          photoPreview.style.display = 'block';
          photoPlaceholder.style.display = 'none';
        }
        reader.readAsDataURL(file);
        
        photoInput.setCustomValidity('');
      } else {
        photoPreview.style.display = 'none';
        photoPlaceholder.style.display = 'block';
      }
    });

    // Signature preview
    const signatureInput = document.getElementById('signature');
    const signaturePreviewElement = document.getElementById('signature-preview');
    
    // Restore signature preview from session if available
    if (signaturePreviewElement && signaturePreviewElement.dataset.preview) {
      const previewData = signaturePreviewElement.dataset.preview;
      console.log('Signature preview data found:', previewData.substring(0, 50) + '...');
      signaturePreviewElement.src = previewData;
      signaturePreviewElement.style.display = 'block';
      const signaturePlaceholder = document.getElementById('signature-placeholder');
      if (signaturePlaceholder) {
        signaturePlaceholder.style.display = 'none';
      }
    }
    
    if (signatureInput) {
      signatureInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = function(e) {
            document.getElementById('signature-preview').src = e.target.result;
            document.getElementById('signature-preview').style.display = 'block';
            document.getElementById('signature-placeholder').style.display = 'none';
          }
          reader.readAsDataURL(file);
        }
      });
    }

    // Proof of Residency preview
    const proofInput = document.getElementById('proof_of_residency');
    const proofPreviewElement = document.getElementById('proof-preview');
    
    // Restore proof preview from session if available
    if (proofPreviewElement && proofPreviewElement.dataset.preview) {
      const previewData = proofPreviewElement.dataset.preview;
      console.log('Proof of residency preview data found:', previewData.substring(0, 50) + '...');
      proofPreviewElement.src = previewData;
      proofPreviewElement.style.display = 'block';
      const proofPlaceholder = document.getElementById('proof-placeholder');
      if (proofPlaceholder) {
        proofPlaceholder.style.display = 'none';
      }
    }
    
    if (proofInput) {
      proofInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
          // Check if it's a PDF
          if (file.type === 'application/pdf') {
            // For PDF, show a generic PDF icon/placeholder
            document.getElementById('proof-preview').style.display = 'none';
            document.getElementById('proof-placeholder').innerHTML = '<i class="fas fa-file-pdf fa-4x mb-2 text-danger"></i><p class="mb-0">PDF Document</p><small class="text-success">' + file.name + '</small>';
            document.getElementById('proof-placeholder').style.display = 'block';
          } else {
            // For images, show preview
            const reader = new FileReader();
            reader.onload = function(e) {
              document.getElementById('proof-preview').src = e.target.result;
              document.getElementById('proof-preview').style.display = 'block';
              document.getElementById('proof-placeholder').style.display = 'none';
            }
            reader.readAsDataURL(file);
          }
        }
      });
    }

    // Clean up camera on page unload
    window.addEventListener('beforeunload', stopCamera);

    // Form submission - handle captured photo
    step3Form.addEventListener('submit', async function(e) {
      const capturedData = capturedPhotoData.value;
      
      if (capturedData && !photoInput.files.length) {
        e.preventDefault();
        
        try {
          const response = await fetch(capturedData);
          const blob = await response.blob();
          const file = new File([blob], 'captured-photo.jpg', { type: 'image/jpeg' });
          
          const dataTransfer = new DataTransfer();
          dataTransfer.items.add(file);
          photoInput.files = dataTransfer.files;
          photoInput.required = false;
          
          this.submit();
        } catch (error) {
          console.error('Error converting captured photo:', error);
          alert('Failed to process captured photo. Please try uploading a photo instead.');
        }
        return false;
      }
      
      if (!photoInput.files.length && !capturedData) {
        return true;
      }
    });
  }

  // Initialize Step 3 when DOM is ready
  document.addEventListener('DOMContentLoaded', function() {
    initializeStep3PhotoUpload();
  });

  /**
   * Hide server-side validation errors on page load
   * They will be shown after user interacts with the field
   */
  function hideServerSideErrors(form) {
    // Hide server-side validation errors on initial page load
    const errorFields = form.querySelectorAll('.is-invalid');
    errorFields.forEach(function(field) {
      // Store that this field had a server-side error
      field.setAttribute('data-had-server-error', 'true');
      
      // Remove the is-invalid class from fields that haven't been touched
      field.classList.remove('is-invalid');
      
      // Reset border to normal
      field.style.borderColor = '#d2d6da';
      
      // Hide the server-side error feedback
      const feedback = field.parentElement.querySelector('.invalid-feedback[data-server]');
      if (feedback) {
        feedback.style.display = 'none';
      }
      
      // Also hide the .d-block feedback (Laravel's default)
      const blockFeedback = field.parentElement.querySelector('.invalid-feedback.d-block');
      if (blockFeedback) {
        blockFeedback.style.display = 'none';
        blockFeedback.setAttribute('data-server', 'true');
      }
      
      // Hide any @error directive feedback
      const errorFeedback = field.parentElement.querySelector('.invalid-feedback:not([data-field])');
      if (errorFeedback && !errorFeedback.hasAttribute('data-client')) {
        errorFeedback.style.display = 'none';
        errorFeedback.setAttribute('data-server', 'true');
      }
    });
    
    // SPECIAL FIX: Citizenship type field specifically
    const citizenshipType = form.querySelector('#citizenship_type');
    if (citizenshipType) {
      citizenshipType.classList.remove('is-invalid');
      citizenshipType.style.borderColor = '#d2d6da';
      citizenshipType.style.backgroundColor = '#fff';
      citizenshipType.style.backgroundImage = 'none';
      const feedback = citizenshipType.parentElement.querySelector('.invalid-feedback');
      if (feedback) {
        feedback.style.display = 'none';
      }
    }
    
    // Hide custom validation error containers used in senior citizen forms
    const validationErrors = form.querySelectorAll('.validation-error[data-field]');
    validationErrors.forEach(function(errorDiv) {
      errorDiv.style.display = 'none';
      errorDiv.textContent = '';
    });
    
    // Hide all .invalid-feedback elements that are not custom validation containers
    const allInvalidFeedback = form.querySelectorAll('.invalid-feedback:not([data-field]):not([data-client])');
    allInvalidFeedback.forEach(function(feedback) {
      if (!feedback.hasAttribute('data-server')) {
        feedback.style.display = 'none';
        feedback.setAttribute('data-server', 'true');
      }
    });
  }
  
  /**
   * Run immediately on page load to hide errors ASAP
   */
  (function() {
    // Function to aggressively hide all validation errors
    function hideAllValidationErrors() {
      // Remove all is-invalid classes
      document.querySelectorAll('.is-invalid').forEach(function(element) {
        element.classList.remove('is-invalid');
        element.style.borderColor = '#d2d6da';
        element.style.backgroundColor = '#fff';
        element.style.backgroundImage = 'none';
      });
      
      // Hide ALL invalid-feedback elements regardless of source
      document.querySelectorAll('.invalid-feedback').forEach(function(feedback) {
        feedback.style.display = 'none';
        feedback.textContent = '';
        // Remove dynamically created ones
        if (feedback.hasAttribute('data-client')) {
          feedback.remove();
        }
      });
      
      // Hide ALL validation-error elements (senior citizen forms)
      document.querySelectorAll('.validation-error').forEach(function(error) {
        error.style.display = 'none';
        error.textContent = '';
      });
      
      // Hide ALL elements with data-field attribute (custom validation containers)
      document.querySelectorAll('[data-field]').forEach(function(error) {
        error.style.display = 'none';
        error.textContent = '';
      });
      
      // Hide any other error containers that might exist
      document.querySelectorAll('[class*="error"]').forEach(function(error) {
        if (error.textContent.includes('This field is required') || 
            error.textContent.includes('Please select an option') ||
            error.textContent.includes('required') ||
            error.style.color === 'rgb(220, 53, 69)' || // Bootstrap danger color
            error.style.color === '#dc3545') {
          error.style.display = 'none';
          error.textContent = '';
        }
      });
      
      // Remove any duplicate text nodes that might contain error messages
      document.querySelectorAll('*').forEach(function(element) {
        if (element.childNodes.length > 0) {
          element.childNodes.forEach(function(node) {
            if (node.nodeType === Node.TEXT_NODE && 
                (node.textContent.trim() === 'This field is required.' ||
                 node.textContent.trim() === 'Please select an option.')) {
              node.textContent = '';
            }
          });
        }
      });
      
      // SPECIAL: Clear server errors data that might be causing issues
      if (typeof window.serverErrors !== 'undefined') {
        // Don't completely remove it, but make sure validation doesn't trigger on load
        console.log('Server errors detected, but hiding validation on page load');
      }
    }
    
    // Run immediately multiple times to catch all error sources
    hideAllValidationErrors();
    
    // Run on DOM ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', function() {
        hideAllValidationErrors();
        // Run again after a short delay to catch any delayed errors
        setTimeout(hideAllValidationErrors, 100);
        setTimeout(hideAllValidationErrors, 500);
      });
    } else {
      hideAllValidationErrors();
      setTimeout(hideAllValidationErrors, 100);
      setTimeout(hideAllValidationErrors, 500);
    }
    
    // Run on window load
    window.addEventListener('load', function() {
      hideAllValidationErrors();
      setTimeout(hideAllValidationErrors, 100);
    });
    
    // Run the normal form-specific hiding for registration forms
    const forms = document.querySelectorAll('form[id*="residentPreReg"], form[id*="seniorPreReg"]');
    forms.forEach(function(form) {
      hideServerSideErrors(form);
    });
  })();

  /**
   * Initialize optional field validation (show green checkmark when filled, no error state)
   */
  function initializeOptionalFields() {
    const optionalFields = document.querySelectorAll('.optional-field');
    
    optionalFields.forEach(function(field) {
      // Check initial value on page load
      checkOptionalFieldValue(field);
      
      // Check on input
      field.addEventListener('input', function() {
        checkOptionalFieldValue(this);
      });
      
      // Check on change (for select fields)
      field.addEventListener('change', function() {
        checkOptionalFieldValue(this);
      });
      
      // Check on blur
      field.addEventListener('blur', function() {
        checkOptionalFieldValue(this);
      });
    });
  }
  
  /**
   * Check if optional field has value and add/remove has-value class
   */
  function checkOptionalFieldValue(field) {
    if (field.value && field.value.trim() !== '') {
      field.classList.add('has-value');
    } else {
      field.classList.remove('has-value');
    }
  }

  // Initialize optional fields when DOM is ready
  document.addEventListener('DOMContentLoaded', function() {
    initializeOptionalFields();
  });

  // Export functions for external use if needed
  window.ResidentRegistration = {
    validateField: validateField,
    initializeMaterialKit: initializeMaterialKit,
    initializeStep3PhotoUpload: initializeStep3PhotoUpload,
    initializeOptionalFields: initializeOptionalFields,
    showValidationError: showValidationError,
    clearValidationError: clearValidationError,
    addSuccessStyling: addSuccessStyling
  };

  /**
   * Initialize enhanced validation for forms with server-side errors
   */
  function initializeEnhancedValidation() {
    // Check if we have server errors in the page (this will be set by the Blade template)
    if (typeof window.serverErrors !== 'undefined' && typeof window.hasFormSubmission !== 'undefined') {
      const serverErrors = window.serverErrors;
      const hasFormSubmission = window.hasFormSubmission;
      
      // IMPORTANT: NEVER show any errors on initial page load, even if form was submitted
      // Clear all server errors immediately
      Object.keys(serverErrors).forEach(fieldName => {
        clearAllErrorsForField(fieldName);
      });
      
      // Also clear any validation error containers
      document.querySelectorAll('[data-field]').forEach(function(errorDiv) {
        errorDiv.style.display = 'none';
        errorDiv.textContent = '';
      });

      // Determine form type and required fields
      const isSeniorForm = document.querySelector('form[id*="seniorPreReg"]');
      const isStep2Form = document.querySelector('form[id*="Step2"]');
      
      let requiredFields = [];
      if (isSeniorForm && !isStep2Form) {
        // Senior citizen step 1
        requiredFields = ['type_of_resident', 'first_name', 'last_name', 'birthdate', 'birthplace', 'sex', 'civil_status', 'citizenship_type', 'educational_attainment', 'education_status'];
      } else if (isStep2Form) {
        // Step 2 (both regular and senior)
        requiredFields = ['current_address', 'citizenship'];
      } else {
        // Regular resident step 1
        requiredFields = ['type_of_resident', 'first_name', 'last_name', 'birthdate', 'birthplace', 'sex', 'civil_status', 'citizenship_type', 'educational_attainment', 'education_status'];
      }

      // Initialize step 2 specific validation
      if (isStep2Form) {
        initializeStep2Validation();
      } else {
        // Regular step 1 validation for required fields - only validate after user interaction
        requiredFields.forEach(fieldName => {
          const input = document.querySelector(`[name="${fieldName}"]`) || document.getElementById(fieldName);
          if (input) {
            let hasInteracted = false;
            
            // Clear error on focus and mark as interacted
            input.addEventListener('focus', function() {
              hasInteracted = true;
              clearValidationError(fieldName);
            });

            // Validate on blur or change - only if user has interacted
            const eventType = input.tagName.toLowerCase() === 'select' ? 'change' : 'blur';
            input.addEventListener(eventType, function() {
              if (hasInteracted) {
                if (this.value.trim() === '') {
                  showValidationError(fieldName, 'This field is required.');
                } else {
                  clearValidationError(fieldName);
                  addSuccessStyling(fieldName);
                }
              }
            });

            // For select fields, don't validate on focus - only on change
            if (input.tagName.toLowerCase() === 'select') {
              input.addEventListener('focus', function() {
                hasInteracted = true;
                clearValidationError(fieldName);
              });
            }

            // For text inputs, also validate on input - only if user has interacted
            if (input.tagName.toLowerCase() === 'input') {
              input.addEventListener('input', function() {
                if (hasInteracted && this.value.trim() !== '') {
                  clearValidationError(fieldName);
                  addSuccessStyling(fieldName);
                }
              });
            }
          }
        });
      }

      // Special validation for birthdate with age requirement - only after interaction
      const birthdateInput = document.getElementById('birthdate');
      if (birthdateInput) {
        let birthdateInteracted = false;
        
        birthdateInput.addEventListener('focus', function() {
          birthdateInteracted = true;
        });
        
        birthdateInput.addEventListener('change', function() {
          if (!birthdateInteracted) return; // Don't validate until user interacts
          
          const birthDate = new Date(this.value);
          const today = new Date();
          let age = today.getFullYear() - birthDate.getFullYear();
          const monthDiff = today.getMonth() - birthDate.getMonth();
          
          if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
          }
          
          if (this.value === '') {
            showValidationError('birthdate', 'This field is required.');
          } else if (isSeniorForm && age < 60) {
            showValidationError('birthdate', 'You must be at least 60 years old to register as a senior citizen.');
          } else {
            clearValidationError('birthdate');
            addSuccessStyling('birthdate');
          }
        });
      }

      // Enhanced citizenship country conditional validation
      const citizenshipType = document.getElementById('citizenship_type');
      const citizenshipCountry = document.getElementById('citizenship_country');
      const citizenshipCountryRequired = document.querySelector('.citizenship-country-required');
      
      if (citizenshipType && citizenshipCountry && citizenshipCountryRequired) {
        citizenshipType.addEventListener('change', function() {
          const requiresCountry = ['DUAL', 'NATURALIZED', 'FOREIGN'].includes(this.value);
          
          if (requiresCountry) {
            citizenshipCountryRequired.style.display = 'inline';
            citizenshipCountry.setAttribute('required', 'required');
            citizenshipCountry.classList.remove('optional-field');
            
            // Validate country field if citizenship type requires it
            if (citizenshipCountry.value.trim() === '') {
              showValidationError('citizenship_country', 'Country is required for this citizenship type.');
            } else {
              clearValidationError('citizenship_country');
              addSuccessStyling('citizenship_country');
            }
          } else {
            citizenshipCountryRequired.style.display = 'none';
            citizenshipCountry.removeAttribute('required');
            citizenshipCountry.classList.add('optional-field');
            clearValidationError('citizenship_country');
            citizenshipCountry.classList.remove('is-valid', 'is-invalid');
            citizenshipCountry.value = '';
          }
        });
        
        citizenshipCountry.addEventListener('input', function() {
          const isRequired = citizenshipCountry.hasAttribute('required');
          if (isRequired) {
            if (this.value.trim() === '') {
              showValidationError('citizenship_country', 'Country is required for this citizenship type.');
            } else {
              clearValidationError('citizenship_country');
              addSuccessStyling('citizenship_country');
            }
          } else if (this.value.trim().length > 0) {
            addSuccessStyling('citizenship_country');
          } else {
            this.classList.remove('is-valid', 'is-invalid');
          }
        });

        // DO NOT trigger initial check - this causes the error to show on page load
      }

      // Optional fields validation (show success when filled)
      const optionalFields = ['middle_name', 'suffix', 'religion', 'profession_occupation', 'profession', 'monthly_income', 'education'];
      optionalFields.forEach(fieldName => {
        const input = document.querySelector(`[name="${fieldName}"]`) || document.getElementById(fieldName);
        if (input) {
          const eventType = input.tagName.toLowerCase() === 'select' ? 'change' : 'input';
          input.addEventListener(eventType, function() {
            if (this.value.trim().length > 0) {
              addSuccessStyling(fieldName);
            } else {
              this.classList.remove('is-valid', 'is-invalid');
            }
          });
        }
      });

      // Enhanced form submission validation
      const form = document.querySelector('form[id*="seniorPreReg"]') || document.querySelector('form[id*="residentPreReg"]');
      if (form) {
        form.addEventListener('submit', function(e) {
          let hasErrors = false;

          // Validate all required fields before submission
          requiredFields.forEach(fieldName => {
            const input = document.querySelector(`[name="${fieldName}"]`) || document.getElementById(fieldName);
            if (input && input.value.trim() === '') {
              showValidationError(fieldName, 'This field is required.');
              hasErrors = true;
            }
          });

          // Special validation for birthdate
          if (birthdateInput && birthdateInput.value && isSeniorForm) {
            const birthDate = new Date(birthdateInput.value);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
              age--;
            }
            
            if (age < 60) {
              showValidationError('birthdate', 'You must be at least 60 years old to register as a senior citizen.');
              hasErrors = true;
            }
          }

          // Validate citizenship country if required
          if (citizenshipCountry && citizenshipCountry.hasAttribute('required') && citizenshipCountry.value.trim() === '') {
            showValidationError('citizenship_country', 'Country is required for this citizenship type.');
            hasErrors = true;
          }

          // Step 2 specific validations
          if (isStep2Form) {
            const contactNumber = document.getElementById('contact_number');
            if (contactNumber && contactNumber.value.trim() !== '') {
              if (contactNumber.value.length !== 11 || !contactNumber.value.startsWith('09')) {
                showValidationError('contact_number', 'Contact number must be 11 digits starting with 09.');
                hasErrors = true;
              }
            }

            const emailInput = document.getElementById('email_address');
            if (emailInput && emailInput.value.trim() !== '') {
              const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
              if (!emailRegex.test(emailInput.value)) {
                showValidationError('email_address', 'Please enter a valid email address.');
                hasErrors = true;
              }
            }
          }

          if (hasErrors) {
            e.preventDefault();
            // Scroll to first error
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
              firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
              firstError.focus();
            }
          }
        });
      }
    }
  }

  /**
   * Initialize Step 2 specific validation
   */
  function initializeStep2Validation() {
    // Show server-side error messages on page load
    const allErrorMessages = document.querySelectorAll('.invalid-feedback[data-server]');
    allErrorMessages.forEach(function(errorMsg) {
      if (errorMsg.textContent.trim() !== '') {
        errorMsg.style.setProperty('display', 'block', 'important');
        const input = errorMsg.parentElement.querySelector('input, select, textarea');
        if (input) {
          input.classList.add('is-invalid');
        }
      }
    });

    // Phone number validation
    const contactNumber = document.getElementById('contact_number');
    if (contactNumber) {
      contactNumber.addEventListener('input', function(e) {
        // Remove non-digits
        this.value = this.value.replace(/[^0-9]/g, '');
        // Validate 11-digit number starting with 09
        if (this.value.length === 0) {
          clearValidationError('contact_number');
          this.classList.remove('is-valid', 'is-invalid');
        } else if (this.value.length !== 11) {
          showValidationError('contact_number', 'Contact number must be 11 digits.');
        } else if (!this.value.startsWith('09')) {
          showValidationError('contact_number', 'Contact number must start with 09.');
        } else {
          clearValidationError('contact_number');
          addSuccessStyling('contact_number');
        }
      });
    }

    // Email validation
    const emailInput = document.getElementById('email_address');
    if (emailInput) {
      emailInput.addEventListener('input', function() {
        if (this.value.trim() === '') {
          // Optional field - remove validation styling when empty
          this.classList.remove('is-valid', 'is-invalid');
          clearValidationError('email_address');
        } else {
          const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          const isValid = emailRegex.test(this.value);
          if (isValid) {
            clearValidationError('email_address');
            addSuccessStyling('email_address');
          } else {
            showValidationError('email_address', 'Please enter a valid email address.');
          }
        }
      });
    }

    // Required text fields
    const requiredFields = ['current_address'];
    requiredFields.forEach(function(fieldId) {
      const field = document.getElementById(fieldId);
      if (field) {
        field.addEventListener('input', function() {
          const isValid = this.value.trim().length > 0;
          if (isValid) {
            clearValidationError(fieldId);
            addSuccessStyling(fieldId);
          } else {
            showValidationError(fieldId, 'This field is required.');
          }
        });
        
        field.addEventListener('blur', function() {
          const isValid = this.value.trim().length > 0;
          if (isValid) {
            clearValidationError(fieldId);
            addSuccessStyling(fieldId);
          } else {
            showValidationError(fieldId, 'This field is required.');
          }
        });
      }
    });

    // Required select fields
    const requiredSelects = ['citizenship'];
    requiredSelects.forEach(function(selectId) {
      const select = document.getElementById(selectId);
      if (select) {
        let selectTouched = false;
        
        select.addEventListener('focus', function() {
          selectTouched = true;
          clearValidationError(selectId);
        });
        
        select.addEventListener('change', function() {
          selectTouched = true;
          const isValid = this.value !== '';
          if (isValid) {
            clearValidationError(selectId);
            addSuccessStyling(selectId);
          } else {
            showValidationError(selectId, 'This field is required.');
          }
        });
      }
    });
  }

  // Initialize enhanced validation when DOM is ready
  document.addEventListener('DOMContentLoaded', function() {
    initializeEnhancedValidation();
  });

})();
