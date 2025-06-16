@extends('layouts.public.master')

@section('title', 'Pre-Registration Step 4 - Photo & Documents')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Progress Steps -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="progress-steps">
                        <div class="step completed">
                            <div class="step-number">1</div>
                            <div class="step-title">Personal Info</div>
                        </div>
                        <div class="step completed">
                            <div class="step-number">2</div>
                            <div class="step-title">Contact & Education</div>
                        </div>
                        <div class="step completed">
                            <div class="step-number">3</div>
                            <div class="step-title">Additional Info</div>
                        </div>
                        @php
                            $birthdate = session('pre_registration.step1.birthdate');
                            $isSenior = $birthdate ? \Carbon\Carbon::parse($birthdate)->age >= 60 : false;
                        @endphp
                        @if($isSenior)
                        <div class="step completed senior">
                            <div class="step-number"><i class="fe fe-award"></i></div>
                            <div class="step-title">Senior Citizen</div>
                        </div>
                        @endif
                        <div class="step active">
                            <div class="step-number">4</div>
                            <div class="step-title">Photo & Documents</div>
                        </div>
                        <div class="step">
                            <div class="step-number">5</div>
                            <div class="step-title">Review</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fe fe-camera"></i> Step 4: Photo & Documents Upload</h4>
                    <p class="mb-0 mt-2">Upload your photo and signature for ID generation</p>
                </div>
                
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="fe fe-alert-circle"></i> Please correct the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($isSenior)
                        <div class="alert alert-warning mb-4">
                            <h5><i class="fe fe-award"></i> Senior Citizen ID Generation</h5>
                            <p class="mb-0">Your photo will be used to generate your <strong>Senior Citizen ID</strong> which will be sent to your email upon approval. This ID provides access to senior citizen benefits and discounts.</p>
                        </div>
                    @endif

                    <form action="{{ route('public.pre-registration.step4.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Photo Upload -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fe fe-camera"></i> ID Photo Upload
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="photo" class="form-label">Photo <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror" 
                                       name="photo" accept="image/*" required id="photoInput">
                                <small class="form-text text-muted">
                                    <strong>Requirements:</strong>
                                    <ul class="mb-0 mt-1">
                                        <li>Clear, front-facing photo</li>
                                        <li>Good lighting, no shadows</li>
                                        <li>Neutral expression</li>
                                        <li>Maximum file size: 5MB</li>
                                        <li>Formats: JPG, PNG</li>
                                    </ul>
                                </small>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="photo-preview-container">
                                    <label class="form-label">Photo Preview</label>
                                    <div id="photoPreview" class="border rounded text-center p-4">
                                        <i class="fe fe-camera fe-3x text-muted"></i>
                                        <p class="text-muted mt-2">Photo preview will appear here</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Signature Upload -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fe fe-edit"></i> Signature Upload (Optional)
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="signature" class="form-label">Signature</label>
                                <input type="file" class="form-control @error('signature') is-invalid @enderror" 
                                       name="signature" accept="image/*" id="signatureInput">
                                <small class="form-text text-muted">
                                    <strong>Requirements:</strong>
                                    <ul class="mb-0 mt-1">
                                        <li>Clear signature on white background</li>
                                        <li>Black or blue ink preferred</li>
                                        <li>Maximum file size: 2MB</li>
                                        <li>Formats: JPG, PNG</li>
                                    </ul>
                                </small>
                                @error('signature')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="signature-preview-container">
                                    <label class="form-label">Signature Preview</label>
                                    <div id="signaturePreview" class="border rounded text-center p-4">
                                        <i class="fe fe-edit fe-3x text-muted"></i>
                                        <p class="text-muted mt-2">Signature preview will appear here</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2">
                                    <i class="fe fe-check-circle"></i> Terms and Conditions
                                </h5>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input @error('terms_accepted') is-invalid @enderror" 
                                           type="checkbox" name="terms_accepted" id="terms_accepted" required>
                                    <label class="form-check-label" for="terms_accepted">
                                        I agree to the <a href="#" data-toggle="modal" data-target="#termsModal">Terms and Conditions</a> 
                                        and acknowledge that the information provided is accurate and complete.
                                        <span class="text-danger">*</span>
                                    </label>
                                    @error('terms_accepted')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    @if($isSenior)
                                        <a href="{{ route('public.pre-registration.step4-senior') }}" class="btn btn-secondary">
                                            <i class="fe fe-arrow-left"></i> Back: Senior Citizen Info
                                        </a>
                                    @else
                                        <a href="{{ route('public.pre-registration.step3') }}" class="btn btn-secondary">
                                            <i class="fe fe-arrow-left"></i> Back: Additional Info
                                        </a>
                                    @endif
                                    <button type="submit" class="btn btn-primary">
                                        Next: Review & Submit <i class="fe fe-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Terms and Conditions Modal -->
<div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6>Data Privacy and Information Accuracy</h6>
                <p>By submitting this pre-registration form, you acknowledge and agree to the following:</p>
                <ol>
                    <li>All information provided is accurate, complete, and truthful.</li>
                    <li>Your personal data will be used for barangay registration and ID generation purposes only.</li>
                    <li>Your data will be protected in accordance with the Data Privacy Act of 2012.</li>
                    <li>You understand that providing false information may result in rejection of your application.</li>
                    <li>Your registration is subject to verification and approval by the Barangay Administration.</li>
                    <li>@if($isSenior)Senior Citizen @endif Digital ID cards sent via email are official and can be used for barangay transactions.</li>
                    @if($isSenior)
                    <li>Senior citizen benefits and discounts are subject to verification of your Senior Citizen ID.</li>
                    @endif
                </ol>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="document.getElementById('terms_accepted').checked = true;">
                    I Agree
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.progress-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.progress-steps::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 2px;
    background-color: #e9ecef;
    z-index: 1;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    background: white;
    padding: 0 10px;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 8px;
}

.step.active .step-number {
    background-color: #007bff;
    color: white;
}

.step.completed .step-number {
    background-color: #28a745;
    color: white;
}

.step.senior.completed .step-number {
    background-color: #ffc107;
    color: #212529;
}

.step-title {
    font-size: 12px;
    text-align: center;
    color: #6c757d;
}

.step.active .step-title {
    color: #007bff;
    font-weight: 600;
}

.step.completed .step-title {
    color: #28a745;
    font-weight: 600;
}

.step.senior.completed .step-title {
    color: #ffc107;
    font-weight: 600;
}

#photoPreview, #signaturePreview {
    min-height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
}

#photoPreview img, #signaturePreview img {
    max-width: 100%;
    max-height: 180px;
    object-fit: contain;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Photo preview
    const photoInput = document.getElementById('photoInput');
    const photoPreview = document.getElementById('photoPreview');
    
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                photoPreview.innerHTML = `<img src="${e.target.result}" alt="Photo Preview" class="img-fluid">`;
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Signature preview
    const signatureInput = document.getElementById('signatureInput');
    const signaturePreview = document.getElementById('signaturePreview');
    
    signatureInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                signaturePreview.innerHTML = `<img src="${e.target.result}" alt="Signature Preview" class="img-fluid">`;
            };
            reader.readAsDataURL(file);
        } else {
            signaturePreview.innerHTML = `
                <i class="fe fe-edit fe-3x text-muted"></i>
                <p class="text-muted mt-2">Signature preview will appear here</p>
            `;
        }
    });
});
</script>
@endsection