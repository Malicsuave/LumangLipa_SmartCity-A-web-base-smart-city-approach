@extends('layouts.admin.master')

@section('title', 'Pre-Registration Management')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fe fe-user-plus fe-16 mr-2"></i>Pre-Registration Management</h4>
                        <p class="text-muted mb-0">Review and approve resident pre-registration applications</p>
                    </div>
                    <div>
                        <a href="{{ route('public.pre-registration.create') }}" class="btn btn-outline-secondary" target="_blank">
                            <i class="fe fe-external-link fe-16 mr-2"></i>View Public Form
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card mb-4 shadow health-metric-card metric-card metric-card-1">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-primary metric-icon">
                                            <i class="fe fe-users text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Total Applications</p>
                                        <span class="h3 metric-counter">{{ $stats['total'] }}</span>
                                        <span class="small text-muted">Submitted</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card mb-4 shadow health-metric-card metric-card metric-card-2">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-warning metric-icon">
                                            <i class="fe fe-clock text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Pending Review</p>
                                        <span class="h3 metric-counter">{{ $stats['pending'] }}</span>
                                        <span class="small text-muted">Applications</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card mb-4 shadow health-metric-card metric-card metric-card-3">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-success metric-icon">
                                            <i class="fe fe-check-circle text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Approved</p>
                                        <span class="h3 metric-counter">{{ $stats['approved'] }}</span>
                                        <span class="small text-muted">Applications</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="card mb-4 shadow health-metric-card metric-card metric-card-4">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-danger metric-icon">
                                            <i class="fe fe-x-circle text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">Rejected</p>
                                        <span class="h3 metric-counter">{{ $stats['rejected'] }}</span>
                                        <span class="small text-muted">Applications</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filter Form -->
                <form method="GET" action="{{ route('admin.pre-registrations.index') }}" id="filterForm">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Search by name, email, or phone..." 
                                       value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fe fe-search fe-16"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-control" name="status">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            @if(request()->hasAny(['search', 'status']))
                                <a href="{{ route('admin.pre-registrations.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fe fe-refresh-cw fe-16 mr-1"></i>Clear Filters
                                </a>
                            @else
                                <button type="submit" class="btn btn-outline-secondary w-100" disabled>
                                    <i class="fe fe-filter fe-16 mr-1"></i>Apply Filters
                                </button>
                            @endif
                        </div>
                    </div>
                </form>

                <!-- Applications Table -->
                @if($preRegistrations->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th>Age</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($preRegistrations as $registration)
                                    <tr>
                                        <td>
                                            <strong>{{ $registration->full_name }}</strong>
                                            @if($registration->is_senior_citizen)
                                                <br><small class="badge badge-warning">Senior Citizen</small>
                                            @endif
                                        </td>
                                        <td>{{ $registration->email_address }}</td>
                                        <td>{{ $registration->contact_number }}</td>
                                        <td>{{ $registration->age }}</td>
                                        <td>{{ $registration->type_of_resident }}</td>
                                        <td>
                                            @if($registration->status === 'pending')
                                                <span class="badge badge-warning">Pending</span>
                                            @elseif($registration->status === 'approved')
                                                <span class="badge badge-success">Approved</span>
                                            @else
                                                <span class="badge badge-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>{{ $registration->created_at->format('M d, Y') }}</td>
                                        <td class="text-center">
                                            <div class="dropdown">
                                                <button class="btn btn-sm dropdown-toggle more-vertical" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Action</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="{{ route('admin.pre-registrations.show', $registration) }}" class="dropdown-item">
                                                        <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                                    </a>
                                                    
                                                    @if($registration->status === 'pending')
                                                        <div class="dropdown-divider"></div>
                                                        <button type="button" class="dropdown-item" onclick="approveRegistration({{ $registration->id }})">
                                                            <i class="fe fe-check fe-16 mr-2 text-success"></i>Approve
                                                        </button>
                                                        <button type="button" class="dropdown-item" onclick="rejectRegistration({{ $registration->id }})">
                                                            <i class="fe fe-x fe-16 mr-2 text-warning"></i>Reject
                                                        </button>
                                                    @endif
                                                    
                                                    <div class="dropdown-divider"></div>
                                                    <button type="button" class="dropdown-item text-danger" onclick="deleteRegistration({{ $registration->id }})">
                                                        <i class="fe fe-trash-2 fe-16 mr-2"></i>Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $preRegistrations->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <img src="{{ asset('images/empty.svg') }}" alt="No pre-registrations found" class="img-fluid mb-3" width="200">
                        <h4>No pre-registrations found</h4>
                        <p class="text-muted">
                            @if(request()->hasAny(['search', 'status']))
                                No applications match your search criteria. <a href="{{ route('admin.pre-registrations.index') }}">Clear all filters</a>
                            @else
                                No pre-registration applications have been submitted yet.
                            @endif
                        </p>
                        <a href="{{ route('public.pre-registration.create') }}" class="btn btn-primary" target="_blank">
                            <i class="fe fe-external-link fe-16 mr-2"></i>View Public Registration Form
                        </a>
                    </div>
                @endif
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
                    <i class="fe fe-info"></i> This will create a resident record and send a digital ID to the applicant's email.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="approveForm" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fe fe-check"></i> Approve
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
                        <i class="fe fe-x"></i> Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Registration</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to permanently delete this registration?</p>
                <p class="text-danger">
                    <i class="fe fe-alert-triangle"></i> This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fe fe-trash-2"></i> Delete
                    </button>
                </form>
            </div>
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

function deleteRegistration(id) {
    document.getElementById('deleteForm').action = `/admin/pre-registrations/${id}`;
    $('#deleteModal').modal('show');
}
</script>
@endsection