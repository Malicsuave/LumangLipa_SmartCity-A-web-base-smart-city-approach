@extends('layouts.admin.master')

@section('title', 'Health Services Management')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4 d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0 text-gray-800">Health Services Management</h1>
        <div class="page-metrics small text-muted">
            <span id="pageLoadMetric"></span>
        </div>
    </div>
</div>

<!-- Health Service Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card mb-4 shadow health-metric-card metric-card metric-card-1 h-100 border-left-warning">
            <div class="card-body">
                <div class="row align-items-center no-gutters">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-warning metric-icon">
                            <i class="fe fe-clock text-white"></i>
                        </span>
                    </div>
                    <div class="col-9">
                        <p class="small text-muted mb-0">Pending</p>
                        <div class="d-flex align-items-baseline">
                            <span class="h3 metric-counter mb-0 me-1">{{ $healthRequests->where('status', 'pending')->count() }}</span>
                            <span class="small text-muted">Awaiting review</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mb-4 shadow health-metric-card metric-card metric-card-2 h-100 border-left-success">
            <div class="card-body">
                <div class="row align-items-center no-gutters">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-success metric-icon">
                            <i class="fe fe-check text-white"></i>
                        </span>
                    </div>
                    <div class="col-9">
                        <p class="small text-muted mb-0">Approved</p>
                        <div class="d-flex align-items-baseline">
                            <span class="h3 metric-counter mb-0 me-1">{{ $healthRequests->where('status', 'approved')->count() }}</span>
                            <span class="small text-muted">Ready to serve</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mb-4 shadow health-metric-card metric-card metric-card-3 h-100 border-left-info">
            <div class="card-body">
                <div class="row align-items-center no-gutters">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-info metric-icon">
                            <i class="fe fe-calendar text-white"></i>
                        </span>
                    </div>
                    <div class="col-9">
                        <p class="small text-muted mb-0">Scheduled</p>
                        <div class="d-flex align-items-baseline">
                            <span class="h3 metric-counter mb-0 me-1">{{ $healthRequests->where('status', 'scheduled')->count() }}</span>
                            <span class="small text-muted">Appointments</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mb-4 shadow health-metric-card metric-card metric-card-4 h-100 border-left-danger">
            <div class="card-body">
                <div class="row align-items-center no-gutters">
                    <div class="col-3 text-center">
                        <span class="circle circle-sm bg-danger metric-icon">
                            <i class="fe fe-x text-white"></i>
                        </span>
                    </div>
                    <div class="col-9">
                        <p class="small text-muted mb-0">Rejected</p>
                        <div class="d-flex align-items-baseline">
                            <span class="h3 metric-counter mb-0 me-1">{{ $healthRequests->where('status', 'rejected')->count() }}</span>
                            <span class="small text-muted">Denied requests</span>
                        </div>
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
                <div id="healthRequestsContainer">
                            @if($healthRequests->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover" id="healthRequestsTable">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Resident</th>
                                                <th>Service</th>
                                                <th>Status</th>
                                                <th>Date Requested</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($healthRequests as $request)
                                            <tr>
                                                <td>{{ $request->resident_name }}</td>
                                                <td>{{ ucwords(str_replace('_', ' ', $request->service_type)) }}</td>
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
                                                <td>{{ $request->requested_at->format('M d, Y') }}</td>
                                                <td class="text-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm dropdown-toggle more-vertical" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <span class="text-muted sr-only">Action</span>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-right">
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
@endsection

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
                                <label for="meeting_date">Meeting Date</label>
                                <input type="date" class="form-control" id="meeting_date" name="meeting_date" required>
                                <small class="form-text text-muted">Select the meeting date</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="meeting_time">Meeting Time</label>
                                <input type="time" class="form-control" id="meeting_time" name="meeting_time" required>
                                <small class="form-text text-muted">Select time between 8:00 AM - 5:00 PM only</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Selected Date & Time</label>
                                <div class="form-control-plaintext" id="selectedDateTime">
                                    <span class="text-muted">Please select date and time above</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="meeting_location">Meeting Location</label>
                        <input type="text" class="form-control" id="meeting_location" name="meeting_location" value="Barangay Health Center" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="meeting_notes">Meeting Notes</label>
                        <textarea class="form-control" id="meeting_notes" name="meeting_notes" rows="3" placeholder="Additional notes for the meeting..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Schedule Meeting</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Performance optimized styles */
.health-metric-card {
    transition: transform 0.2s ease;
}

.health-metric-card:hover {
    transform: translateY(-5px);
}

.border-left-primary {
    border-left: 4px solid var(--primary) !important;
}

.border-left-success {
    border-left: 4px solid var(--success) !important;
}

.border-left-warning {
    border-left: 4px solid var(--warning) !important;
}

.border-left-info {
    border-left: 4px solid var(--info) !important;
}

.border-left-danger {
    border-left: 4px solid var(--danger) !important;
}

.circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Optimize icon rendering */
.fe {
    will-change: transform;
}

/* Add critical CSS inline for faster rendering */
.table {
    table-layout: fixed;
    width: 100%;
}

/* Optimize repaint operations */
.card {
    backface-visibility: hidden;
    will-change: transform;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Display page load metrics
    if (window.performanceMetrics && window.performanceMetrics.totalLoadTime) {
        document.getElementById('pageLoadMetric').textContent = 
            'Page Load: ' + (parseInt(window.performanceMetrics.totalLoadTime) / 1000).toFixed(2) + 's';
    }
    
    // Load metrics with animation
    animateCounters();
});

