@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h1 class="h3 mb-0 text-gray-800">Health Services Management</h1>
    </div>
</div>

<!-- Health Service Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card mb-4 shadow">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-warning">
                            <i class="fe fe-clock text-white"></i>
                        </span>
                    </div>
                    <div class="col">
                        <p class="small text-muted mb-0">Pending</p>
                        <span class="h3">{{ $healthRequests->where('status', 'pending')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mb-4 shadow">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-success">
                            <i class="fe fe-check text-white"></i>
                        </span>
                    </div>
                    <div class="col">
                        <p class="small text-muted mb-0">Approved</p>
                        <span class="h3">{{ $healthRequests->where('status', 'approved')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mb-4 shadow">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-info">
                            <i class="fe fe-calendar text-white"></i>
                        </span>
                    </div>
                    <div class="col">
                        <p class="small text-muted mb-0">Scheduled</p>
                        <span class="h3">{{ $healthRequests->where('status', 'scheduled')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mb-4 shadow">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-danger">
                            <i class="fe fe-x text-white"></i>
                        </span>
                    </div>
                    <div class="col">
                        <p class="small text-muted mb-0">Rejected</p>
                        <span class="h3">{{ $healthRequests->where('status', 'rejected')->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Health Service Requests Table -->
<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Health Service Requests Management</strong>
            </div>
            <div class="card-body">
                @if($healthRequests->count() > 0)                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="thead-light">                            <tr>
                                <th width="8%">ID</th>
                                <th width="20%">Resident</th>
                                <th width="15%">Service Type</th>
                                <th width="12%">Status</th>
                                <th width="15%">Requested</th>
                                <th width="30%" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($healthRequests as $request)
                            <tr>
                                <td>
                                    <span class="badge badge-light font-weight-bold">HSR-{{ str_pad($request->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $request->resident_name }}</strong>
                                        <br><small class="text-muted">ID: {{ $request->barangay_id }}</small>
                                    </div>
                                </td>                                <td>
                                    <span class="text-capitalize">{{ str_replace('_', ' ', $request->service_type) }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($request->status) {
                                            'pending' => 'badge-warning',
                                            'approved' => 'badge-success',
                                            'scheduled' => 'badge-info',
                                            'completed' => 'badge-primary',
                                            'rejected' => 'badge-danger',
                                            default => 'badge-secondary'
                                        };
                                    @endphp
                                    <span class="badge {{ $statusClass }} text-capitalize">{{ $request->status }}</span>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        <div>{{ $request->requested_at->format('M d, Y') }}</div>
                                        <small>{{ $request->requested_at->format('h:i A') }}</small>
                                    </div>
                                </td>                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon" type="button" id="dropdownMenuButton-health{{ $request->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fe fe-more-vertical fe-16"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton-health{{ $request->id }}">
                                            <a class="dropdown-item view-request" href="#" data-id="{{ $request->id }}">
                                                <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                            </a>
                                            
                                            @if($request->status === 'pending')
                                                <a class="dropdown-item approve-request" href="#" data-id="{{ $request->id }}">
                                                    <i class="fe fe-check-circle fe-16 mr-2 text-success"></i>Approve
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item reject-request text-danger" href="#" data-id="{{ $request->id }}">
                                                    <i class="fe fe-x-circle fe-16 mr-2"></i>Reject
                                                </a>
                                            @endif
                                            
                                            @if($request->status === 'approved')
                                                <a class="dropdown-item schedule-meeting" href="#" 
                                                   data-id="{{ $request->id }}" 
                                                   data-resident="{{ $request->resident_name }}"
                                                   data-service="{{ ucwords(str_replace('_', ' ', $request->service_type)) }}">
                                                    <i class="fe fe-calendar fe-16 mr-2 text-info"></i>Schedule Meeting
                                                </a>
                                                <a class="dropdown-item complete-request" href="#" data-id="{{ $request->id }}">
                                                    <i class="fe fe-check-circle fe-16 mr-2 text-success"></i>Mark Complete
                                                </a>
                                            @endif
                                            
                                            @if($request->status === 'scheduled')
                                                <a class="dropdown-item complete-request" href="#" data-id="{{ $request->id }}">
                                                    <i class="fe fe-check-circle fe-16 mr-2 text-success"></i>Mark Complete
                                                </a>
                                                <a class="dropdown-item view-meeting" href="#" data-id="{{ $request->id }}">
                                                    <i class="fe fe-calendar fe-16 mr-2 text-info"></i>View Meeting Details
                                                </a>
                                            @endif
                                            
                                            @if($request->status === 'completed')
                                                <a class="dropdown-item view-history" href="#" data-id="{{ $request->id }}">
                                                    <i class="fe fe-clock fe-16 mr-2 text-secondary"></i>View History
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        Showing {{ $healthRequests->firstItem() ?? 0 }} to {{ $healthRequests->lastItem() ?? 0 }} of {{ $healthRequests->total() }} health service requests
                    </div>
                    {{ $healthRequests->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fe fe-heart fe-48 text-muted mb-3"></i>
                    <h5 class="text-muted">No health service requests found</h5>
                    <p class="text-muted">Health service requests will appear here once residents submit them.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- View Request Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Health Service Request Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="requestDetails">
                    <!-- Details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Request Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Health Service Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="rejectForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejection_reason">Rejection Reason</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="4" required placeholder="Please provide a reason for rejecting this request..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Schedule Meeting Modal -->
<div class="modal fade" id="scheduleMeetingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Health Service Meeting</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="scheduleMeetingForm">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <div id="meetingResidentInfo">
                            <!-- Resident info will be populated here -->
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="meeting_title">Meeting Title</label>
                                <input type="text" class="form-control" id="meeting_title" name="meeting_title" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="meeting_date">Meeting Date & Time</label>
                                <input type="datetime-local" class="form-control" id="meeting_date" name="meeting_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="meeting_location">Meeting Location</label>
                                <input type="text" class="form-control" id="meeting_location" name="meeting_location" value="Barangay Health Center" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="meeting_notes">Meeting Notes</label>
                        <textarea class="form-control" id="meeting_notes" name="meeting_notes" rows="3" placeholder="Additional notes for the meeting..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Schedule Meeting</button>                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    let currentRequestId = null;

    // View Request
    $('.view-request').on('click', function(e) {
        e.preventDefault();
        const requestId = $(this).data('id');
        
        fetch(`/admin/health-services/${requestId}`)
            .then(response => response.json())
            .then(data => {
                const details = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fe fe-user"></i> Resident Information</h6>
                            <table class="table table-sm">
                                <tr><td><strong>Name:</strong></td><td>${data.resident_name}</td></tr>
                                <tr><td><strong>Barangay ID:</strong></td><td>${data.barangay_id}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fe fe-clipboard"></i> Request Information</h6>                            <table class="table table-sm">
                                <tr><td><strong>Service Type:</strong></td><td class="text-capitalize">${data.service_type.replace(/_/g, ' ')}</td></tr>
                                <tr><td><strong>Status:</strong></td><td><span class="badge badge-${data.status === 'completed' ? 'primary' : data.status === 'approved' ? 'success' : data.status === 'scheduled' ? 'info' : data.status === 'rejected' ? 'danger' : 'warning'}">${data.status.toUpperCase()}</span></td></tr>
                            </table>
                        </div>
                    </div>                    <hr>
                    <h6><i class="fe fe-file-text"></i> Purpose</h6>
                    <p class="mb-3">${data.purpose}</p>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="fe fe-clock"></i> Requested:</strong><br>${new Date(data.requested_at).toLocaleString()}</p>
                        </div>
                        <div class="col-md-6">
                            ${data.approved_at ? `<p><strong><i class="fe fe-check"></i> Approved:</strong><br>${new Date(data.approved_at).toLocaleString()}</p>` : ''}
                            ${data.scheduled_at ? `<p><strong><i class="fe fe-calendar"></i> Scheduled:</strong><br>${new Date(data.scheduled_at).toLocaleString()}</p>` : ''}
                        </div>
                    </div>
                    ${data.rejection_reason ? `<div class="alert alert-danger"><strong>Rejection Reason:</strong> ${data.rejection_reason}</div>` : ''}
                `;
                $('#requestDetails').html(details);
                $('#viewModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading request details');
            });
    });

    // Approve Request
    $('.approve-request').on('click', function(e) {
        e.preventDefault();
        const requestId = $(this).data('id');
        
        if (confirm('Are you sure you want to approve this health service request?')) {
            fetch(`/admin/health-services/${requestId}/approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error approving request');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error approving request');
            });
        }
    });

    // Complete Request
    $('.complete-request').on('click', function(e) {
        e.preventDefault();
        const requestId = $(this).data('id');
        
        if (confirm('Are you sure you want to mark this health service request as completed?')) {
            fetch(`/admin/health-services/${requestId}/complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error completing request');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error completing request');
            });
        }
    });

    // Reject Request
    $('.reject-request').on('click', function(e) {
        e.preventDefault();
        currentRequestId = $(this).data('id');
        $('#rejectModal').modal('show');
    });

    // Schedule Meeting
    $('.schedule-meeting').on('click', function(e) {
        e.preventDefault();
        currentRequestId = $(this).data('id');
        const residentName = $(this).data('resident');
        const serviceType = $(this).data('service');
        
        // Pre-fill the meeting title
        $('#meeting_title').val(`${serviceType} - ${residentName}`);
        
        // Set minimum date to current date
        const now = new Date();
        const minDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000).toISOString().slice(0, 16);
        $('#meeting_date').attr('min', minDateTime);
        
        // Update resident info
        $('#meetingResidentInfo').html(`
            <strong><i class="fe fe-user"></i> Name:</strong> ${residentName}<br>
            <strong><i class="fe fe-heart"></i> Service:</strong> ${serviceType}
        `);
        
        $('#scheduleMeetingModal').modal('show');
    });

    // Handle reject form submission
    $('#rejectForm').on('submit', function(e) {
        e.preventDefault();
        
        const rejectionReason = $('#rejection_reason').val();
        
        fetch(`/admin/health-services/${currentRequestId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ rejection_reason: rejectionReason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#rejectModal').modal('hide');
                location.reload();
            } else {
                alert('Error rejecting request');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error rejecting request');
        });
    });

    // Handle schedule meeting form submission
    $('#scheduleMeetingForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const requestData = Object.fromEntries(formData.entries());
        requestData.health_service_request_id = currentRequestId;
        
        fetch('/admin/health-meetings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(requestData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#scheduleMeetingModal').modal('hide');
                alert('Meeting scheduled successfully!');
                // Reset form
                document.getElementById('scheduleMeetingForm').reset();
                location.reload();
            } else {
                let errorMessage = 'Please check the following errors:\n';
                if (data.errors) {
                    Object.values(data.errors).forEach(errors => {
                        errors.forEach(error => {
                            errorMessage += '• ' + error + '\n';
                        });
                    });
                } else {
                    errorMessage = data.message || 'An error occurred while scheduling the meeting.';
                }
                alert(errorMessage);
            }
        })        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while scheduling the meeting.');
        });
    });
});
</script>
@endpush
@endsection
