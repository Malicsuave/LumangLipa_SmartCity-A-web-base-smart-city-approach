@extends('layouts.admin.master')

@section('title', 'Senior Citizen ID Management')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Senior Citizen ID Management</h1>
                <small class="text-muted">{{ $seniorCitizen->full_name }}</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.senior-citizens.index') }}">Senior Citizens</a></li>
                    <li class="breadcrumb-item active">{{ $seniorCitizen->senior_id_number ?: 'ID Management' }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">


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

            <div class="card card-dark card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user-check mr-2"></i>Senior Citizen ID Management
                        <span class="badge badge-dark ml-2">Age {{ \Carbon\Carbon::parse($seniorCitizen->birthdate)->age }}</span>
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.senior-citizens.index') }}" class="btn btn-tool btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                
                <div class="card-body">

                <div class="row">
                    <!-- Left column: ID details -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fe fe-credit-card fe-16 mr-2"></i>Senior Citizen ID Information</h6>
                            </div>
                                                <div class="row">
                        <!-- Left column: ID details -->
                        <div class="col-md-6">
                            <div class="card card-secondary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-user-check mr-2"></i>Senior Citizen ID Information
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <!-- Status Overview -->
                                    <div class="row mb-3">
                                        <div class="col-sm-4">
                                            <div class="description-block border-right">
                                                <h5 class="description-header">{{ $seniorCitizen->senior_id_number ?: 'Not Set' }}</h5>
                                                <span class="description-text">SENIOR ID</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="description-block border-right">
                                                <span class="badge badge-dark description-header">
                                                    {{ ucfirst(str_replace('_', ' ', $seniorCitizen->senior_id_status ?? 'pending')) }}
                                                </span>
                                                <span class="description-text">STATUS</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="description-block">
                                                <h5 class="description-header">{{ $seniorCitizen->senior_id_expires_at ? $seniorCitizen->senior_id_expires_at->format('M Y') : 'N/A' }}</h5>
                                                <span class="description-text">EXPIRES</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- ID Information Form -->
                                    <div class="border-top pt-3">
                                        <h6 class="text-bold mb-3">Update ID Details</h6>
                                        <form action="{{ route('admin.senior-citizens.update-id-info', $seniorCitizen) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-group">
                                                <label for="senior_id_number">Senior ID Number</label>
                                                <input type="text" class="form-control @error('senior_id_number') is-invalid @enderror" id="senior_id_number" name="senior_id_number" value="{{ old('senior_id_number', $seniorCitizen->senior_id_number) }}" placeholder="e.g. {{ $suggestedSeniorId ?? 'SC-LUM-2025-0001' }}">
                                                @error('senior_id_number')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="form-group">
                                                <label for="senior_id_expires_at">Expiration Date</label>
                                                <input type="date" class="form-control @error('senior_id_expires_at') is-invalid @enderror" id="senior_id_expires_at" name="senior_id_expires_at" value="{{ old('senior_id_expires_at', $seniorCitizen->senior_id_expires_at ? $seniorCitizen->senior_id_expires_at->format('Y-m-d') : '') }}">
                                                @error('senior_id_expires_at')
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
                                        @if($seniorCitizen->photo && $seniorCitizen->signature)
                                            <div class="btn-group-vertical btn-group-sm d-grid gap-2">
                                                @if($seniorCitizen->senior_id_status != 'issued')
                                                    <form id="issueSeniorIdForm" action="{{ route('admin.senior-citizens.issue-id', $seniorCitizen) }}" method="POST" class="mb-2">
                                                        @csrf
                                                        <button type="button" class="btn btn-dark btn-block" id="showIssueSeniorModal">
                                                            <i class="fas fa-user-check mr-2"></i>Issue Senior Citizen ID
                                                        </button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('admin.senior-citizens.id.preview', $seniorCitizen) }}" class="btn btn-dark btn-block mb-2">
                                                        <i class="fas fa-eye mr-2"></i>Preview ID
                                                    </a>
                                                    <a href="{{ route('admin.senior-citizens.id.download', $seniorCitizen) }}" class="btn btn-dark btn-block mb-2">
                                                        <i class="fas fa-download mr-2"></i>Download ID
                                                    </a>
                                                    <form action="{{ route('admin.senior-citizens.mark-renewal', $seniorCitizen) }}" method="POST" class="mb-2">
                                                        @csrf
                                                        <button type="submit" class="btn btn-dark btn-block">
                                                            <i class="fas fa-sync-alt mr-2"></i>Mark for Renewal
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.senior-citizens.revoke-id', $seniorCitizen) }}" method="POST" class="mb-2">
                                                        @csrf
                                                        <button type="submit" class="btn btn-dark btn-block">
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
                            </div>
                        </div>
                    </div>

                        <!-- Right column: Photo & signature -->
                        <div class="col-md-6">
                            <div class="card card-dark">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-camera mr-2"></i>ID Photo</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 text-center mb-3">
                                            @if($seniorCitizen->photo)
                                                <img src="{{ asset('storage/residents/photos/' . $seniorCitizen->photo) }}" alt="{{ $seniorCitizen->full_name }}" class="img-fluid img-thumbnail" style="max-height: 150px;">
                                            @else
                                                <div class="bg-light p-4 rounded text-center">
                                                    <i class="fas fa-user fa-3x text-dark mb-2"></i>
                                                    <p class="text-muted">No photo</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <form action="{{ route('admin.senior-citizens.upload-photo', $seniorCitizen) }}" method="POST" enctype="multipart/form-data">
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
                                                <button type="submit" class="btn btn-dark btn-sm">
                                                    <i class="fas fa-upload mr-1"></i> Upload Photo
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card card-dark">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-signature mr-2"></i>ID Signature</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 text-center mb-3">
                                            @if($seniorCitizen->signature)
                                                <img src="{{ asset('storage/residents/signatures/' . $seniorCitizen->signature) }}" alt="Signature" class="img-fluid border p-2" style="max-height: 100px; background-color: #f8f9fa;">
                                            @else
                                                <div class="bg-light p-3 rounded text-center">
                                                    <i class="fas fa-pen-nib fa-2x text-dark mb-2"></i>
                                                    <p class="mb-0 text-muted">No signature</p>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <form action="{{ route('admin.senior-citizens.upload-signature', $seniorCitizen) }}" method="POST" enctype="multipart/form-data">
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
                                                <button type="submit" class="btn btn-dark btn-sm">
                                                    <i class="fas fa-upload mr-1"></i> Upload Signature
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <!-- ID Issuance History -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-outline card-dark">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-history mr-2"></i>ID Issuance History</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if(isset($idHistory) && $idHistory->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped">
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
                                                                    <span class="badge badge-dark">Issued</span>
                                                                @elseif($activity->description == 'updated senior citizen ID information')
                                                                    <span class="badge badge-info">Updated</span>
                                                                @elseif($activity->description == 'sent_senior_citizen_id_card_email')
                                                                    <span class="badge badge-primary">Emailed</span>
                                                                @elseif($activity->description == 'downloaded_senior_citizen_id_card')
                                                                    <span class="badge badge-secondary">Downloaded</span>
                                                                @elseif($activity->description == 'marked_senior_id_for_renewal')
                                                                    <span class="badge badge-dark">Marked for Renewal</span>
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
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle mr-2"></i> No ID issuance history found for this senior citizen.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Back Button -->
                    <div class="row">
                        <div class="col-12">
                            <a href="{{ route('admin.senior-citizens.index') }}" class="btn btn-default">
                                <i class="fas fa-arrow-left mr-2"></i> Back to Senior Citizens
                            </a>
                        </div>
                    </div>
                </div>
            </div>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- Issue Senior Citizen ID Confirmation Modal -->
<div class="modal fade" id="issueSeniorConfirmModal" tabindex="-1" role="dialog" aria-labelledby="issueSeniorConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h4 class="modal-title text-white" id="issueSeniorConfirmModalLabel">
            <i class="fas fa-user-check mr-2"></i>Confirm Senior Citizen ID Issuance
        </h4>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="callout callout-warning">
            <h5>Are you sure you want to issue this Senior Citizen ID?</h5>
            <p class="mb-0">This action will mark the ID as issued and send an email notification if an email address is provided.</p>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">
            <i class="fas fa-times mr-1"></i>Cancel
        </button>
        <button type="button" class="btn btn-dark" id="confirmIssueSeniorBtn">
          <i class="fas fa-user-check mr-2"></i>Issue Senior Citizen ID
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
                    $('img[alt="{{ $seniorCitizen->full_name }}"]').attr('src', e.target.result);
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
        $('#showIssueSeniorModal').on('click', function(e) {
            $('#issueSeniorConfirmModal').modal('show');
        });
        
        $('#confirmIssueSeniorBtn').on('click', function() {
            $('#issueSeniorIdForm').submit();
        });
    });
</script>
@endpush