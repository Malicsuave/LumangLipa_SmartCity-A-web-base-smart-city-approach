@extends('layouts.admin.master')

@section('page-header', 'New Senior Citizen Registration')

@section('page-header-extra')
    <p class="text-muted mb-0">Upload the senior citizen's profile photo and digital signature</p>
@endsection

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.senior-citizens.index') }}">Senior Citizens</a></li>
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
                    <span class="badge badge-light">Step 3 of 5</span>
                </div>
            </div>
            <form action="{{ route('admin.senior-citizens.register.step3.store') }}" method="POST" id="step3Form" enctype="multipart/form-data">
                @csrf
                <!-- Hidden inputs to track removal of existing images -->
                <input type="hidden" id="remove_photo" name="remove_photo" value="0">
                <input type="hidden" id="remove_signature" name="remove_signature" value="0">
                <div class="card-body registration-form">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Optional:</strong> You can upload a profile photo and digital signature to be included in the senior citizen ID card. These are not required but will make the ID more personalized and professional.
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-user-circle mr-2"></i>Profile Photo
                            </h5>
                            <div class="upload-area" id="photo-upload-area">
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
                            <div id="photo-preview" class="preview-container" style="display: none;">
                                <img id="photo-preview-img" src="" alt="Photo Preview" class="preview-image">
                                <div class="file-info" id="photo-info"></div>
                                <button type="button" class="btn btn-sm btn-danger remove-btn" id="remove-photo">
                                    <i class="fas fa-trash mr-1"></i>Remove Photo
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-signature mr-2"></i>Digital Signature
                            </h5>
                            <div class="upload-area" id="signature-upload-area">
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
                            <div id="signature-preview" class="preview-container" style="display: none;">
                                <img id="signature-preview-img" src="" alt="Signature Preview" class="preview-signature">
                                <div class="file-info" id="signature-info"></div>
                                <button type="button" class="btn btn-sm btn-danger remove-btn" id="remove-signature">
                                    <i class="fas fa-trash mr-1"></i>Remove Signature
                                </button>
                            </div>
                        </div>
                    </div>
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
                            <a href="{{ route('admin.senior-citizens.register.step2') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>Previous: Contact Info
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-primary">
                                Continue to Step 4 <i class="fas fa-arrow-right ml-2"></i>
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
    function setupFileUpload(inputId, uploadAreaId, previewId, previewImgId, infoId, removeId, maxSize) {
        const input = document.getElementById(inputId);
        const uploadArea = document.getElementById(uploadAreaId);
        const preview = document.getElementById(previewId);
        const previewImg = document.getElementById(previewImgId);
        const info = document.getElementById(infoId);
        const removeBtn = document.getElementById(removeId);
        uploadArea.addEventListener('click', () => input.click());
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
        input.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });
        removeBtn.addEventListener('click', () => {
            input.value = '';
            preview.style.display = 'none';
            uploadArea.style.display = 'block';
            
            // Set removal flag for existing images
            if (inputId === 'photo') {
                document.getElementById('remove_photo').value = '1';
            } else if (inputId === 'signature') {
                document.getElementById('remove_signature').value = '1';
            }
        });
        function handleFile(file) {
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file (JPG, JPEG, PNG).');
                return;
            }
            if (file.size > maxSize) {
                alert(`File size should not exceed ${maxSize / (1024 * 1024)}MB.`);
                return;
            }
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
    setupFileUpload('photo', 'photo-upload-area', 'photo-preview', 'photo-preview-img', 'photo-info', 'remove-photo', 2 * 1024 * 1024);
    setupFileUpload('signature', 'signature-upload-area', 'signature-preview', 'signature-preview-img', 'signature-info', 'remove-signature', 1024 * 1024);
    
    // Load existing images from session on page load
    @if(session('senior_registration.step3.photo'))
        $('#photo-preview-img').attr('src', '{{ asset("storage/" . session("senior_registration.step3.photo")) }}');
        $('#photo-info').text('Previously uploaded photo');
        $('#photo-upload-area').hide();
        $('#photo-preview').show();
    @endif
    
    @if(session('senior_registration.step3.signature'))
        $('#signature-preview-img').attr('src', '{{ asset("storage/" . session("senior_registration.step3.signature")) }}');
        $('#signature-info').text('Previously uploaded signature');
        $('#signature-upload-area').hide();
        $('#signature-preview').show();
    @endif
});
</script>
@endpush
