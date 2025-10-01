@extends('layouts.admin.master')

@section('page-header', 'New Resident Registration')

@section('page-header-extra')
    <p class="text-muted mb-0">Upload photo and signature for the resident ID (optional)</p>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
    <li class="breadcrumb-item active">New Registration</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/registration-form.css') }}">
<style>
.upload-area {
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 30px;
    text-align: center;
    background-color: #f9f9f9;
    transition: all 0.3s ease;
    cursor: pointer;
}

.upload-area:hover {
    border-color: #007bff;
    background-color: #f0f8ff;
}

.upload-area.dragover {
    border-color: #007bff;
    background-color: #e7f3ff;
}

.preview-container {
    margin-top: 15px;
    text-align: center;
}

.preview-image {
    max-width: 200px;
    max-height: 200px;
    border-radius: 8px;
    border: 3px solid #007bff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.preview-signature {
    max-width: 300px;
    max-height: 150px;
    border-radius: 8px;
    border: 3px solid #007bff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.file-info {
    font-size: 0.9em;
    color: #666;
    margin-top: 10px;
}

.remove-btn {
    margin-top: 10px;
}
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">
                    <i class="fas fa-camera mr-2"></i>
                    Photo and Signature Upload
                </strong>
                <div class="card-tools">
                    <span class="badge badge-light">Step 3 of 4</span>
                </div>
            </div>
            <form action="{{ route('admin.residents.create.step3.store') }}" method="POST" id="step3Form" enctype="multipart/form-data">
                @csrf
                <div class="card-body registration-form">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Optional:</strong> You can upload a profile photo and digital signature to be included in the resident ID card. These are not required but will make the ID more personalized and professional.
                    </div>

                    <!-- Profile Photo Upload -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-user-circle mr-2"></i>Profile Photo
                            </h5>
                            
                            <div class="upload-area" id="photo-upload-area" style="display: {{ Session::has('registration.step3.photo') ? 'none' : 'block' }};">
                                <input type="file" class="d-none @error('photo') is-invalid @enderror" 
                                       id="photo" name="photo" 
                                       accept="image/jpeg,image/jpg,image/png">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">Click to upload or drag and drop</h6>
                                <p class="text-muted mb-0">
                                    <small>
                                        <strong>Recommended:</strong> Clear headshot photo<br>
                                        <strong>Formats:</strong> JPG, JPEG, PNG<br>
                                        <strong>Max size:</strong> 2MB
                                    </small>
                                </p>
                            </div>
                            
                            @error('photo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            <div id="photo-preview" class="preview-container" style="display: {{ Session::has('registration.step3.photo') ? 'block' : 'none' }};">
                                <img id="photo-preview-img" src="{{ Session::has('registration.step3.photo') ? asset('storage/' . Session::get('registration.step3.photo')) : '' }}" alt="Photo Preview" class="preview-image">
                                <div class="file-info" id="photo-info">
                                    @if(Session::has('registration.step3.photo'))
                                        {{ basename(Session::get('registration.step3.photo')) }}
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-danger remove-btn" id="remove-photo">
                                    <i class="fas fa-trash mr-1"></i>Remove Photo
                                </button>
                            </div>
                        </div>

                        <!-- Digital Signature Upload -->
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-signature mr-2"></i>Digital Signature
                            </h5>
                            
                            <div class="upload-area" id="signature-upload-area" style="display: {{ Session::has('registration.step3.signature') ? 'none' : 'block' }};">
                                <input type="file" class="d-none @error('signature') is-invalid @enderror" 
                                       id="signature" name="signature" 
                                       accept="image/jpeg,image/jpg,image/png">
                                <i class="fas fa-edit fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">Click to upload or drag and drop</h6>
                                <p class="text-muted mb-0">
                                    <small>
                                        <strong>Recommended:</strong> Clear signature on white background<br>
                                        <strong>Formats:</strong> JPG, JPEG, PNG<br>
                                        <strong>Max size:</strong> 1MB
                                    </small>
                                </p>
                            </div>
                            
                            @error('signature')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            <div id="signature-preview" class="preview-container" style="display: {{ Session::has('registration.step3.signature') ? 'block' : 'none' }};">
                                <img id="signature-preview-img" src="{{ Session::has('registration.step3.signature') ? asset('storage/' . Session::get('registration.step3.signature')) : '' }}" alt="Signature Preview" class="preview-signature">
                                <div class="file-info" id="signature-info">
                                    @if(Session::has('registration.step3.signature'))
                                        {{ basename(Session::get('registration.step3.signature')) }}
                                    @endif
                                </div>
                                <button type="button" class="btn btn-sm btn-danger remove-btn" id="remove-signature">
                                    <i class="fas fa-trash mr-1"></i>Remove Signature
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Tips -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-light border">
                                <h6 class="text-primary mb-2">
                                    <i class="fas fa-lightbulb mr-2"></i>Tips for better uploads:
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>For Profile Photo:</strong>
                                        <ul class="mb-0 mt-1">
                                            <li>Use good lighting</li>
                                            <li>Face the camera directly</li>
                                            <li>Neutral expression</li>
                                            <li>Plain background preferred</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>For Digital Signature:</strong>
                                        <ul class="mb-0 mt-1">
                                            <li>Sign on white paper</li>
                                            <li>Use dark ink (black/blue)</li>
                                            <li>Scan or photo with good contrast</li>
                                            <li>Crop close to signature</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('admin.residents.create.step2') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>Previous Step
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-primary">
                                Continue to Review <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // File upload handling
    function setupFileUpload(inputId, uploadAreaId, previewId, previewImgId, infoId, removeId, maxSize) {
        const input = document.getElementById(inputId);
        const uploadArea = document.getElementById(uploadAreaId);
        const preview = document.getElementById(previewId);
        const previewImg = document.getElementById(previewImgId);
        const info = document.getElementById(infoId);
        const removeBtn = document.getElementById(removeId);

        // Click to upload
        uploadArea.addEventListener('click', () => input.click());

        // Drag and drop
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });

        uploadArea.addEventListener('dragleave', () => {
            uploadArea.classList.remove('dragover');
        });

        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                input.files = files;
                handleFile(files[0]);
            }
        });

        // File input change
        input.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });

        // Remove button
        removeBtn.addEventListener('click', () => {
            input.value = '';
            preview.style.display = 'none';
            uploadArea.style.display = 'block';
        });

        function handleFile(file) {
            // Validate file type
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file (JPG, JPEG, PNG).');
                return;
            }

            // Validate file size
            if (file.size > maxSize) {
                alert(`File size should not exceed ${maxSize / (1024 * 1024)}MB.`);
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                info.textContent = `${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
                uploadArea.style.display = 'none';
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }

    // Setup photo upload (2MB max)
    setupFileUpload('photo', 'photo-upload-area', 'photo-preview', 'photo-preview-img', 'photo-info', 'remove-photo', 2 * 1024 * 1024);

    // Setup signature upload (1MB max)
    setupFileUpload('signature', 'signature-upload-area', 'signature-preview', 'signature-preview-img', 'signature-info', 'remove-signature', 1024 * 1024);
});
</script>
@endpush
