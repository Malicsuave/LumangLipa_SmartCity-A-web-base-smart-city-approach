@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.senior-citizens.index') }}">Senior Citizens</a></li>
<li class="breadcrumb-item active" aria-current="page">ID Management</li>
@endsection

@section('page-title', 'Senior Citizen ID Management')
@section('page-subtitle', 'Manage ID card for ' . $seniorCitizen->resident->full_name)

@section('title', 'Senior ID Management')

@section('content')
<style>
.table-responsive,
.card-body,
.collapse,
#filterSection {
    overflow: visible !important;
}
.dropdown-menu {
    z-index: 9999 !important;
}
.table-responsive {
    padding-bottom: 120px;
}
</style>
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fe fe-credit-card fe-16 mr-2 text-warning"></i>Senior Citizen ID Management</h4>
                        <p class="text-muted mb-0">{{ $seniorCitizen->resident->full_name }} - {{ $seniorCitizen->resident->barangay_id }}</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.senior-citizens.index') }}" class="btn btn-outline-secondary">
                            <i class="fe fe-arrow-left fe-16 mr-2"></i>Back to List
                        </a>
                    </div>
                </div>
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
                                <h6 class="mb-0"><i class="fe fe-credit-card fe-16 mr-2"></i>Senior Citizen ID Information</h6>
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
                                            <input type="text" class="form-control @error('senior_id_number') is-invalid @enderror" id="senior_id_number" name="senior_id_number" value="{{ old('senior_id_number', $seniorCitizen->senior_id_number) }}" placeholder="e.g. {{ $suggestedSeniorId }}">
                                            @error('senior_id_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="senior_issue_id" class="form-label">Issue ID / Reference Number</label>
                                            <div class="input-group">
                                            <input type="text" class="form-control @error('senior_issue_id') is-invalid @enderror" id="senior_issue_id" name="senior_issue_id" value="{{ old('senior_issue_id', $seniorCitizen->senior_issue_id) }}" placeholder="Enter issue number (e.g. SC-2025-001)">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-outline-info" id="generateReferenceId" title="Generate Reference Number">
                                                        <i class="fe fe-refresh-cw"></i>
                                                    </button>
                                                </div>
                                            </div>
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
                                    @if($seniorCitizen->resident->photo && $seniorCitizen->resident->signature)
                                        <div class="d-flex flex-wrap">
                                            @if($seniorCitizen->senior_id_status != 'issued')
                                                <form action="{{ route('admin.senior-citizens.issue-id', $seniorCitizen) }}" method="POST" style="display: inline;" class="mr-2 mb-2">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary">
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
                                    @else
                                        <div class="alert alert-warning">
                                            <div class="d-flex align-items-center">
                                                <i class="fe fe-alert-triangle fe-24 mr-3"></i>
                                                <div>
                                                    <h6 class="alert-heading mb-1">Requirements Not Met</h6>
                                                    <p class="mb-0">Both photo and signature are required before you can preview or issue an ID card.</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
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
                                            <button type="submit" class="btn btn-dark">
                                                <i class="fe fe-save fe-16 mr-2"></i>Upload Signature
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <h6><i class="fe fe-clock fe-16 mr-2"></i>ID Issuance History</h6>
                    @if(isset($idHistory) && $idHistory->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Action</th>
                                        <th>Senior ID</th>
                                        <th>Admin</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($idHistory as $activity)
                                        <tr>
                                            <td>{{ $activity->created_at->format('M d, Y h:i A') }}</td>
                                            <td>
                                                @if($activity->description == 'issued_senior_citizen_id_card')
                                                    <span class="badge badge-success">Issued</span>
                                                @elseif($activity->description == 'updated senior citizen ID information')
                                                    <span class="badge badge-info">Updated</span>
                                                @elseif($activity->description == 'sent_senior_citizen_id_card_email')
                                                    <span class="badge badge-primary">Emailed</span>
                                                @elseif($activity->description == 'downloaded_senior_citizen_id_card')
                                                    <span class="badge badge-secondary">Downloaded</span>
                                                @elseif($activity->description == 'marked_senior_id_for_renewal')
                                                    <span class="badge badge-warning">Marked for Renewal</span>
                                                @else
                                                    <span class="badge badge-dark">{{ str_replace('_', ' ', ucfirst($activity->description)) }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $activity->properties['senior_id_number'] ?? 'N/A' }}</td>
                                            <td>{{ $activity->causer ? $activity->causer->name : 'System' }}</td>
                                            <td>
                                                @if(isset($activity->properties['senior_id_issued_at']) || isset($activity->properties['senior_id_expires_at']))
                                                    @if(isset($activity->properties['senior_id_issued_at']))
                                                        Issued: {{ \Carbon\Carbon::parse($activity->properties['senior_id_issued_at'])->format('M d, Y') }}<br>
                                                    @endif
                                                    @if(isset($activity->properties['senior_id_expires_at']))
                                                        Expires: {{ \Carbon\Carbon::parse($activity->properties['senior_id_expires_at'])->format('M d, Y') }}
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-light" role="alert">
                            <i class="fe fe-info fe-16 mr-2"></i> No ID issuance history found for this senior citizen.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var generateBtn = document.getElementById('generateReferenceId');
        if (generateBtn) {
            generateBtn.addEventListener('click', function() {
                var year = new Date().getFullYear();
                var randomNum = Math.floor(100 + Math.random() * 900); // 3-digit random number
                var ref = 'SC-' + year + '-' + randomNum;
                document.getElementById('senior_issue_id').value = ref;
                document.getElementById('senior_issue_id').focus();
            });
        }
    });
</script>
@endpush