@extends('layouts.admin.master')

@section('title', 'Resident ID Management')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">ID Management</h1>
                <small class="text-muted">{{ $resident->full_name }}</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.residents.id.pending') }}">ID Management</a></li>
                    <li class="breadcrumb-item active">{{ $resident->barangay_id }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Senior Citizen Alert -->
        @if($resident->is_senior_citizen)
                                                @if($resident->is_senior_citizen)
                                            <div class="alert alert-warning">
                                                <h6><i class="fas fa-info-circle mr-2"></i>Senior Citizen Age Detected</h6>
                                                <p class="mb-2">This resident is 60+ years old. Senior citizens are now managed independently.</p>
                                                <a href="{{ route('admin.senior-citizens.index') }}" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-users mr-1"></i>View Senior Citizens
                                                </a>
                                                <a href="{{ route('admin.senior-citizens.register') }}" class="btn btn-info btn-sm">
                                                    <i class="fas fa-plus mr-1"></i>Register as Senior
                                                </a>
                                            </div>
                                        @endif
        @endif

        <!-- Alerts -->

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Please fix the following errors:</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-id-card mr-2"></i>Resident ID Management
                        @if($resident->is_senior_citizen)
                            <span class="badge badge-warning ml-2">Age 60+</span>
                        @endif
                    </h3>
                    <div class="card-tools">
                        @if($resident->is_senior_citizen)
                            <a href="{{ route('admin.senior-citizens.index') }}" class="btn btn-tool btn-sm">
                                <i class="fas fa-user-check"></i> Senior Citizens
                            </a>
                        @endif
                    </div>
                </div>
                
                <div class="card-body">

                    <!-- ID Type Selection for Senior Citizens -->
                    @if($resident->is_senior_citizen)
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-layer-group mr-2"></i>ID Type Selection</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-primary"><i class="fas fa-id-card"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Resident ID</span>
                                                    <span class="info-box-number">3-year validity</span>
                                                    <div class="progress">
                                                        <div class="progress-bar bg-primary" style="width: 100%"></div>
                                                    </div>
                                                    <span class="progress-description">
                                                        Currently Managing
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="info-box">
                                                <span class="info-box-icon bg-warning"><i class="fas fa-user-check"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Senior Citizen ID</span>
                                                    <span class="info-box-number">5-year validity</span>
                                                    <div class="mt-2">
                                                        <a href="{{ route('admin.senior-citizens.index') }}" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-arrow-right mr-1"></i>View Senior Citizens
                                                        </a>
                                                    </div>
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
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-id-card mr-2"></i>Resident ID Information
                                        @if($resident->seniorCitizen)
                                            <small class="text-muted">(General Barangay ID)</small>
                                        @endif
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <!-- Status Overview -->
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <div class="description-block border-right">
                                                <h5 class="description-header">{{ $resident->barangay_id ?: 'Not Set' }}</h5>
                                                <span class="description-text">BARANGAY ID</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="description-block border-right">
                                                <span class="badge {{ $resident->id_status == 'issued' ? 'badge-success' : 'badge-warning' }} description-header">
                                                    {{ ucfirst(str_replace('_', ' ', $resident->id_status ?? 'pending')) }}
                                                </span>
                                                <span class="description-text">STATUS</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="description-block">
                                                <h5 class="description-header">{{ $resident->id_expires_at ? $resident->id_expires_at->format('M Y') : 'N/A' }}</h5>
                                                <span class="description-text">EXPIRES</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- ID Information Form -->
                                    <div class="border-top pt-3">
                                        <h6 class="text-bold mb-3">Update ID Details</h6>
                                        <form action="{{ route('admin.residents.id.update', $resident) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group">
                                                <label for="barangay_id">Barangay ID Number</label>
                                                <input type="text" class="form-control @error('barangay_id') is-invalid @enderror" id="barangay_id" name="barangay_id" value="{{ old('barangay_id', $resident->barangay_id) }}" placeholder="Enter ID number">
                                                @error('barangay_id')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="id_expires_at">Expiration Date</label>
                                                <input type="date" class="form-control @error('id_expires_at') is-invalid @enderror" id="id_expires_at" name="id_expires_at" value="{{ old('id_expires_at', $resident->id_expires_at ? $resident->id_expires_at->format('Y-m-d') : '') }}">
                                                @error('id_expires_at')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save mr-2"></i>Update ID Information
                                            </button>
                                        </form>
                                    </div>

                                    <!-- ID Actions -->
                                    <div class="border-top pt-3 mt-3">
                                        <h6 class="text-bold mb-3">ID Actions</h6>
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
                                        <div class="btn-group-vertical btn-group-sm d-grid gap-2">
                                                @if($resident->id_status != 'issued')
                                                    <form id="issueIdForm" action="{{ route('admin.residents.id.issue', $resident) }}" method="POST" class="mb-2">
                                                        @csrf
                                                        <button type="button" class="btn btn-success btn-block" id="showIssueModal">
                                                            <i class="fas fa-id-card mr-2"></i>Issue Resident ID
                                                        </button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('admin.residents.id.preview', $resident) }}" class="btn btn-secondary btn-block mb-2">
                                                        <i class="fas fa-eye mr-2"></i>Preview ID
                                                    </a>
                                                    <a href="{{ route('admin.residents.id.download', $resident) }}" class="btn btn-primary btn-block mb-2">
                                                        <i class="fas fa-download mr-2"></i>Download ID
                                                    </a>
                                                    <form action="{{ route('admin.residents.id.mark-renewal', $resident) }}" method="POST" class="mb-2">
                                                        @csrf
                                                        <button type="submit" class="btn btn-info btn-block">
                                                            <i class="fas fa-sync-alt mr-2"></i>Mark for Renewal
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.residents.id.revoke', $resident) }}" method="POST" class="mb-2">
                                                        @csrf
                                                        <button type="submit" class="btn btn-danger btn-block">
                                                            <i class="fas fa-times-circle mr-2"></i>Revoke ID
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                            @else
                                                <div class="alert alert-warning">
                                                    <h5><i class="icon fas fa-exclamation-triangle"></i> Requirements Not Met</h5>
                                                    Both photo and signature are required before you can preview or issue an ID card.
                                                </div>
                                            @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right column: Photo & signature -->
                        <div class="col-md-6">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-camera mr-2"></i>ID Photo</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 text-center mb-3">
                                            @if($resident->photo)
                                                <img src="{{ asset('storage/residents/photos/' . $resident->photo) }}" alt="{{ $resident->full_name }}" class="img-fluid img-thumbnail" style="max-height: 150px;">
                                            @else
                                                <div class="bg-light p-4 rounded text-center">
                                                    <i class="fas fa-user fa-3x text-muted mb-2"></i>
                                                    <p class="text-muted">No photo</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <form action="{{ route('admin.residents.id.upload-photo', $resident) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="photo">Upload New Photo</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
                                                            <label class="custom-file-label" for="photo">Choose file</label>
                                                        </div>
                                                    </div>
                                                    @error('photo')
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                    <small class="form-text text-muted">
                                                        Upload a square photo (1:1 ratio) for best results. Max size: 5MB.
                                                    </small>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-upload mr-1"></i> Upload Photo
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-signature mr-2"></i>ID Signature</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 text-center mb-3">
                                            @if($resident->signature)
                                                <img src="{{ asset('storage/residents/signatures/' . $resident->signature) }}" alt="Signature" class="img-fluid border p-2" style="max-height: 100px; background-color: #f8f9fa;">
                                            @else
                                                <div class="bg-light p-3 rounded text-center">
                                                    <i class="fas fa-pen-nib fa-2x text-muted mb-2"></i>
                                                    <p class="mb-0 text-muted">No signature</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <form action="{{ route('admin.residents.id.upload-signature', $resident) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="signature">Upload New Signature</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input @error('signature') is-invalid @enderror" id="signature" name="signature" accept="image/*">
                                                            <label class="custom-file-label" for="signature">Choose file</label>
                                                        </div>
                                                    </div>
                                                    @error('signature')
                                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                    <small class="form-text text-muted">
                                                        Upload a clear image of the signature. Max size: 2MB.
                                                    </small>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-upload mr-1"></i> Upload Signature
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ID Issuance History -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-history mr-2"></i>ID Issuance History</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($idHistory && $idHistory->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Action</th>
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
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle mr-2"></i> No ID issuance history found for this resident.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('admin.residents.id.pending') }}" class="btn btn-default">
                                <i class="fas fa-arrow-left mr-2"></i> Back to ID Management
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- Issue Resident ID Confirmation Modal -->
<div class="modal fade" id="issueConfirmModal" tabindex="-1" role="dialog" aria-labelledby="issueConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h4 class="modal-title text-white" id="issueConfirmModalLabel">
            <i class="fas fa-id-card mr-2"></i>Confirm ID Issuance
        </h4>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="callout callout-info">
            <h5>Are you sure you want to issue this Resident ID?</h5>
            <p class="mb-0">This action will mark the ID as issued and cannot be undone without proper authorization.</p>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i>Cancel
        </button>
        <button type="button" class="btn btn-primary" id="confirmIssueBtn">
          <i class="fas fa-id-card mr-2"></i>Issue Resident ID
        </button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize custom file inputs
        bsCustomFileInput.init();
        
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

        // Modal handlers
        $('#showIssueModal').on('click', function(e) {
            $('#issueConfirmModal').modal('show');
        });
        
        $('#confirmIssueBtn').on('click', function() {
            $('#issueIdForm').submit();
        });
    });
</script>
@endpush