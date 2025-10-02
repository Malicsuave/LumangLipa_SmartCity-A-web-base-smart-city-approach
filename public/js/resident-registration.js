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

    // Initialize Bootstrap Select if available
    if (window.jQuery && window.jQuery.fn && window.jQuery.fn.selectpicker) {
      window.jQuery('.selectpicker').selectpicker('refresh');
    }
  }

  /**
   * Initialize form validation
   */
  function initializeFormValidation() {
    const forms = document.querySelectorAll('form[id*="residentPreReg"]');
    
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

        // Validate on change for select fields
        if (input.tagName === 'SELECT') {
          input.addEventListener('change', function() {
            touched = true;
            validateField(this);
          });
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
      
      // Check minimum age (e.g., must be at least 1 year old)
      const oneYearAgo = new Date();
      oneYearAgo.setFullYear(oneYearAgo.getFullYear() - 1);
      if (selectedDate > oneYearAgo) {
        isValid = false;
        errorMessage = 'Please enter a valid birthdate.';
      }
    }

    // Select field validation
    if (field.tagName === 'SELECT' && isRequired && !value) {
      isValid = false;
      errorMessage = 'Please select an option.';
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
    removeErrorMessage(field);
    
    const feedback = document.createElement('div');
    feedback.className = 'invalid-feedback';
    feedback.textContent = message;
    feedback.style.display = 'block';
    
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

  /**
   * Initialize Step 3 - Photo & Signature Upload
   */
  function initializeStep3PhotoUpload() {
    // Check if we're on step 3
    const step3Form = document.getElementById('residentPreRegStep3Form');
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

    // Set initial button styles (Upload mode active by default)
    uploadModeBtn.style.backgroundColor = '#007bff';
    uploadModeBtn.style.borderColor = '#007bff';
    uploadModeBtn.style.color = 'white';
    uploadModeBtn.style.border = '1px solid #007bff';

    cameraModeBtn.style.backgroundColor = 'white';
    cameraModeBtn.style.borderColor = '#007bff';
    cameraModeBtn.style.color = '#007bff';
    cameraModeBtn.style.border = '1px solid #007bff';

    // Switch to upload mode
    uploadModeBtn.addEventListener('click', function() {
      uploadMode.style.display = 'block';
      cameraMode.style.display = 'none';
      uploadModeBtn.style.backgroundColor = '#007bff';
      uploadModeBtn.style.borderColor = '#007bff';
      uploadModeBtn.style.color = 'white';
      uploadModeBtn.style.border = '1px solid #007bff';
      cameraModeBtn.style.backgroundColor = 'white';
      cameraModeBtn.style.borderColor = '#007bff';
      cameraModeBtn.style.color = '#007bff';
      cameraModeBtn.style.border = '1px solid #007bff';
      stopCamera();
      photoInput.required = true;
      capturedPhotoData.value = '';
    });

    // Switch to camera mode
    cameraModeBtn.addEventListener('click', function() {
      uploadMode.style.display = 'none';
      cameraMode.style.display = 'block';
      cameraModeBtn.style.backgroundColor = '#007bff';
      cameraModeBtn.style.borderColor = '#007bff';
      cameraModeBtn.style.color = 'white';
      cameraModeBtn.style.border = '1px solid #007bff';
      uploadModeBtn.style.backgroundColor = 'white';
      uploadModeBtn.style.borderColor = '#007bff';
      uploadModeBtn.style.color = '#007bff';
      uploadModeBtn.style.border = '1px solid #007bff';
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
      
      uploadModeBtn.click();
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
    });
    
    // SPECIAL FIX: Citizenship type field specifically
    const citizenshipType = document.querySelector('#citizenship_type');
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
  }
  
  /**
   * Run immediately on page load to hide errors ASAP
   */
  (function() {
    const forms = document.querySelectorAll('form[id*="residentPreReg"]');
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
    initializeOptionalFields: initializeOptionalFields
  };

})();
