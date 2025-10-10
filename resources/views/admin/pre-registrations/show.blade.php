@extends('layouts.admin.master')

@section('title', 'Pre-Registration Details - ' . $preRegistration->full_name)

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-pre-registration-details.css') }}">
@endpush

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Pre-Registration Details</h1>
                <small class="text-muted">{{ $preRegistration->full_name }}</small>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.pre-registrations.index') }}">Pre-Registrations</a></li>
                    <li class="breadcrumb-item active">{{ $preRegistration->full_name }}</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Status and Action Cards -->
        <div class="row mb-3">
            <div class="col-md-8">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <div class="row align-items-center pre-registration-header">
                            <div class="col">
                                <h4 class="mb-1">{{ $preRegistration->full_name }}</h4>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-hashtag mr-1"></i>Registration ID: <strong>{{ $preRegistration->registration_id ?? ($preRegistration->created_at ? 'PRE-' . $preRegistration->created_at->format('Y-m') . '-' . str_pad($preRegistration->id, 5, '0', STR_PAD_LEFT) : 'PRE-' . str_pad($preRegistration->id, 5, '0', STR_PAD_LEFT)) }}</strong>
                                </p>
                            </div>
                            <div class="col-auto">
                                @if($preRegistration->status === 'pending')
                                    <span class="badge badge-warning p-2" style="font-size: 1rem;">
                                        <i class="fas fa-clock mr-1"></i>PENDING REVIEW
                                    </span>
                                @elseif($preRegistration->status === 'approved')
                                    <span class="badge badge-success p-2" style="font-size: 1rem;">
                                        <i class="fas fa-check-circle mr-1"></i>APPROVED
                                    </span>
                                @else
                                    <span class="badge badge-danger p-2" style="font-size: 1rem;">
                                        <i class="fas fa-times-circle mr-1"></i>REJECTED
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center p-3">
                        @if($preRegistration->status === 'pending')
                            <button type="button" class="btn btn-success btn-block mb-2" 
                                    onclick="approveRegistration({{ $preRegistration->id }})">
                                <i class="fas fa-check mr-1"></i> Approve Registration
                            </button>
                            <button type="button" class="btn btn-danger btn-block" 
                                    onclick="rejectRegistration({{ $preRegistration->id }})">
                                <i class="fas fa-times mr-1"></i> Reject Registration
                            </button>
                        @else
                            <p class="text-muted mb-2">Registration has been {{ $preRegistration->status }}</p>
                            @if($preRegistration->status === 'approved' && $preRegistration->resident)
                                <a href="{{ route('admin.residents.show', $preRegistration->resident) }}" 
                                   class="btn btn-primary btn-block">
                                    <i class="fas fa-user mr-1"></i> View Resident Record
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Consolidated Information Card -->
        <div class="row">
            <div class="col-12 mb-3">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle mr-2"></i>Registration Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Personal Information -->
                            <div class="col-md-6 mb-4">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-user mr-2"></i>Personal Information
                                </h5>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="40%" class="text-muted"><strong>Full Name:</strong></td>
                                        <td>{{ $preRegistration->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Gender:</strong></td>
                                        <td>{{ $preRegistration->sex }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Birthdate:</strong></td>
                                        <td>
                                            {{ $preRegistration->birthdate ? $preRegistration->birthdate->format('F d, Y') : 'N/A' }} 
                                            @if($preRegistration->birthdate)
                                                <small class="text-muted">({{ $preRegistration->age }} years old)</small>
                                            @endif
                                            @if($preRegistration->is_senior_citizen)
                                                <br><span class="badge badge-warning mt-1"><i class="fas fa-user-check mr-1"></i>Senior Citizen</span>
                                            @else
                                                <br><span class="badge badge-info mt-1"><i class="fas fa-user mr-1"></i>Regular Resident</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Birthplace:</strong></td>
                                        <td>{{ $preRegistration->birthplace }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Civil Status:</strong></td>
                                        <td>{{ $preRegistration->civil_status }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Resident Type:</strong></td>
                                        <td>{{ $preRegistration->type_of_resident }}</td>
                                    </tr>
                                    @if($preRegistration->religion)
                                    <tr>
                                        <td class="text-muted"><strong>Religion:</strong></td>
                                        <td>{{ $preRegistration->religion }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>

                            <!-- Contact Information -->
                            <div class="col-md-6 mb-4">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-address-book mr-2"></i>Contact Information
                                </h5>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="40%" class="text-muted"><strong>Email:</strong></td>
                                        <td>
                                            @if($preRegistration->email_address)
                                                <i class="fas fa-envelope mr-1"></i>{{ $preRegistration->email_address }}
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Phone:</strong></td>
                                        <td><i class="fas fa-phone mr-1"></i>{{ $preRegistration->contact_number }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Address:</strong></td>
                                        <td><i class="fas fa-map-marker-alt mr-1"></i>{{ $preRegistration->address }}</td>
                                    </tr>
                                </table>

                                <h5 class="text-primary border-bottom pb-2 mb-3 mt-4">
                                    <i class="fas fa-phone-alt mr-2"></i>Emergency Contact
                                </h5>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="40%" class="text-muted"><strong>Name:</strong></td>
                                        <td>{{ $preRegistration->emergency_contact_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Relationship:</strong></td>
                                        <td>{{ $preRegistration->emergency_contact_relationship ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Phone:</strong></td>
                                        <td>{{ $preRegistration->emergency_contact_number ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Address:</strong></td>
                                        <td>{{ $preRegistration->emergency_contact_address ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Citizenship & Work Information -->
                            <div class="col-md-6 mb-4">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-briefcase mr-2"></i>Citizenship & Work
                                </h5>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="40%" class="text-muted"><strong>Citizenship:</strong></td>
                                        <td>
                                            {{ $preRegistration->citizenship_type }}
                                            @if($preRegistration->citizenship_country)
                                                ({{ $preRegistration->citizenship_country }})
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Occupation:</strong></td>
                                        <td>{{ $preRegistration->profession_occupation }}</td>
                                    </tr>
                                    @if($preRegistration->monthly_income)
                                    <tr>
                                        <td class="text-muted"><strong>Monthly Income:</strong></td>
                                        <td>₱{{ number_format($preRegistration->monthly_income, 2) }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>

                            <!-- Education Information -->
                            <div class="col-md-6 mb-4">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-graduation-cap mr-2"></i>Education
                                </h5>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="40%" class="text-muted"><strong>Attainment:</strong></td>
                                        <td>{{ $preRegistration->educational_attainment }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted"><strong>Status:</strong></td>
                                        <td>{{ $preRegistration->education_status }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Mother's Information -->
                            @if($preRegistration->mother_full_name)
                            <div class="col-md-6 mb-4">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-female mr-2"></i>Mother's Information
                                </h5>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="40%" class="text-muted"><strong>Name:</strong></td>
                                        <td>{{ $preRegistration->mother_full_name }}</td>
                                    </tr>
                                </table>
                            </div>
                            @endif

                            <!-- Population Sectors -->
                            @if($preRegistration->population_sectors && is_array($preRegistration->population_sectors) && count($preRegistration->population_sectors) > 0)
                            <div class="col-md-6 mb-4">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-users mr-2"></i>Population Sectors
                                </h5>
                                <div>
                                    @foreach($preRegistration->population_sectors as $sector)
                                        <span class="badge badge-info mr-1 mb-1">{{ $sector }}</span>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Proof of Residency -->
                        @if($preRegistration->proof_of_residency)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-file-alt mr-2"></i>Proof of Residency
                                </h5>
                                <div class="text-center">
                                    @php
                                        $proofPath = 'storage/pre-registrations/proof-of-residency/' . $preRegistration->proof_of_residency;
                                        $isImage = in_array(pathinfo($preRegistration->proof_of_residency, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']);
                                    @endphp
                                    
                                    @if($isImage)
                                        <img src="{{ asset($proofPath) }}" 
                                             alt="Proof of Residency" class="img-thumbnail img-fluid proof-of-residency-img"
                                             onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}'; this.alt='Image not found';">
                                    @else
                                        <div class="alert alert-info">
                                            <i class="fas fa-file-pdf fa-3x mb-2"></i>
                                            <p>PDF Document</p>
                                        </div>
                                    @endif
                                    <br>
                                    <a href="{{ asset($proofPath) }}" 
                                       target="_blank" class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-external-link-alt mr-1"></i>Open in New Tab
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- ID Card Images -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="text-primary border-bottom pb-2 mb-3">
                                    <i class="fas fa-images mr-2"></i>ID Card Images
                                </h5>
                                <div class="row">
                                    @if($preRegistration->photo)
                                    <div class="col-md-6 text-center mb-3">
                                        <h6 class="text-muted mb-2"><i class="fas fa-camera mr-1"></i>Photo</h6>
                                        <img src="{{ asset('storage/pre-registrations/photos/' . $preRegistration->photo) }}" 
                                             alt="Registration Photo" class="img-thumbnail img-fluid id-card-image">
                                    </div>
                                    @endif
                                    
                                    @if($preRegistration->signature)
                                    <div class="col-md-6 text-center mb-3">
                                        <h6 class="text-muted mb-2"><i class="fas fa-signature mr-1"></i>Signature</h6>
                                        <img src="{{ asset('storage/pre-registrations/signatures/' . $preRegistration->signature) }}" 
                                             alt="Registration Signature" class="img-thumbnail img-fluid id-card-image">
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <!-- Registration Timeline -->
            <div class="col-md-12 mb-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-history mr-2"></i>Registration Timeline</h3>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div>
                                <i class="fas fa-file-alt bg-primary"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock mr-1"></i>{{ $preRegistration->created_at ? $preRegistration->created_at->format('M d, Y g:i A') : 'N/A' }}</span>
                                    <h3 class="timeline-header">Registration Submitted</h3>
                                    <div class="timeline-body">
                                        Pre-registration application was submitted by {{ $preRegistration->full_name }}
                                    </div>
                                </div>
                            </div>
                            
                            @if($preRegistration->status === 'approved')
                            <div>
                                <i class="fas fa-check-circle bg-success"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock mr-1"></i>{{ $preRegistration->approved_at ? $preRegistration->approved_at->format('M d, Y g:i A') : 'N/A' }}</span>
                                    <h3 class="timeline-header">Registration Approved</h3>
                                    <div class="timeline-body">
                                        @if($preRegistration->approvedBy)
                                            Approved by <strong>{{ $preRegistration->approvedBy->name }}</strong>
                                        @endif
                                        @if($preRegistration->resident)
                                            <br><span class="badge badge-success mt-1"><i class="fas fa-check mr-1"></i>Resident record created & digital ID sent</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @elseif($preRegistration->status === 'rejected')
                            <div>
                                <i class="fas fa-times-circle bg-danger"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock mr-1"></i>{{ $preRegistration->rejected_at ? $preRegistration->rejected_at->format('M d, Y g:i A') : 'N/A' }}</span>
                                    <h3 class="timeline-header text-danger">Registration Rejected</h3>
                                    <div class="timeline-body">
                                        @if($preRegistration->rejectedBy)
                                            Rejected by <strong>{{ $preRegistration->rejectedBy->name }}</strong>
                                        @endif
                                        @if($preRegistration->rejection_reason)
                                            <br><div class="alert alert-danger mt-2 mb-0">
                                                <strong>Reason:</strong> {{ $preRegistration->rejection_reason }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div>
                                <i class="fas fa-clock bg-gray"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Approve Confirmation Modal -->
        <div class="modal fade" id="approveModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Approve Registration</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to approve this registration?</p>
                        <p class="text-info">
                            <i class="fas fa-info-circle"></i> This will create a resident record and send a digital ID to the applicant's email.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <form id="approveForm" method="POST" class="admin-inline-form">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check-circle mr-2"></i> Approve
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Registration</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <form id="rejectForm" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p>Please provide a reason for rejecting this registration:</p>
                            <textarea class="form-control" name="rejection_reason" rows="3" 
                                      placeholder="Enter reason for rejection..." required></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times"></i> Reject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function approveRegistration(id) {
    document.getElementById('approveForm').action = `/admin/pre-registrations/${id}/approve`;
    $('#approveModal').modal('show');
}

function rejectRegistration(id) {
    document.getElementById('rejectForm').action = `/admin/pre-registrations/${id}/reject`;
    $('#rejectModal').modal('show');
}
</script>
@endpush