@extends('layouts.admin.master')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <h1 class="h3 mb-0 text-gray-800">Complaint Management</h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Manage Complaints</strong>
                <div class="float-right">
                    <span class="badge badge-info">{{ $complaints->total() }} Total Complaints</span>
                </div>
            </div>
            <div class="card-body">
                @if($complaints->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-borderless table-striped">
                            <thead>
                                <tr>                                    <th>ID</th>
                                    <th>Complainant</th>
                                    <th>Type</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Filed Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($complaints as $complaint)
                                <tr>
                                    <td>
                                        <span class="badge badge-light">#{{ $complaint->id }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm mr-2">
                                                <span class="avatar-title rounded-circle bg-warning text-dark">
                                                    {{ substr($complaint->complainant_name, 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <strong>{{ $complaint->complainant_name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $complaint->barangay_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary">{{ $complaint->formatted_complaint_type }}</span>
                                    </td>
                                    <td>
                                        <strong>{{ Str::limit($complaint->subject, 30) }}</strong>                                        <br>
                                        <small class="text-muted">{{ Str::limit($complaint->description, 50) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge {{ $complaint->status_badge }}">{{ ucfirst($complaint->status) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $complaint->filed_at->format('M d, Y') }}</span>
                                        <br>
                                        <small class="text-muted">{{ $complaint->filed_at->format('h:i A') }}</small>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm dropdown-toggle more-vertical" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="text-muted sr-only">Action</span>
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a class="dropdown-item view-complaint" href="#" data-id="{{ $complaint->id }}">
                                                    <i class="fe fe-eye fe-16 mr-2 text-primary"></i>View Details
                                                </a>
                                                @if($complaint->status === 'pending')
                                                    <a class="dropdown-item approve-complaint" href="#" data-id="{{ $complaint->id }}">
                                                        <i class="fe fe-check-circle fe-16 mr-2 text-success"></i>Approve
                                                    </a>
                                                    <a class="dropdown-item dismiss-complaint" href="#" data-id="{{ $complaint->id }}">
                                                        <i class="fe fe-x-circle fe-16 mr-2 text-danger"></i>Dismiss
                                                    </a>
                                                @elseif($complaint->status === 'approved')
                                                    <a class="dropdown-item schedule-meeting" href="#" 
                                                       data-id="{{ $complaint->id }}"
                                                       data-complainant="{{ $complaint->complainant_name }}"
                                                       data-type="{{ $complaint->formatted_complaint_type }}">
                                                        <i class="fe fe-calendar fe-16 mr-2 text-info"></i>Schedule Meeting
                                                    </a>
                                                    <a class="dropdown-item resolve-complaint" href="#" data-id="{{ $complaint->id }}">
                                                        <i class="fe fe-check fe-16 mr-2 text-success"></i>Mark Resolved
                                                    </a>
                                                @elseif($complaint->status === 'scheduled')
                                                    <a class="dropdown-item resolve-complaint" href="#" data-id="{{ $complaint->id }}">
                                                        <i class="fe fe-check fe-16 mr-2 text-success"></i>Mark Resolved
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
                            Showing {{ $complaints->firstItem() }} to {{ $complaints->lastItem() }} of {{ $complaints->total() }} complaints
                        </div>
                        {{ $complaints->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fe fe-flag fe-48 text-muted mb-3"></i>
                        <h5 class="text-muted">No complaints found</h5>
                        <p class="text-muted">Complaints will appear here once residents submit them.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- View Complaint Modal -->
<div class="modal fade" id="viewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Complaint Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="complaintDetails">
                    <!-- Details will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Dismiss Complaint Modal -->
<div class="modal fade" id="dismissModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="dismissForm">
                <div class="modal-header">
                    <h5 class="modal-title">Dismiss Complaint</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="dismissal_reason">Reason for dismissal:</label>
                        <textarea class="form-control" id="dismissal_reason" name="dismissal_reason" rows="4" required placeholder="Please provide a detailed reason for dismissing this complaint..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Dismiss Complaint</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Schedule Meeting Modal -->
<div class="modal fade" id="scheduleMeetingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="scheduleMeetingForm">
                <div class="modal-header">
                    <h5 class="modal-title">Schedule Complaint Meeting</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="meeting_title">Meeting Title:</label>
                                <input type="text" class="form-control" id="meeting_title" name="meeting_title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="meeting_date">Meeting Date & Time:</label>
                                <input type="datetime-local" class="form-control" id="meeting_date" name="meeting_date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="meeting_location">Location:</label>
                                <input type="text" class="form-control" id="meeting_location" name="meeting_location" required placeholder="e.g., Barangay Hall Conference Room">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="meeting_notes">Meeting Notes (Optional):</label>
                                <textarea class="form-control" id="meeting_notes" name="meeting_notes" rows="3" placeholder="Additional notes or agenda for the meeting..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Schedule Meeting</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentComplaintId = null;

$(document).ready(function() {
    // View Complaint Details
    $('.view-complaint').on('click', function(e) {
        e.preventDefault();
        const complaintId = $(this).data('id');
        
        fetch(`/admin/complaints/${complaintId}`)
            .then(response => response.json())
            .then(data => {
                const details = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fe fe-user"></i> Complainant Information</h6>
                            <p><strong>Name:</strong> ${data.complainant_name}</p>
                            <p><strong>Barangay ID:</strong> ${data.barangay_id}</p>
                            <p><strong>Contact:</strong> ${data.resident?.contact_number || 'N/A'}</p>
                        </div>                        <div class="col-md-6">
                            <h6><i class="fe fe-info"></i> Complaint Information</h6>
                            <p><strong>Type:</strong> ${data.formatted_complaint_type}</p>
                            <p><strong>Status:</strong> <span class="badge ${data.status_badge}">${data.status}</span></p>
                        </div>
                    </div>
                    <hr>
                    <h6><i class="fe fe-file-text"></i> Complaint Details</h6>
                    <p><strong>Subject:</strong> ${data.subject}</p>
                    <p><strong>Description:</strong></p>
                    <p class="bg-light p-3 rounded">${data.description}</p>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong><i class="fe fe-clock"></i> Filed:</strong><br>${new Date(data.filed_at).toLocaleString()}</p>
                        </div>
                        <div class="col-md-6">
                            ${data.approved_at ? `<p><strong><i class="fe fe-check"></i> Approved:</strong><br>${new Date(data.approved_at).toLocaleString()}</p>` : ''}
                            ${data.scheduled_at ? `<p><strong><i class="fe fe-calendar"></i> Scheduled:</strong><br>${new Date(data.scheduled_at).toLocaleString()}</p>` : ''}
                        </div>
                    </div>
                    ${data.dismissal_reason ? `<div class="alert alert-danger"><strong>Dismissal Reason:</strong> ${data.dismissal_reason}</div>` : ''}
                `;
                $('#complaintDetails').html(details);
                $('#viewModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading complaint details');
            });
    });

    // Approve Complaint
    $('.approve-complaint').on('click', function(e) {
        e.preventDefault();
        const complaintId = $(this).data('id');
        
        if (confirm('Are you sure you want to approve this complaint?')) {
            fetch(`/admin/complaints/${complaintId}/approve`, {
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
                    alert('Error approving complaint');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error approving complaint');
            });
        }
    });

    // Resolve Complaint
    $('.resolve-complaint').on('click', function(e) {
        e.preventDefault();
        const complaintId = $(this).data('id');
        
        if (confirm('Are you sure you want to mark this complaint as resolved?')) {
            fetch(`/admin/complaints/${complaintId}/resolve`, {
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
                    alert('Error resolving complaint');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error resolving complaint');
            });
        }
    });

    // Dismiss Complaint
    $('.dismiss-complaint').on('click', function(e) {
        e.preventDefault();
        currentComplaintId = $(this).data('id');
        $('#dismissModal').modal('show');
    });

    // Schedule Meeting
    $('.schedule-meeting').on('click', function(e) {
        e.preventDefault();
        currentComplaintId = $(this).data('id');
        const complainantName = $(this).data('complainant');
        const complaintType = $(this).data('type');
        
        // Pre-fill the meeting title
        $('#meeting_title').val(`${complaintType} - ${complainantName}`);
        
        // Set minimum date to current date
        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
        $('#meeting_date').attr('min', now.toISOString().slice(0, 16));
        
        $('#scheduleMeetingModal').modal('show');
    });

    // Handle dismiss form submission
    $('#dismissForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const dismissalData = Object.fromEntries(formData.entries());
        
        fetch(`/admin/complaints/${currentComplaintId}/dismiss`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(dismissalData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#dismissModal').modal('hide');
                location.reload();
            } else {
                alert('Error dismissing complaint');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error dismissing complaint');
        });
    });

    // Handle schedule meeting form submission
    $('#scheduleMeetingForm').on('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const meetingData = Object.fromEntries(formData.entries());
        meetingData.complaint_id = currentComplaintId;
        
        fetch('/admin/complaint-meetings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(meetingData)
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
                    alert(errorMessage);
                } else {
                    alert('Error scheduling meeting');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error scheduling meeting');
        });
    });
});
</script>
@endpush
