@extends('layouts.admin.master')

@section('title', 'Resident ID Management')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Senior Citizen Alert -->
            @if($resident->seniorCitizen)
            <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fe fe-info fe-24 mr-3"></i>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading mb-1">Senior Citizen Detected</h5>
                        <p class="mb-2">This resident is registered as a senior citizen and has access to both Resident ID and Senior Citizen ID.</p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.senior-citizens.id-management', $resident->seniorCitizen) }}" class="btn btn-warning btn-sm">
                                <i class="fe fe-user-check fe-16 mr-1"></i>Manage Senior Citizen ID
                            </a>
                            <span class="text-muted">or continue managing Resident ID below</span>
                        </div>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            @endif

            <div class="card shadow mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Resident ID Management: {{ $resident->full_name }}
                            @if($resident->seniorCitizen)
                                <span class="badge badge-warning ml-2">Senior Citizen</span>
                            @endif
                        </h6>
                        <div class="d-flex gap-2">
                            @if($resident->seniorCitizen)
                                <a href="{{ route('admin.senior-citizens.id-management', $resident->seniorCitizen) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="fe fe-user-check fe-16 mr-1"></i>Senior ID
                                </a>
                            @endif
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

                    <!-- ID Type Selection for Senior Citizens -->
                    @if($resident->seniorCitizen)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0"><i class="fe fe-layers fe-16 mr-2"></i>ID Type Selection</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card border-primary">
                                                <div class="card-body text-center">
                                                    <i class="fe fe-credit-card fe-24 text-primary mb-2"></i>
                                                    <h6>Resident ID</h6>
                                                    <p class="text-muted small">General barangay identification<br>3-year validity</p>
                                                    <span class="badge badge-primary">Currently Managing</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card border-warning">
                                                <div class="card-body text-center">
                                                    <i class="fe fe-user-check fe-24 text-warning mb-2"></i>
                                                    <h6>Senior Citizen ID</h6>
                                                    <p class="text-muted small">Senior privileges & benefits<br>5-year validity</p>
                                                    <a href="{{ route('admin.senior-citizens.id-management', $resident->seniorCitizen) }}" class="btn btn-warning btn-sm">
                                                        Switch to Senior ID
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <!-- Left column: ID details -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fe fe-credit-card fe-16 mr-2"></i>Resident ID Information
                                        @if($resident->seniorCitizen)
                                            <small class="text-muted">(General Barangay ID)</small>
                                        @endif
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <p><strong>Barangay ID Number:</strong> {{ $resident->barangay_id ?: 'Not assigned yet' }}</p>
                                        <p><strong>ID Status:</strong> 
                                            <span class="badge {{ $resident->id_status == 'issued' ? 'badge-success' : 'badge-warning' }}">
                                                {{ ucfirst(str_replace('_', ' ', $resident->id_status ?? 'pending')) }}
                                            </span>
                                        </p>
                                        @if($resident->id_expires_at)
                                            <p><strong>Expiry Date:</strong> {{ $resident->id_expires_at->format('F d, Y') }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="mb-3">
                                        <h6>ID Information</h6>
                                        <form action="{{ route('admin.residents.id.update', $resident) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group">
                                                <label for="barangay_id" class="form-label">Barangay ID Number</label>
                                                <input type="text" class="form-control @error('barangay_id') is-invalid @enderror" id="barangay_id" name="barangay_id" value="{{ old('barangay_id', $resident->barangay_id) }}" placeholder="Enter ID number">
                                                @error('barangay_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="id_number" class="form-label">Issue ID / Reference Number</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control @error('id_number') is-invalid @enderror" id="id_number" name="id_number" value="{{ old('id_number', $resident->id_number) }}" placeholder="e.g. {{ $suggestedIssueId }}" pattern="^BR-\d{4}-\d{3,}$">
                                                    <div class="input-group-append">
                                                        <button type="button" class="btn btn-outline-info" id="generateIssueId" title="Generate Issue ID">
                                                            <i class="fe fe-refresh-cw"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <small class="form-text text-muted">
                                                    A unique reference number for this ID issuance (format: BR-YYYY-NNN).
                                                </small>
                                                @error('id_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="id_expires_at" class="form-label">Expiration Date</label>
                                                <input type="date" class="form-control @error('id_expires_at') is-invalid @enderror" id="id_expires_at" name="id_expires_at" value="{{ old('id_expires_at', $resident->id_expires_at ? $resident->id_expires_at->format('Y-m-d') : '') }}">
                                                @error('id_expires_at')
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
                                        @php
                                            $dropdownItems = [];
                                            if ($resident->photo && $resident->signature) {
                                                if ($resident->id_status != 'issued') {
                                                    $dropdownItems[] = [
                                                        'label' => 'Issue Resident ID',
                                                        'icon' => 'fe fe-credit-card fe-16 text-success',
                                                        'class' => '',
                                                        'attrs' => '',
                                                        'href' => '#',
                                                        'form' => route('admin.residents.id.issue', $resident),
                                                        'method' => 'POST',
                                                        'csrf' => true,
                                                    ];
                                                } else {
                                                    $dropdownItems[] = [
                                                        'label' => 'Preview ID',
                                                        'icon' => 'fe fe-eye fe-16 text-secondary',
                                                        'class' => '',
                                                        'attrs' => '',
                                                        'href' => route('admin.residents.id.preview', $resident),
                                                    ];
                                                    $dropdownItems[] = [
                                                        'label' => 'Download ID',
                                                        'icon' => 'fe fe-download fe-16 text-primary',
                                                        'class' => '',
                                                        'attrs' => '',
                                                        'href' => route('admin.residents.id.download', $resident),
                                                    ];
                                                    $dropdownItems[] = [
                                                        'label' => 'Mark for Renewal',
                                                        'icon' => 'fe fe-refresh-cw fe-16 text-info',
                                                        'class' => '',
                                                        'attrs' => '',
                                                        'href' => '#',
                                                        'form' => route('admin.residents.id.mark-renewal', $resident),
                                                        'method' => 'POST',
                                                        'csrf' => true,
                                                    ];
                                                    $dropdownItems[] = [
                                                        'label' => 'Revoke ID',
                                                        'icon' => 'fe fe-x-circle fe-16 text-danger',
                                                        'class' => '',
                                                        'attrs' => '',
                                                        'href' => '#',
                                                        'form' => route('admin.residents.id.revoke', $resident),
                                                        'method' => 'POST',
                                                        'csrf' => true,
                                                    ];
                                                }
                                            }
                                        @endphp
                                        @if($resident->photo && $resident->signature)
                                        <div class="d-flex flex-wrap">
                                                @if($resident->id_status != 'issued')
                                                    <form id="issueIdForm" action="{{ route('admin.residents.id.issue', $resident) }}" method="POST" style="display: inline;" class="mr-2 mb-2">
                                                        @csrf
                                                        <button type="button" class="btn btn-primary" id="showIssueModal">
                                                            <i class="fe fe-credit-card fe-16 mr-2"></i>Issue Resident ID
                                                        </button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('admin.residents.id.preview', $resident) }}" class="btn btn-outline-secondary mr-2 mb-2">
                                                        <i class="fe fe-eye fe-16 mr-2"></i>Preview ID
                                                    </a>
                                                    <a href="{{ route('admin.residents.id.download', $resident) }}" class="btn btn-primary mr-2 mb-2">
                                                        <i class="fe fe-download fe-16 mr-2"></i>Download ID
                                                    </a>
                                                    <form action="{{ route('admin.residents.id.mark-renewal', $resident) }}" method="POST" style="display: inline;" class="mr-2 mb-2">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-info">
                                                            <i class="fe fe-refresh-cw fe-16 mr-2"></i>Mark for Renewal
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.residents.id.revoke', $resident) }}" method="POST" style="display: inline;" class="mr-2 mb-2">
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
                                            @if($resident->photo)
                                                <img src="{{ asset('storage/residents/photos/' . $resident->photo) }}" alt="{{ $resident->full_name }}" class="img-fluid rounded" style="max-height: 150px;">
                                            @else
                                                <div class="no-photo bg-light p-5 rounded">
                                                    <i class="fe fe-user fe-24"></i>
                                                    <p>No photo</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <form action="{{ route('admin.residents.id.upload-photo', $resident) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="photo">Upload New Photo</label>
                                                    <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
                                                    @error('photo')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">
                                                        Upload a square photo (1:1 ratio) for best results. Max size: 5MB.
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
                                            @if($resident->signature)
                                                <img src="{{ asset('storage/residents/signatures/' . $resident->signature) }}" alt="Signature" class="img-fluid" style="max-height: 100px; background-color: #f8f9fa; padding: 10px;">
                                            @else
                                                <div class="no-signature bg-light p-3 rounded">
                                                    <p class="mb-0 text-muted">No signature</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <form action="{{ route('admin.residents.id.upload-signature', $resident) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="signature">Upload New Signature</label>
                                                    <input type="file" class="form-control @error('signature') is-invalid @enderror" id="signature" name="signature" accept="image/*">
                                                    @error('signature')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="form-text text-muted">
                                                        Upload a clear image of the signature. Max size: 2MB.
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
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fe fe-clock fe-16 mr-2"></i>ID Issuance History</h6>
                                </div>
                                <div class="card-body">
                                    @if($idHistory && $idHistory->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Action</th>
                                                        <th>Issue ID</th>
                                                        <th>Admin</th>
                                                        <th>Details</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($idHistory as $activity)
                                                        <tr>
                                                            <td>{{ $activity->created_at->format('M d, Y h:i A') }}</td>
                                                            <td>
                                                                @if($activity->description == 'issued_id_card')
                                                                    <span class="badge badge-success">Issued</span>
                                                                @elseif($activity->description == 'updated_id_information')
                                                                    <span class="badge badge-info">Updated</span>
                                                                @elseif($activity->description == 'sent_id_card_email')
                                                                    <span class="badge badge-primary">Emailed</span>
                                                                @elseif($activity->description == 'downloaded_id_card')
                                                                    <span class="badge badge-secondary">Downloaded</span>
                                                                @elseif($activity->description == 'marked_id_for_renewal')
                                                                    <span class="badge badge-warning">Marked for Renewal</span>
                                                                @else
                                                                    <span class="badge badge-dark">{{ str_replace('_', ' ', ucfirst($activity->description)) }}</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $activity->properties['id_number'] ?? 'N/A' }}</td>
                                                            <td>{{ $activity->causer ? $activity->causer->name : 'System' }}</td>
                                                            <td>
                                                                @if(isset($activity->properties['id_issued_at']) || isset($activity->properties['id_expires_at']))
                                                                    @if(isset($activity->properties['id_issued_at']))
                                                                        Issued: {{ \Carbon\Carbon::parse($activity->properties['id_issued_at'])->format('M d, Y') }}<br>
                                                                    @endif
                                                                    @if(isset($activity->properties['id_expires_at']))
                                                                        Expires: {{ \Carbon\Carbon::parse($activity->properties['id_expires_at'])->format('M d, Y') }}
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
                                            <i class="fe fe-info fe-16 mr-2"></i> No ID issuance history found for this resident.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('admin.residents.id.pending') }}" class="btn btn-secondary">
                                <i class="fe fe-arrow-left"></i> Back to ID Management
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Issue Resident ID Confirmation Modal -->
<div class="modal fade" id="issueConfirmModal" tabindex="-1" role="dialog" aria-labelledby="issueConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="issueConfirmModalLabel">Confirm ID Issuance</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to issue this Resident ID?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirmIssueBtn">
          <i class="fe fe-credit-card fe-16 mr-2"></i>Issue Resident ID
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Preview uploaded image before submission
        $('#photo').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('img[alt="{{ $resident->full_name }}"]').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Preview uploaded signature before submission
        $('#signature').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('img[alt="Signature"]').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });

        // Generate Issue ID button click handler
        $('#generateIssueId').click(function() {
            // Fetch a new random ID from the backend
            $.ajax({
                url: '{{ route('admin.residents.id.generate', $resident) }}',
                method: 'GET',
                success: function(data) {
                    if (data && data.issue_id) {
                        $('#id_number').val(data.issue_id).trigger('focus');
                    }
                },
                error: function() {
                    alert('Failed to generate a new Issue ID. Please try again.');
                }
            });
        });
        
        // Validate issue ID format
        $('#id_number').on('input', function() {
            const input = $(this);
            const value = input.val();
            const pattern = /^BR-\d{4}-\d{3,}$/;
            
            if (value && !pattern.test(value)) {
                input.addClass('is-invalid');
                if (!input.next('.invalid-feedback').length) {
                    input.after('<div class="invalid-feedback">Format should be BR-YYYY-NNN (e.g., BR-2025-001)</div>');
                }
            } else {
                input.removeClass('is-invalid');
                input.next('.invalid-feedback').remove();
            }
        });
    });

$(function() {
    $('#showIssueModal').on('click', function(e) {
        $('#issueConfirmModal').modal('show');
    });
    $('#confirmIssueBtn').on('click', function() {
        $('#issueIdForm').submit();
    });
});
</script>
@endpush