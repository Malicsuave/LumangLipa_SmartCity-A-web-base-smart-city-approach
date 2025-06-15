@extends('layouts.admin.master')

@section('title', 'GAD Record Details')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h3 page-title">GAD Record Details</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.gad.index') }}" class="btn btn-primary mr-2">
                        <i class="fe fe-arrow-left mr-2"></i> Back to List
                    </a>
                    <div class="dropdown d-inline">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="actionDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fe fe-more-vertical"></i> Actions
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="actionDropdown">
                            <a href="{{ route('admin.gad.edit', $gad->id) }}" class="dropdown-item">
                                <i class="fe fe-edit mr-1"></i> Edit Record
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('admin.gad.destroy', $gad->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this GAD record? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fe fe-trash mr-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            @endif
            
            <!-- Basic Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Basic Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1 small">Resident</h6>
                            <p class="mb-0 font-weight-bold">{{ $gad->resident->full_name }}</p>
                            <small class="text-muted">Barangay ID: {{ $gad->resident->barangay_id }}</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1 small">Gender Identity</h6>
                            <p class="mb-0 font-weight-bold">{{ $gad->gender_identity }}</p>
                            @if($gad->gender_details)
                                <small class="text-muted">Additional details: {{ $gad->gender_details }}</small>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1 small">Record Created</h6>
                            <p class="mb-0">{{ $gad->created_at->format('F j, Y, g:i a') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1 small">Last Updated</h6>
                            <p class="mb-0">{{ $gad->updated_at->format('F j, Y, g:i a') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Program Enrollment Card -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Program Enrollment</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h6 class="text-muted mb-1 small">Programs Enrolled</h6>
                            <div>
                                @if($gad->programs_enrolled && count($gad->programs_enrolled) > 0)
                                    @foreach($gad->programs_enrolled as $program)
                                        <span class="badge badge-primary mr-1 mb-1">{{ $program }}</span>
                                    @endforeach
                                @else
                                    <p class="text-muted">No programs enrolled</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted mb-1 small">Enrollment Date</h6>
                            <p class="mb-0">{{ $gad->enrollment_date ? $gad->enrollment_date->format('F j, Y') : 'Not specified' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted mb-1 small">Program End Date</h6>
                            <p class="mb-0">{{ $gad->program_end_date ? $gad->program_end_date->format('F j, Y') : 'Not specified' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted mb-1 small">Program Status</h6>
                            <p class="mb-0">
                                @if($gad->program_status)
                                    @if($gad->program_status == 'Active')
                                        <span class="badge badge-success">Active</span>
                                    @elseif($gad->program_status == 'Completed')
                                        <span class="badge badge-info">Completed</span>
                                    @elseif($gad->program_status == 'On Hold')
                                        <span class="badge badge-warning">On Hold</span>
                                    @elseif($gad->program_status == 'Discontinued')
                                        <span class="badge badge-danger">Discontinued</span>
                                    @else
                                        {{ $gad->program_status }}
                                    @endif
                                @else
                                    Not specified
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Pregnancy Information -->
            @if($gad->is_pregnant)
            <div class="card shadow mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="card-title mb-0">Pregnancy Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted mb-1 small">Due Date</h6>
                            <p class="mb-0">{{ $gad->due_date ? $gad->due_date->format('F j, Y') : 'Not specified' }}</p>
                            @if($gad->due_date)
                                <small class="text-muted">
                                    @if($gad->days_until_due > 0)
                                        {{ $gad->days_until_due }} days remaining
                                    @elseif($gad->days_until_due == 0)
                                        <span class="text-danger">Due today!</span>
                                    @else
                                        <span class="text-danger">Past due date</span>
                                    @endif
                                </small>
                            @endif
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted mb-1 small">Lactating</h6>
                            <p class="mb-0">{{ $gad->is_lactating ? 'Yes' : 'No' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted mb-1 small">Needs Maternity Support</h6>
                            <p class="mb-0">{{ $gad->needs_maternity_support ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Solo Parent Information -->
            @if($gad->is_solo_parent)
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h6 class="card-title mb-0">Solo Parent Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted mb-1 small">Solo Parent ID</h6>
                            <p class="mb-0">{{ $gad->solo_parent_id ?: 'Not issued' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted mb-1 small">ID Issued Date</h6>
                            <p class="mb-0">{{ $gad->solo_parent_id_issued ? $gad->solo_parent_id_issued->format('F j, Y') : 'Not specified' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted mb-1 small">ID Expiry Date</h6>
                            <p class="mb-0">
                                {{ $gad->solo_parent_id_expiry ? $gad->solo_parent_id_expiry->format('F j, Y') : 'Not specified' }}
                                @if($gad->solo_parent_id_expiry && $gad->needsSoloParentIdRenewal())
                                    <span class="badge badge-danger ml-1">Needs renewal</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($gad->solo_parent_details)
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-muted mb-1 small">Details</h6>
                            <p class="mb-0">{{ $gad->solo_parent_details }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
            
            <!-- VAW Case Information -->
            @if($gad->is_vaw_case)
            <div class="card shadow mb-4">
                <div class="card-header bg-danger text-white">
                    <h6 class="card-title mb-0">Violence Against Women (VAW) Case</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted mb-1 small">Case Number</h6>
                            <p class="mb-0">{{ $gad->vaw_case_number ?: 'Not assigned' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted mb-1 small">Report Date</h6>
                            <p class="mb-0">{{ $gad->vaw_report_date ? $gad->vaw_report_date->format('F j, Y') : 'Not specified' }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <h6 class="text-muted mb-1 small">Case Status</h6>
                            <p class="mb-0">
                                @if($gad->vaw_case_status == 'Pending')
                                    <span class="badge badge-warning">Pending Investigation</span>
                                @elseif($gad->vaw_case_status == 'Ongoing')
                                    <span class="badge badge-primary">Ongoing Case</span>
                                @elseif($gad->vaw_case_status == 'Resolved')
                                    <span class="badge badge-success">Case Resolved</span>
                                @elseif($gad->vaw_case_status == 'Closed')
                                    <span class="badge badge-secondary">Case Closed</span>
                                @else
                                    {{ $gad->vaw_case_status ?: 'Not specified' }}
                                @endif
                            </p>
                        </div>
                    </div>
                    @if($gad->vaw_case_details)
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-muted mb-1 small">Case Details</h6>
                            <p class="mb-0">{{ $gad->vaw_case_details }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif
            
            <!-- Additional Notes -->
            @if($gad->notes)
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">Additional Notes</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $gad->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection