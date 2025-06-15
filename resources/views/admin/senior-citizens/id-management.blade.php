@extends('layouts.admin.master')

@section('title', 'Senior ID Management')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-warning">Senior Citizen ID Management: {{ $seniorCitizen->resident->full_name }}</h6>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fe fe-check-circle fe-16 mr-2"></i> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fe fe-alert-circle fe-16 mr-2"></i> {{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fe fe-alert-circle fe-16 mr-2"></i>
                        <strong>Please fix the following errors:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif

                    <div class="row">
                        <!-- Left column: ID details -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Senior Citizen ID Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <p><strong>Senior ID Number:</strong> {{ $seniorCitizen->senior_id_number ?: 'Not issued yet' }}</p>
                                        <p><strong>ID Status:</strong> 
                                            <span class="badge {{ $seniorCitizen->senior_id_status == 'issued' ? 'badge-success' : 'badge-warning' }}">
                                                {{ ucfirst(str_replace('_', ' ', $seniorCitizen->senior_id_status)) }}
                                            </span>
                                        </p>
                                        @if($seniorCitizen->senior_id_issued_at)
                                            <p><strong>Issued Date:</strong> {{ $seniorCitizen->senior_id_issued_at->format('F d, Y') }}</p>
                                        @endif
                                        @if($seniorCitizen->senior_id_expires_at)
                                            <p><strong>Expiry Date:</strong> {{ $seniorCitizen->senior_id_expires_at->format('F d, Y') }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-3">
                                        <h6>ID Information</h6>
                                        <form action="{{ route('admin.senior-citizens.update-id-info', $seniorCitizen) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group">
                                                <label for="senior_id_number" class="form-label">Senior ID Number</label>
                                                <input type="text" class="form-control @error('senior_id_number') is-invalid @enderror" id="senior_id_number" name="senior_id_number" value="{{ old('senior_id_number', $seniorCitizen->senior_id_number) }}" placeholder="Enter Senior ID number">
                                                @error('senior_id_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="senior_issue_id" class="form-label">Issue ID / Reference Number</label>
                                                <input type="text" class="form-control @error('senior_issue_id') is-invalid @enderror" id="senior_issue_id" name="senior_issue_id" value="{{ old('senior_issue_id', $seniorCitizen->senior_issue_id) }}" placeholder="Enter issue number (e.g. SC-2025-001)">
                                                <small class="form-text text-muted">
                                                    A unique reference number for this ID issuance.
                                                </small>
                                                @error('senior_issue_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="senior_id_expires_at" class="form-label">Expiration Date</label>
                                                <input type="date" class="form-control @error('senior_id_expires_at') is-invalid @enderror" id="senior_id_expires_at" name="senior_id_expires_at" value="{{ old('senior_id_expires_at', $seniorCitizen->senior_id_expires_at ? $seniorCitizen->senior_id_expires_at->format('Y-m-d') : '') }}">
                                                @error('senior_id_expires_at')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-dark">
                                                <i class="fe fe-save fe-16 mr-2"></i>Update ID Information
                                            </button>
                                        </form>
                                    </div>

                                    <div class="mb-3">
                                        <h6>ID Actions</h6>
                                        <div class="d-flex flex-wrap">
                                            @if($seniorCitizen->senior_id_status !== 'issued')
                                                <form action="{{ route('admin.senior-citizens.issue-id', $seniorCitizen) }}" method="POST" style="display: inline;" class="mr-2 mb-2">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success">
                                                        <i class="fe fe-credit-card fe-16 mr-2"></i>Issue Senior ID
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('admin.senior-citizens.id.preview', $seniorCitizen) }}" class="btn btn-outline-secondary mr-2 mb-2">
                                                    <i class="fe fe-eye fe-16 mr-2"></i>Preview ID
                                                </a>
                                                <a href="{{ route('admin.senior-citizens.id.download', $seniorCitizen) }}" class="btn btn-primary mr-2 mb-2">
                                                    <i class="fe fe-download fe-16 mr-2"></i>Download ID
                                                </a>
                                                <form action="{{ route('admin.senior-citizens.mark-renewal', $seniorCitizen) }}" method="POST" style="display: inline;" class="mr-2 mb-2">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-info">
                                                        <i class="fe fe-refresh-cw fe-16 mr-2"></i>Mark for Renewal
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.senior-citizens.revoke-id', $seniorCitizen) }}" method="POST" style="display: inline;" class="mr-2 mb-2">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fe fe-x-circle fe-16 mr-2"></i>Revoke ID
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right column: Photo & signature -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">ID Photo</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 text-center mb-3">
                                            @if($seniorCitizen->resident->photo)
                                                <img src="{{ asset('storage/residents/photos/' . $seniorCitizen->resident->photo) }}" 
                                                    alt="{{ $seniorCitizen->resident->full_name }}" class="img-fluid rounded" style="max-height: 150px;">
                                            @else
                                                <div class="no-photo bg-light p-5 rounded">
                                                    <i class="fe fe-user fe-24"></i>
                                                    <p>No photo</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <form action="{{ route('admin.senior-citizens.upload-photo', $seniorCitizen) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="photo">Upload New Photo</label>
                                                    <input type="file" class="form-control-file @error('photo') is-invalid @enderror" id="photo" name="photo" required>
                                                    @error('photo')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <small class="form-text text-muted">
                                                        Recommended: 2x2 inches, max 5MB, jpeg/png
                                                    </small>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fe fe-upload"></i> Upload Photo
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">ID Signature</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 text-center mb-3">
                                            @if($seniorCitizen->resident->signature)
                                                <img src="{{ asset('storage/residents/signatures/' . $seniorCitizen->resident->signature) }}" 
                                                    alt="Signature" class="img-fluid" style="max-height: 100px; background-color: #f8f9fa; padding: 10px;">
                                            @else
                                                <div class="no-signature bg-light p-3 rounded">
                                                    <p class="mb-0 text-muted">No signature</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <form action="{{ route('admin.senior-citizens.upload-signature', $seniorCitizen) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="signature">Upload New Signature</label>
                                                    <input type="file" class="form-control-file @error('signature') is-invalid @enderror" id="signature" name="signature" required>
                                                    @error('signature')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <small class="form-text text-muted">
                                                        Max 2MB, jpeg/png
                                                    </small>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="fe fe-upload"></i> Upload Signature
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('admin.senior-citizens.index') }}" class="btn btn-secondary">
                                <i class="fe fe-arrow-left"></i> Back to Senior Citizens List
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection