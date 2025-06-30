@extends('layouts.admin.master')

@section('title', 'Pre-Registration Details - ' . $preRegistration->full_name)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Header -->
            <div class="row mb-2 align-items-center">
                <div class="col">
                    <h2 class="h5 page-title">Pre-Registration Details</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.pre-registrations.index') }}" class="btn btn-outline-secondary">
                        <i class="fe fe-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>

            <!-- Status and Action Cards -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col">
                                    <h4>{{ $preRegistration->full_name }}</h4>
                                    <p class="text-muted mb-0">Registration ID: {{ $preRegistration->id }}</p>
                                </div>
                                <div class="col-auto">
                                    @if($preRegistration->status === 'pending')
                                        <span class="badge badge-warning badge-lg">PENDING REVIEW</span>
                                    @elseif($preRegistration->status === 'approved')
                                        <span class="badge badge-success badge-lg">APPROVED</span>
                                    @else
                                        <span class="badge badge-danger badge-lg">REJECTED</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            @if($preRegistration->status === 'pending')
                                <button type="button" class="btn btn-success btn-block mb-2" 
                                        onclick="approveRegistration({{ $preRegistration->id }})">
                                    <i class="fe fe-check"></i> Approve Registration
                                </button>
                                <button type="button" class="btn btn-danger btn-block" 
                                        onclick="rejectRegistration({{ $preRegistration->id }})">
                                    <i class="fe fe-x"></i> Reject Registration
                                </button>
                            @else
                                <p class="text-muted">Registration has been {{ $preRegistration->status }}</p>
                                @if($preRegistration->status === 'approved' && $preRegistration->resident)
                                    <a href="{{ route('admin.residents.show', $preRegistration->resident) }}" 
                                       class="btn btn-primary btn-block">
                                        <i class="fe fe-user"></i> View Resident Record
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Personal Information -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Personal Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Full Name:</strong></div>
                                <div class="col-sm-8">{{ $preRegistration->full_name }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Gender:</strong></div>
                                <div class="col-sm-8">{{ $preRegistration->sex }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Birthdate:</strong></div>
                                <div class="col-sm-8">
                                    {{ $preRegistration->birthdate->format('F d, Y') }} 
                                    <small class="text-muted">({{ $preRegistration->age }} years old)</small>
                                    @if($preRegistration->is_senior_citizen)
                                        <br><span class="badge badge-warning">Senior Citizen</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Birthplace:</strong></div>
                                <div class="col-sm-8">{{ $preRegistration->birthplace }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Civil Status:</strong></div>
                                <div class="col-sm-8">{{ $preRegistration->civil_status }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Resident Type:</strong></div>
                                <div class="col-sm-8">{{ $preRegistration->type_of_resident }}</div>
                            </div>
                            @if($preRegistration->religion)
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Religion:</strong></div>
                                    <div class="col-sm-8">{{ $preRegistration->religion }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Contact Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Email:</strong></div>
                                <div class="col-sm-8">{{ $preRegistration->email_address }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Phone:</strong></div>
                                <div class="col-sm-8">{{ $preRegistration->contact_number }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Address:</strong></div>
                                <div class="col-sm-8">{{ $preRegistration->address }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Citizenship & Work Information -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Citizenship & Work</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Citizenship:</strong></div>
                                <div class="col-sm-8">
                                    {{ $preRegistration->citizenship_type }}
                                    @if($preRegistration->citizenship_country)
                                        ({{ $preRegistration->citizenship_country }})
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Occupation:</strong></div>
                                <div class="col-sm-8">{{ $preRegistration->profession_occupation }}</div>
                            </div>
                            @if($preRegistration->monthly_income)
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Monthly Income:</strong></div>
                                    <div class="col-sm-8">₱{{ number_format($preRegistration->monthly_income, 2) }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Education Information -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Education</h6>
                        </div>
                        <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Attainment:</strong></div>
                                <div class="col-sm-8">{{ $preRegistration->educational_attainment }}</div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-sm-4"><strong>Status:</strong></div>
                                <div class="col-sm-8">{{ $preRegistration->education_status }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mother's Information -->
                @if($preRegistration->mother_full_name)
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Mother's Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-sm-4"><strong>Name:</strong></div>
                                    <div class="col-sm-8">{{ $preRegistration->mother_full_name }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Population Sectors -->
                @if($preRegistration->population_sectors && is_array($preRegistration->population_sectors) && count($preRegistration->population_sectors) > 0)
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Population Sectors</h6>
                            </div>
                            <div class="card-body">
                                @foreach($preRegistration->population_sectors as $sector)
                                    <span class="badge badge-info mr-1 mb-1">{{ $sector }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- ID Card Images -->
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">ID Card Images</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @if($preRegistration->photo)
                                    <div class="col-md-6 text-center">
                                        <h6>Photo</h6>
                                        <img src="{{ asset('storage/pre-registrations/photos/' . $preRegistration->photo) }}" 
                                             alt="Registration Photo" class="img-thumbnail" style="max-width: 300px;">
                                    </div>
                                @endif
                                
                                @if($preRegistration->signature)
                                    <div class="col-md-6 text-center">
                                        <h6>Signature</h6>
                                        <img src="{{ asset('storage/pre-registrations/signatures/' . $preRegistration->signature) }}" 
                                             alt="Registration Signature" class="img-thumbnail" style="max-width: 300px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Registration Timeline -->
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Registration Timeline</h6>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6>Registration Submitted</h6>
                                        <p class="text-muted mb-0">{{ $preRegistration->created_at->format('F d, Y g:i A') }}</p>
                                    </div>
                                </div>
                                
                                @if($preRegistration->status === 'approved')
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h6>Registration Approved</h6>
                                            <p class="text-muted mb-0">
                                                {{ $preRegistration->approved_at->format('F d, Y g:i A') }}
                                                @if($preRegistration->approvedBy)
                                                    by {{ $preRegistration->approvedBy->name }}
                                                @endif
                                            </p>
                                            @if($preRegistration->resident)
                                                <p class="text-success mb-0">
                                                    <i class="fe fe-check"></i> Resident record created & digital ID sent
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @elseif($preRegistration->status === 'rejected')
                                    <div class="timeline-item">
                                        <div class="timeline-marker bg-danger"></div>
                                        <div class="timeline-content">
                                            <h6>Registration Rejected</h6>
                                            <p class="text-muted mb-0">
                                                {{ $preRegistration->rejected_at->format('F d, Y g:i A') }}
                                                @if($preRegistration->rejectedBy)
                                                    by {{ $preRegistration->rejectedBy->name }}
                                                @endif
                                            </p>
                                            @if($preRegistration->rejection_reason)
                                                <p class="text-danger mb-0">
                                                    <strong>Reason:</strong> {{ $preRegistration->rejection_reason }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
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
                <p>Are you sure you want to approve this registration for <strong>{{ $preRegistration->full_name }}</strong>?</p>
                <div class="alert alert-info">
                    <h6><i class="fe fe-info"></i> This will:</h6>
                    <ul class="mb-0">
                        <li>Create a new resident record in the system</li>
                        <li>Generate a digital ID ({{ $preRegistration->is_senior_citizen ? 'Senior Citizen ID' : 'Resident ID' }})</li>
                        <li>Send the digital ID to the applicant's email</li>
                        @if($preRegistration->is_senior_citizen)
                            <li>Register them as a senior citizen with benefits</li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="approveForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fe fe-check"></i> Approve Registration
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
                    <p>Please provide a reason for rejecting <strong>{{ $preRegistration->full_name }}</strong>'s registration:</p>
                    <textarea class="form-control" name="rejection_reason" rows="3" 
                              placeholder="Enter reason for rejection..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fe fe-x"></i> Reject Registration
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -34px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline::before {
    content: '';
    position: absolute;
    left: -28px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}
</style>
@endsection