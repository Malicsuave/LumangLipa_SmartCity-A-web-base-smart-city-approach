@extends('layouts.admin.master')

@section('title', 'GAD Record Details')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">GAD Record Details</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.gad.index') }}">GAD Records</a></li>
        <li class="breadcrumb-item active">Record Details</li>
    </ol>
    
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="btn-group float-end" role="group">
                <a href="{{ route('admin.gad.edit', $gad->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i> Edit Record
                </a>
                <form action="{{ route('admin.gad.destroy', $gad->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this GAD record? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Delete
                    </button>
                </form>
                <a href="{{ route('admin.residents.show', $gad->resident_id) }}" class="btn btn-info">
                    <i class="fas fa-user me-1"></i> View Resident Profile
                </a>
            </div>
        </div>
    </div>
    
    <!-- Basic Information Card -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-info-circle me-1"></i>
            Basic Information
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h5>Resident</h5>
                    <p class="mb-0">{{ $gad->resident->full_name }}</p>
                    <small class="text-muted">Barangay ID: {{ $gad->resident->barangay_id }}</small>
                </div>
                <div class="col-md-6 mb-3">
                    <h5>Gender Identity</h5>
                    <p>{{ $gad->gender_identity }}</p>
                    @if($gad->gender_details)
                        <small class="text-muted">Additional details: {{ $gad->gender_details }}</small>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <h5>Record Created</h5>
                    <p>{{ $gad->created_at->format('F j, Y, g:i a') }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <h5>Last Updated</h5>
                    <p>{{ $gad->updated_at->format('F j, Y, g:i a') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Program Enrollment Card -->
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-list-alt me-1"></i>
            Program Enrollment
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h5>Programs Enrolled</h5>
                    @if($gad->programs_enrolled && count($gad->programs_enrolled) > 0)
                        @foreach($gad->programs_enrolled as $program)
                            <span class="badge bg-primary me-1 mb-1">{{ $program }}</span>
                        @endforeach
                    @else
                        <p class="text-muted">No programs enrolled</p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5>Enrollment Date</h5>
                    <p>{{ $gad->enrollment_date ? $gad->enrollment_date->format('F j, Y') : 'Not specified' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Program End Date</h5>
                    <p>{{ $gad->program_end_date ? $gad->program_end_date->format('F j, Y') : 'Not specified' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Program Status</h5>
                    <p>
                        @if($gad->program_status)
                            @if($gad->program_status == 'Active')
                                <span class="badge bg-success">Active</span>
                            @elseif($gad->program_status == 'Completed')
                                <span class="badge bg-info">Completed</span>
                            @elseif($gad->program_status == 'On Hold')
                                <span class="badge bg-warning text-dark">On Hold</span>
                            @elseif($gad->program_status == 'Discontinued')
                                <span class="badge bg-danger">Discontinued</span>
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
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <i class="fas fa-baby me-1"></i>
            Pregnancy Information
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5>Due Date</h5>
                    <p>{{ $gad->due_date ? $gad->due_date->format('F j, Y') : 'Not specified' }}</p>
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
                    <h5>Lactating</h5>
                    <p>{{ $gad->is_lactating ? 'Yes' : 'No' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Needs Maternity Support</h5>
                    <p>{{ $gad->needs_maternity_support ? 'Yes' : 'No' }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Solo Parent Information -->
    @if($gad->is_solo_parent)
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <i class="fas fa-user-friends me-1"></i>
            Solo Parent Information
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5>Solo Parent ID</h5>
                    <p>{{ $gad->solo_parent_id ?: 'Not issued' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>ID Issued Date</h5>
                    <p>{{ $gad->solo_parent_id_issued ? $gad->solo_parent_id_issued->format('F j, Y') : 'Not specified' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>ID Expiry Date</h5>
                    <p>
                        {{ $gad->solo_parent_id_expiry ? $gad->solo_parent_id_expiry->format('F j, Y') : 'Not specified' }}
                        @if($gad->solo_parent_id_expiry && $gad->needsSoloParentIdRenewal())
                            <span class="badge bg-danger ms-1">Needs renewal</span>
                        @endif
                    </p>
                </div>
            </div>
            @if($gad->solo_parent_details)
            <div class="row">
                <div class="col-md-12">
                    <h5>Details</h5>
                    <p class="mb-0">{{ $gad->solo_parent_details }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
    
    <!-- VAW Case Information -->
    @if($gad->is_vaw_case)
    <div class="card mb-4">
        <div class="card-header bg-danger text-white">
            <i class="fas fa-exclamation-triangle me-1"></i>
            Violence Against Women (VAW) Case
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5>Case Number</h5>
                    <p>{{ $gad->vaw_case_number ?: 'Not assigned' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Report Date</h5>
                    <p>{{ $gad->vaw_report_date ? $gad->vaw_report_date->format('F j, Y') : 'Not specified' }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5>Case Status</h5>
                    <p>
                        @if($gad->vaw_case_status == 'Pending')
                            <span class="badge bg-warning text-dark">Pending Investigation</span>
                        @elseif($gad->vaw_case_status == 'Ongoing')
                            <span class="badge bg-primary">Ongoing Case</span>
                        @elseif($gad->vaw_case_status == 'Resolved')
                            <span class="badge bg-success">Case Resolved</span>
                        @elseif($gad->vaw_case_status == 'Closed')
                            <span class="badge bg-secondary">Case Closed</span>
                        @else
                            {{ $gad->vaw_case_status ?: 'Not specified' }}
                        @endif
                    </p>
                </div>
            </div>
            @if($gad->vaw_case_details)
            <div class="row">
                <div class="col-md-12">
                    <h5>Case Details</h5>
                    <p class="mb-0">{{ $gad->vaw_case_details }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif
    
    <!-- Additional Notes -->
    @if($gad->notes)
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-sticky-note me-1"></i>
            Additional Notes
        </div>
        <div class="card-body">
            <p class="mb-0">{{ $gad->notes }}</p>
        </div>
    </div>
    @endif
</div>
@endsection