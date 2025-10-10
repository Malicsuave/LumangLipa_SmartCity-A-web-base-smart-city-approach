@extends('layouts.public.resident-registration')

@section('title', 'Senior Citizen Pre-Registration - Step 3')

@section('form-title', 'Senior Citizen Pre-Registration')
@section('step-indicator', 'Step 3 of 5')

@section('form-content')
<div class="card-header bg-white border-0 pb-0">
  <h5 class="personal-header"><i class="fas fa-camera mr-2"></i>Photo & Documents Upload</h5>
  <small class="text-muted">Upload your photo, signature (optional), and proof of residency document.</small>
</div>
<form role="form" id="seniorPreRegStep3Form" method="POST" action="{{ route('public.senior-registration.step3.store') }}" enctype="multipart/form-data" autocomplete="off">
  @csrf
  <div class="card-body">
    <div class="alert alert-info alert-dismissible fade show" role="alert" style="color: white; position: relative;">
      <i class="fas fa-info-circle"></i> 
      <strong>Required:</strong> Please upload your photo and proof of residency document. The signature is optional.
      <button type="button" class="close text-white" data-dismiss="alert" aria-label="Close" style="position: absolute; top: 10px; right: 15px; background: none; border: none; font-size: 1.2rem; cursor: pointer; opacity: 0.8;">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <!-- Photo Upload -->
    <div class="row mb-4">
      <div class="col-md-6">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <h6 class="mb-0" style="line-height: 1.8;"><i class="fas fa-portrait mr-2"></i>Photo <span class="text-danger">*</span></h6>
          
          <!-- Camera/Upload Toggle Buttons -->
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-sm" id="upload-mode-btn" style="font-size: 0.75rem; padding: 4px 10px; line-height: 1.2;">
              <i class="fas fa-upload" style="font-size: 0.7rem;"></i> Upload
            </button>
            <button type="button" class="btn btn-sm" id="camera-mode-btn" style="font-size: 0.75rem; padding: 4px 10px; line-height: 1.2;">
              <i class="fas fa-camera" style="font-size: 0.7rem;"></i> Take Photo
            </button>
          </div>
        </div>

        <!-- Upload Mode -->
        <div id="upload-mode" style="display: block;">
          <div class="text-center mb-3">
            <div class="photo-preview-container" style="width: 300px; height: 300px; margin: 0 auto; border: 2px dashed #ddd; border-radius: 10px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
              <img id="photo-preview" src="#" alt="Photo Preview" 
                   @if(isset($step3['photo']) && $step3['photo']) 
                   style="display: block; width: 100%; height: 100%; object-fit: cover;"
                   data-preview="{{ asset('storage/' . $step3['photo']) }}"
                   @else
                   style="display: none; width: 100%; height: 100%; object-fit: cover;"
                   @endif>
              <div id="photo-placeholder" style="text-align: center; color: #6c757d; @if(isset($step3['photo']) && $step3['photo']) display: none; @endif">
                <i class="fas fa-user fa-4x mb-2"></i>
                <p class="mb-0">2x2 ID Photo</p>
                <small class="text-muted">Required</small>
              </div>
            </div>
            @if(isset($step3['photo']) && $step3['photo'])
            <small class="text-success d-block mt-2">
              <i class="fas fa-check-circle"></i> Photo previously uploaded
            </small>
            @endif
          </div>
          <div class="form-group mb-0">
            <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                   id="photo" name="photo" accept="image/*" 
                   @if(!isset($step3['photo']) || !$step3['photo']) required @endif>
            <small class="form-text text-muted d-block mt-2">
              <i class="fas fa-info-circle text-info"></i> Required - JPG, PNG (Max 2MB)
            </small>
            @error('photo')
              <div class="invalid-feedback d-block" data-server>{{ $message }}</div>
            @enderror
          </div>
        </div>

        <!-- Camera Mode -->
        <div id="camera-mode" style="display: none;">
          <div class="text-center mb-3">
            <div style="width: 100%; max-width: 400px; margin: 0 auto; border: 2px solid #ddd; border-radius: 10px; overflow: hidden; background: #000; position: relative;">
              <video id="camera-stream" autoplay playsinline style="width: 100%; height: auto; display: block;"></video>
              <canvas id="photo-canvas" style="display: none;"></canvas>
              
              <!-- Camera Flip Button (Overlaid on video) -->
              <button type="button" id="flip-camera-btn" title="Switch Camera" style="position: absolute; top: 10px; right: 10px; z-index: 10; width: 50px; height: 50px; border-radius: 50%; background: rgba(80, 80, 80, 0.7); border: none; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; padding: 0;">
                <svg id="flip-camera-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" style="width: 36px; height: 36px; fill: white; transition: transform 0.6s ease;">
                  <!-- Camera body (center) -->
                  <rect x="32" y="40" width="36" height="27" rx="3" fill="white"/>
                  <rect x="40" y="33" width="20" height="7" rx="2" fill="white"/>
                  <circle cx="50" cy="53.5" r="8" fill="rgba(80,80,80,0.8)"/>
                  
                  <!-- Circular arrows around camera -->
                  <!-- Top-right curved arrow -->
                  <path d="M 65 25 A 20 20 0 0 1 75 50" stroke="white" stroke-width="3" fill="none" stroke-linecap="round"/>
                  <path d="M 77 48 L 75 50 L 73 48 Z" fill="white"/>
                  
                  <!-- Bottom-left curved arrow -->
                  <path d="M 35 75 A 20 20 0 0 1 25 50" stroke="white" stroke-width="3" fill="none" stroke-linecap="round"/>
                  <path d="M 23 52 L 25 50 L 27 52 Z" fill="white"/>
                </svg>
              </button>
              
              <!-- Captured Photo Preview with Quality Indicator -->
              <div id="captured-preview-container" style="display: none; position: relative;">
                <img id="captured-preview" style="width: 100%; height: auto; display: block;">
                <div id="photo-quality-badge" style="position: absolute; top: 10px; right: 10px; padding: 8px 16px; background: rgba(40, 167, 69, 0.9); color: white; border-radius: 20px; font-weight: bold; font-size: 0.85rem;">
                  <i class="fas fa-check-circle mr-1"></i> Good Quality
                </div>
              </div>
            </div>
            
            <!-- Photo Size Info -->
            <small id="photo-size-info" class="text-muted" style="display: none; margin-top: 8px;">
              <i class="fas fa-info-circle"></i> Photo size: <span id="photo-size-text">0 KB</span>
            </small>
          </div>
          
          <div class="text-center">
            <button type="button" class="btn btn-success btn-lg" id="capture-btn" style="display: none;">
              <i class="fas fa-camera mr-2"></i> Capture Photo
            </button>
            <button type="button" class="btn btn-warning" id="retake-btn" style="display: none;">
              <i class="fas fa-redo mr-2"></i> Retake Photo
            </button>
            <button type="button" class="btn btn-success" id="use-photo-btn" style="display: none;">
              <i class="fas fa-check mr-2"></i> Use This Photo
            </button>
            <div id="camera-error" class="alert alert-danger mt-3" style="display: none;"></div>
          </div>
        </div>

        <!-- Hidden input to store captured photo -->
        <input type="hidden" id="captured-photo-data" name="captured_photo_data">
      </div>

      <div class="col-md-6">
        <h6 class="mb-3"><i class="fas fa-signature mr-2"></i>Signature <small class="text-muted">(Optional)</small></h6>
        <div class="text-center mb-3">
                    <div class="signature-preview-container" style="width: 300px; height: 150px; margin: 0 auto; border: 2px dashed #ddd; border-radius: 10px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
            <img id="signature-preview" src="#" alt="Signature Preview" 
                 @if(isset($step3['signature']) && $step3['signature']) 
                 style="display: block; width: 100%; height: 100%; object-fit: contain;"
                 data-preview="{{ asset('storage/' . $step3['signature']) }}"
                 @else
                 style="display: none; width: 100%; height: 100%; object-fit: contain;"
                 @endif>
            <div id="signature-placeholder" style="text-align: center; color: #6c757d; @if(isset($step3['signature']) && $step3['signature']) display: none; @endif">
              <i class="fas fa-signature mr-2"></i>
              <p class="mb-0">Your Signature</p>
              <small class="text-muted">Optional</small>
            </div>
          </div>
          @if(isset($step3['signature']) && $step3['signature'])
          <small class="text-success d-block mt-2">
            <i class="fas fa-check-circle"></i> Signature previously uploaded
          </small>
          @endif
        </div>
        <div class="form-group mb-0">
          <input type="file" class="form-control @error('signature') is-invalid @enderror" 
                 id="signature" name="signature" accept="image/*">
          <small class="form-text text-muted d-block mt-2">
            <i class="fas fa-info-circle text-info"></i> Optional - JPG, PNG (Max 1MB)
          </small>
          @error('signature')
            <div class="invalid-feedback d-block" data-server>{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>

    <!-- Proof of Residency Upload -->
    <div class="row mb-4">
      <div class="col-md-12">
        <h6 class="mb-3"><i class="fas fa-file-alt mr-2"></i>Proof of Residency <span class="text-danger">*</span></h6>
        
        <div class="row">
          <div class="col-md-6 mx-auto">
            <div class="text-center mb-3">
              <div class="document-preview-container" style="width: 100%; max-width: 400px; height: 300px; margin: 0 auto; border: 2px dashed #ddd; border-radius: 10px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                <img id="proof-preview" src="#" alt="Proof of Residency Preview" 
                     @if(isset($step3['proof_of_residency']) && $step3['proof_of_residency']) 
                     style="display: block; width: 100%; height: 100%; object-fit: contain;"
                     data-preview="{{ asset('storage/' . $step3['proof_of_residency']) }}"
                     @else
                     style="display: none; width: 100%; height: 100%; object-fit: contain;"
                     @endif>
                <div id="proof-placeholder" style="text-align: center; color: #6c757d; padding: 20px; @if(isset($step3['proof_of_residency']) && $step3['proof_of_residency']) display: none; @endif">
                  <i class="fas fa-file-upload fa-3x mb-3"></i>
                  <p class="mb-2" style="font-weight: 600; font-size: 0.95rem;">Proof of Residency</p>
                  <small class="text-muted d-block mb-2">Required Document</small>
                  <div style="text-align: left; font-size: 0.75rem; line-height: 1.5; margin-top: 10px;">
                    <strong class="d-block mb-1" style="font-size: 0.8rem;">Accepted documents:</strong>
                    <ul style="padding-left: 15px; margin-bottom: 0;">
                      <li>Utility bills (Electric, Water)</li>
                      <li>Barangay Clearance</li>
                      <li>Lease/Rental Agreement</li>
                      <li>Tax Declaration</li>
                      <li>Property Title</li>
                    </ul>
                  </div>
                </div>
              </div>
              @if(isset($step3['proof_of_residency']) && $step3['proof_of_residency'])
              <small class="text-success d-block mt-2">
                <i class="fas fa-check-circle"></i> Document previously uploaded
              </small>
              @endif
            </div>
            <div class="form-group mb-0">
              <input type="file" class="form-control @error('proof_of_residency') is-invalid @enderror" 
                     id="proof_of_residency" name="proof_of_residency" accept="image/*,application/pdf" 
                     @if(!isset($step3['proof_of_residency']) || !$step3['proof_of_residency']) required @endif>
              <small class="form-text text-muted d-block mt-2">
                <i class="fas fa-info-circle text-info"></i> Required - JPG, PNG, PDF (Max 5MB)
              </small>
              @error('proof_of_residency')
                <div class="invalid-feedback d-block" data-server>{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Upload Guidelines -->
    <div class="row mb-4">
      <div class="col-md-12">
        <div class="card bg-light">
          <div class="card-body">
            <h6 class="mb-3"><i class="fas fa-lightbulb text-warning"></i> Upload Guidelines:</h6>
            <div class="row">
              <div class="col-md-4">
                <strong>Photo Requirements:</strong>
                <ul class="small mb-0">
                  <li>Recent photo (taken within the last 6 months)</li>
                  <li>Clear face, looking straight at camera</li>
                  <li>Plain background (preferably white or light colored)</li>
                  <li>No sunglasses or hat</li>
                  <li>Formal or semi-formal attire</li>
                </ul>
              </div>
              <div class="col-md-4">
                <strong>Signature Requirements:</strong>
                <ul class="small mb-0">
                  <li>Sign on white paper with black or blue pen</li>
                  <li>Capture with good lighting</li>
                  <li>Signature should be clear and legible</li>
                  <li>No shadows or blurry edges</li>
                </ul>
              </div>
              <div class="col-md-4">
                <strong>Proof of Residency:</strong>
                <ul class="small mb-0">
                  <li>Document must be clear and readable</li>
                  <li>Must show your name and address</li>
                  <li>Recent document (within last 3 months for bills)</li>
                  <li>Take photo with good lighting</li>
                  <li>Ensure all text is visible</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Buttons -->
    <div class="row">
      <div class="col-md-6 mt-2">
        <a href="{{ route('public.senior-registration.step2') }}" class="btn btn-outline-secondary w-100">
          Previous
        </a>
      </div>
      <div class="col-md-6 mt-2">
        <button type="submit" class="btn bg-gradient-dark w-100">
          Next 
        </button>
      </div>
    </div>
  </div>
</form>

{{-- JavaScript functionality is handled in /public/js/resident-registration.js --}}
@endsection

@push('scripts')
<script src="{{ asset('js/resident-registration.js') }}"></script>
@endpush