// Animate metric counters
function animateCounters() {
    document.querySelectorAll('.metric-counter').forEach(counter => {
        const target = parseInt(counter.textContent);
        const duration = 1000;
        const step = target / duration * 10;
        let current = 0;
        
        const animate = () => {
            current += step;
            if (current < target) {
                counter.textContent = Math.floor(current);
                setTimeout(animate, 10);
            } else {
                counter.textContent = target;
            }
        };
        
        setTimeout(() => {
            animate();
        }, 200); // Slight delay before animation starts
    });
}

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
        const minDate = now.toISOString().split('T')[0];
        $('#meeting_date').attr('min', minDate);
        
        // Update resident info
        $('#meetingResidentInfo').html(`
            <strong><i class="fe fe-user"></i> Name:</strong> ${residentName}<br>
            <strong><i class="fe fe-heart"></i> Service:</strong> ${serviceType}
        `);
        
        // Reset the selected datetime display
        updateSelectedDateTime();
        
        $('#scheduleMeetingModal').modal('show');
    });
    
    // Update selected date/time display when inputs change
    $('#meeting_date, #meeting_time').on('change', function() {
        updateSelectedDateTime();
    });
    
    // Function to update the selected date/time display
    function updateSelectedDateTime() {
        const dateValue = $('#meeting_date').val();
        const timeValue = $('#meeting_time').val();
        
        if (dateValue && timeValue) {
            // Create a proper date object to format it nicely
            const selectedDate = new Date(dateValue + 'T' + timeValue);
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            };
            const formattedDateTime = selectedDate.toLocaleDateString('en-US', options);
            
            $('#selectedDateTime').html(`
                <div class="alert alert-light mb-0">
                    <i class="fe fe-clock text-primary"></i>
                    <strong>Scheduled for:</strong> ${formattedDateTime}
                </div>
            `);
        } else {
            $('#selectedDateTime').html('<span class="text-muted">Please select date and time above</span>');
        }
    }
    
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
        
        // Combine date and time into a single datetime field
        const meetingDate = requestData.meeting_date;
        const meetingTime = requestData.meeting_time;
        
        if (meetingDate && meetingTime) {
            // Create datetime string in format YYYY-MM-DD HH:MM:SS
            requestData.meeting_date = meetingDate + ' ' + meetingTime + ':00';
        }
        
        // Remove the separate time field since we combined it
        delete requestData.meeting_time;
        
        requestData.health_service_request_id = currentRequestId;
        
        // Format the date and time for confirmation message
        const selectedDate = new Date(meetingDate + 'T' + meetingTime);
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        };
        const formattedDateTime = selectedDate.toLocaleDateString('en-US', options);
        
        // Show confirmation dialog
        const confirmMessage = `Are you sure you want to schedule this meeting?\n\n` +
                              `Title: ${requestData.meeting_title}\n` +
                              `Date & Time: ${formattedDateTime}\n` +
                              `Location: ${requestData.meeting_location}\n` +
                              `${requestData.meeting_notes ? 'Notes: ' + requestData.meeting_notes : ''}`;
        
        if (!confirm(confirmMessage)) {
            return; // User cancelled, don't proceed
        }
        
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
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while scheduling the meeting.');
        });
    });
});
</script>
@endpush
