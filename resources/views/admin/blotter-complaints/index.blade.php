@extends('layouts.admin.master')

@section('page-title', 'Blotter/Complaint Management')

@push('styles')
@include('admin.components.datatable-styles')
<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
<style>
/* Status badge colors matching the design */
.badge-pending { background-color: #ffc107 !important; color: #000 !important; }
.badge-under-investigation { background-color: #17a2b8 !important; color: #fff !important; }
.badge-resolved { background-color: #28a745 !important; color: #fff !important; }
.badge-dismissed { background-color: #dc3545 !important; color: #fff !important; }

/* Modal styling to match announcements */
.info-row {
    display: flex;
    align-items: flex-start;
}
.info-label {
    min-width: 100px;
    flex-shrink: 0;
}
.info-value {
    flex: 1;
    word-break: break-word;
}
.section-title {
    border-bottom: 2px solid #2A7BC4;
    padding-bottom: 4px;
    margin-bottom: 12px;
}
.content-box {
    background: #f8f9fa;
    border-radius: 8px;
    line-height: 1.6;
}
.dropdown-menu {
    z-index: 9999 !important;
}
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Blotter/Complaint Management</h1>
                <p class="text-muted mb-0">Review and manage blotter/complaint reports</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('blotter-complaint.request') }}" class="btn btn-outline-secondary" target="_blank">
                    <i class="fas fa-external-link-alt mr-2"></i>View Public Form
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-2 col-md-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['total'] }}</h3>
                <p>Total Cases</p>
            </div>
            <div class="icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stats['pending'] }}</h3>
                <p>Pending</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['waiting_for_meeting'] }}</h3>
                <p>Waiting for Meeting</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $stats['meeting_scheduled'] }}</h3>
                <p>Meeting Scheduled</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['rejected'] }}</h3>
                <p>Rejected</p>
            </div>
            <div class="icon">
                <i class="fas fa-thumbs-down"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['resolved'] }}</h3>
                <p>Resolved</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Table Card -->
<div class="card shadow-lg border-0 mb-4 admin-card-shadow">
    <div class="card-header">
        <strong class="card-title">
            <i class="fas fa-exclamation-triangle mr-2"></i>Blotter/Complaint Cases
            
        </strong>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="blotterComplaintsTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Case ID</th>
                        <th>Reporter</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Meeting Details</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Case ID</th>
                        <th>Reporter</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Meeting Details</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
                <tbody>
                    @forelse($blotterComplaints as $complaint)
                    <tr>
                        <td>
                            <strong>{{ $complaint->case_number }}</strong>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $complaint->resident ? $complaint->resident->first_name . ' ' . $complaint->resident->last_name : 'N/A' }}</strong>
                            </div>
                            <small class="text-muted">{{ $complaint->barangay_id }}</small>
                        </td>
                        <td>
                            <span class="badge badge-secondary">Complaint</span>
                        </td>
                        <td>
                            @php
                                $statusClass = 'secondary';
                                $statusLabel = '';
                                switch($complaint->status) {
                                    case 'pending': 
                                        $statusClass = 'warning'; 
                                        $statusLabel = 'Pending Review';
                                        break;
                                    case 'waiting_for_meeting': 
                                        $statusClass = 'info'; 
                                        $statusLabel = 'Waiting for Meeting';
                                        break;
                                    case 'meeting_scheduled': 
                                        $statusClass = 'primary'; 
                                        $statusLabel = 'Meeting Scheduled';
                                        break;
                                    case 'rejected': 
                                        $statusClass = 'danger'; 
                                        $statusLabel = 'Rejected';
                                        break;
                                    case 'resolved': 
                                        $statusClass = 'success'; 
                                        $statusLabel = 'Resolved';
                                        break;
                                    default:
                                        $statusLabel = ucwords(str_replace('_', ' ', $complaint->status));
                                }
                            @endphp
                            <span class="badge badge-{{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td>
                            @if($complaint->status === 'meeting_scheduled' && $complaint->meeting_date)
                                <div class="text-center">
                                    <div>
                                        <i class="fas fa-calendar text-primary"></i>
                                        <strong>{{ \Carbon\Carbon::parse($complaint->meeting_date)->format('M d, Y') }}</strong>
                                    </div>
                                    <div>
                                        <i class="fas fa-clock text-info"></i>
                                        <span>{{ \Carbon\Carbon::parse($complaint->meeting_time)->format('g:i A') }}</span>
                                    </div>
                                    @if($complaint->meeting_location)
                                        <div>
                                            <i class="fas fa-map-marker-alt text-success"></i>
                                            <small class="text-muted">{{ Str::limit($complaint->meeting_location, 20) }}</small>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted">
                                    <i class="fas fa-minus"></i> No meeting scheduled
                                </span>
                            @endif
                        </td>
                        <td>
                            {{ $complaint->created_at->format('M d, Y') }}
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Actions
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="#" onclick="viewComplaintDetails('{{ $complaint->id }}')">
                                        <i class="fas fa-eye mr-2"></i>View Details
                                    </a>
                                    @if($complaint->status == 'pending')
                                    <a class="dropdown-item text-success" href="#" onclick="acceptComplaint({{ $complaint->id }})">
                                        <i class="fas fa-check mr-2"></i>Accept
                                    </a>
                                    <a class="dropdown-item text-danger" href="#" onclick="showRejectModal({{ $complaint->id }})">
                                        <i class="fas fa-times mr-2"></i>Reject
                                    </a>
                                    @elseif($complaint->status == 'waiting_for_meeting')
                                    <a class="dropdown-item text-primary" href="#" onclick="scheduleMeeting({{ $complaint->id }})">
                                        <i class="fas fa-calendar mr-2"></i>Schedule Meeting
                                    </a>
                                    @elseif($complaint->status == 'meeting_scheduled')
                                    <a class="dropdown-item text-success" href="#" onclick="updateComplaintStatus({{ $complaint->id }}, 'resolved')">
                                        <i class="fas fa-check-circle mr-2"></i>Mark Resolved
                                    </a>
                                    @endif
                                    @if(in_array($complaint->status, ['pending', 'waiting_for_meeting', 'rejected']))
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="#" onclick="confirmDelete({{ $complaint->id }}, '{{ $complaint->case_number }}')">
                                        <i class="fas fa-trash mr-2"></i>Delete
                                    </a>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No blotter/complaint cases found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- View Complaint Details Modal -->
<div class="modal fade" id="viewComplaintModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Blotter/Complaint Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Case Information -->
                            <div class="info-row mb-3">
                                <span class="info-label" style="font-weight: 600; color: #666;">Case Number:</span>
                                <span class="info-value ml-2" id="modal-case-number"></span>
                            </div>
                            <div class="info-row mb-3">
                                <span class="info-label" style="font-weight: 600; color: #666;">Status:</span>
                                <span class="info-value ml-2" id="modal-status"></span>
                            </div>
                            <div class="info-row mb-3">
                                <span class="info-label" style="font-weight: 600; color: #666;">Filed Date:</span>
                                <span class="info-value ml-2" id="modal-filed-date"></span>
                            </div>
                            
                            <!-- Complainants & Respondents -->
                            <div class="section-title">
                                <h6 class="mb-2" style="color: #2A7BC4;">Parties Involved</h6>
                            </div>
                            <div class="info-row mb-2">
                                <span class="info-label" style="font-weight: 600; color: #666;">Complainants:</span>
                            </div>
                            <div class="content-box p-3 mb-3" style="background: #f8f9fa; border-radius: 8px;" id="modal-complainants"></div>
                            
                            <div class="info-row mb-2">
                                <span class="info-label" style="font-weight: 600; color: #666;">Respondents:</span>
                            </div>
                            <div class="content-box p-3 mb-3" style="background: #f8f9fa; border-radius: 8px;" id="modal-respondents"></div>
                            
                            <!-- Complaint Details -->
                            <div class="section-title">
                                <h6 class="mb-2" style="color: #2A7BC4;">Complaint Details</h6>
                            </div>
                            <div class="content-box p-3 mb-3" style="background: #f8f9fa; border-radius: 8px; line-height: 1.6;" id="modal-details"></div>
                            
                            <!-- Resolution Sought -->
                            <div class="section-title">
                                <h6 class="mb-2" style="color: #2A7BC4;">Resolution Sought</h6>
                            </div>
                            <div class="content-box p-3 mb-3" style="background: #f8f9fa; border-radius: 8px; line-height: 1.6;" id="modal-resolution"></div>
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Reporter Information -->
                            <div class="section-title">
                                <h6 class="mb-2" style="color: #2A7BC4;">Reporter Information</h6>
                            </div>
                            <div class="info-row mb-2">
                                <span class="info-label" style="font-weight: 600; color: #666;">Name:</span>
                                <span class="info-value ml-2" id="modal-reporter-name"></span>
                            </div>
                            <div class="info-row mb-2">
                                <span class="info-label" style="font-weight: 600; color: #666;">Barangay ID:</span>
                                <span class="info-value ml-2" id="modal-barangay-id"></span>
                            </div>
                            <div class="info-row mb-2">
                                <span class="info-label" style="font-weight: 600; color: #666;">Contact:</span>
                                <span class="info-value ml-2" id="modal-contact"></span>
                            </div>
                            <div class="info-row mb-3">
                                <span class="info-label" style="font-weight: 600; color: #666;">Address:</span>
                                <span class="info-value ml-2" id="modal-address"></span>
                            </div>
                            
                            <!-- Case Information -->
                            <div class="section-title">
                                <h6 class="mb-2" style="color: #2A7BC4;">Case Information</h6>
                            </div>
                            <div class="info-row mb-2">
                                <span class="info-label" style="font-weight: 600; color: #666;">Verification:</span>
                                <span class="info-value ml-2" id="modal-verification"></span>
                            </div>
                            <div class="info-row mb-2">
                                <span class="info-label" style="font-weight: 600; color: #666;">Last Updated:</span>
                                <span class="info-value ml-2" id="modal-updated"></span>
                            </div>
                            
                            <!-- Meeting Details (only show if meeting is scheduled) -->
                            <div id="meeting-details-section" style="display: none;">
                                <div class="section-title mt-3">
                                    <h6 class="mb-2" style="color: #2A7BC4;">Meeting Details</h6>
                                </div>
                                <div class="info-row mb-2">
                                    <span class="info-label" style="font-weight: 600; color: #666;">📅 Date:</span>
                                    <span class="info-value ml-2" id="modal-meeting-date"></span>
                                </div>
                                <div class="info-row mb-2">
                                    <span class="info-label" style="font-weight: 600; color: #666;">🕒 Time:</span>
                                    <span class="info-value ml-2" id="modal-meeting-time"></span>
                                </div>
                                <div class="info-row mb-2">
                                    <span class="info-label" style="font-weight: 600; color: #666;">📍 Location:</span>
                                    <span class="info-value ml-2" id="modal-meeting-location"></span>
                                </div>
                                <div class="info-row mb-2" id="modal-meeting-notes-row" style="display: none;">
                                    <span class="info-label" style="font-weight: 600; color: #666;">📝 Notes:</span>
                                </div>
                                <div class="content-box p-3 mb-3" style="background: #f8f9fa; border-radius: 8px; line-height: 1.6; display: none;" id="modal-meeting-notes"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <div id="modal-actions">
                    <!-- Actions will be populated by JavaScript based on status -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Meeting Modal -->
<div class="modal fade" id="scheduleMeetingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Meeting</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="scheduleMeetingForm">
                    <input type="hidden" id="meeting-complaint-id">
                    <div class="form-group">
                        <label>Case Number</label>
                        <input type="text" id="meeting-case-number" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Meeting Date</label>
                        <input type="date" id="meeting-date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Meeting Time</label>
                        <input type="time" id="meeting-time" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Meeting Location</label>
                        <input type="text" id="meeting-location" class="form-control" placeholder="e.g. Barangay Hall Conference Room" required>
                    </div>
                    <div class="form-group">
                        <label>Meeting Purpose/Notes</label>
                        <textarea id="meeting-notes" class="form-control" rows="3" placeholder="Brief description of the meeting purpose"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveMeeting()">
                    <i class="fas fa-calendar-check"></i> Schedule Meeting
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete case <strong id="deleteCaseNumber"></strong>?</p>
                <p class="text-danger"><small>This action cannot be undone.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Accept Complaint Modal -->
<div class="modal fade" id="acceptComplaintModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Blotter/Complaint</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Are you sure you want to approve this complaint?</label>
                    <small class="text-muted d-block mb-2">This will notify the complainant via email.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmAcceptBtn">
                    <i class="fas fa-check"></i> Approve Complaint
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toastr JS
if (typeof toastr === 'undefined') {
    var script = document.createElement('script');
    script.src = 'https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js';
    document.head.appendChild(script);
}

// Toastr notification helpers
function showSuccess(message) {
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "4000"
    };
    toastr.success(message);
}
function showError(message) {
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "6000"
    };
    toastr.error(message);
}
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#blotterComplaintsTable')) {
        $('#blotterComplaintsTable').DataTable().destroy();
    }
    $('#blotterComplaintsTable').DataTable({
        dom: '<"row mb-2"<"col-md-6"B><"col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        buttons: [
            {
                extend: 'copy',
                text: 'Copy',
                className: 'btn btn-secondary btn-sm'
            },
            {
                extend: 'csv',
                text: 'CSV',
                className: 'btn btn-secondary btn-sm'
            },
            {
                extend: 'excel',
                text: 'Excel',
                className: 'btn btn-secondary btn-sm'
            },
            {
                extend: 'pdf',
                text: 'PDF',
                className: 'btn btn-secondary btn-sm'
            },
            {
                extend: 'print',
                text: 'Print',
                className: 'btn btn-secondary btn-sm'
            },
            {
                extend: 'colvis',
                text: 'Columns',
                className: 'btn btn-secondary btn-sm'
            }
        ],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        order: [[ 5, "desc" ]], // Sort by submitted date
        columnDefs: [
            { orderable: false, targets: -1 }
        ],
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            infoFiltered: "(filtered from _MAX_ total entries)",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            },
            emptyTable: "No blotter/complaint cases available"
        }
    });
});

function viewComplaintDetails(complaintId) {
    // Reset modal content
    $('#modal-case-number').text('Loading...');
    $('#modal-status').text('Loading...');
    $('#modal-filed-date').text('Loading...');
    $('#modal-complainants').text('Loading...');
    $('#modal-respondents').text('Loading...');
    $('#modal-details').text('Loading...');
    $('#modal-resolution').text('Loading...');
    $('#modal-reporter-name').text('Loading...');
    $('#modal-barangay-id').text('Loading...');
    $('#modal-contact').text('Loading...');
    $('#modal-address').text('Loading...');
    $('#modal-verification').text('Loading...');
    $('#modal-updated').text('Loading...');
    
    // Reset meeting details
    $('#meeting-details-section').hide();
    $('#modal-meeting-date').text('');
    $('#modal-meeting-time').text('');
    $('#modal-meeting-location').text('');
    $('#modal-meeting-notes').text('');
    $('#modal-meeting-notes-row').hide();
    $('#modal-meeting-notes').hide();
    
    // Open modal first
    $('#viewComplaintModal').modal('show');
    
    // Fetch data via AJAX
    $.ajax({
        url: '/admin/blotter-complaints/' + complaintId,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            // Populate modal with data
            $('#modal-case-number').text(response.case_number);
            $('#modal-status').html(getComplaintStatusBadge(response.status));
            $('#modal-filed-date').text(formatDate(response.created_at));
            $('#modal-complainants').html(response.complainants.replace(/\n/g, '<br>'));
            $('#modal-respondents').html(response.respondents.replace(/\n/g, '<br>'));
            $('#modal-details').html(response.complaint_details.replace(/\n/g, '<br>'));
            $('#modal-resolution').html(response.resolution_sought.replace(/\n/g, '<br>'));
            $('#modal-verification').text(response.verification_method.charAt(0).toUpperCase() + response.verification_method.slice(1));
            $('#modal-updated').text(formatDate(response.updated_at));
            
            // Reporter information
            if (response.resident) {
                $('#modal-reporter-name').text(response.resident.first_name + ' ' + response.resident.last_name);
                $('#modal-contact').text(response.resident.contact_number || 'N/A');
                $('#modal-address').text(response.resident.address || 'N/A');
            } else {
                $('#modal-reporter-name').text('N/A');
                $('#modal-contact').text('N/A');
                $('#modal-address').text('N/A');
            }
            $('#modal-barangay-id').text(response.barangay_id);
            
            // Show meeting details if meeting is scheduled
            if (response.status === 'meeting_scheduled' && response.meeting_date) {
                $('#modal-meeting-date').text(formatDate(response.meeting_date));
                $('#modal-meeting-time').text(formatTime(response.meeting_time));
                $('#modal-meeting-location').text(response.meeting_location || 'N/A');
                
                if (response.meeting_notes) {
                    $('#modal-meeting-notes-row').show();
                    $('#modal-meeting-notes').html(response.meeting_notes.replace(/\n/g, '<br>')).show();
                } else {
                    $('#modal-meeting-notes-row').hide();
                    $('#modal-meeting-notes').hide();
                }
                
                $('#meeting-details-section').show();
            } else {
                $('#meeting-details-section').hide();
            }
            
            // Update action buttons based on status
            updateModalActions(complaintId, response.status);
        },
        error: function() {
            alert('Failed to load complaint details. Please try again.');
            $('#viewComplaintModal').modal('hide');
        }
    });
}

function getComplaintStatusBadge(status) {
    const statusClasses = {
        'pending': 'warning',
        'waiting_for_meeting': 'info',
        'meeting_scheduled': 'primary',
        'rejected': 'danger',
        'resolved': 'success'
    };
    const statusLabels = {
        'pending': 'Pending Review',
        'waiting_for_meeting': 'Waiting for Meeting',
        'meeting_scheduled': 'Meeting Scheduled',
        'rejected': 'Rejected',
        'resolved': 'Resolved'
    };
    
    const badgeClass = statusClasses[status] || 'secondary';
    const label = statusLabels[status] || status;
    
    return `<span class="badge badge-${badgeClass}">${label}</span>`;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function formatTime(timeString) {
    if (!timeString) return 'N/A';
    
    // Parse time string (HH:MM:SS format)
    const [hours, minutes] = timeString.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    
    return `${displayHour}:${minutes} ${ampm}`;
}

function updateModalActions(complaintId, status) {
    let actionsHtml = '';
    
    if (status === 'pending') {
        actionsHtml = `
            <button type="button" class="btn btn-success mr-2" onclick="acceptComplaint(${complaintId})">
                <i class="fas fa-check"></i> Accept
            </button>
            <button type="button" class="btn btn-danger" onclick="showRejectModal(${complaintId})">
                <i class="fas fa-times"></i> Reject
            </button>
        `;
    } else if (status === 'waiting_for_meeting') {
        actionsHtml = `
            <button type="button" class="btn btn-primary" onclick="scheduleMeeting(${complaintId})">
                <i class="fas fa-calendar"></i> Schedule Meeting
            </button>
        `;
    } else if (status === 'meeting_scheduled') {
        actionsHtml = `
            <button type="button" class="btn btn-success" onclick="updateComplaintStatus(${complaintId}, 'resolved')">
                <i class="fas fa-check-circle"></i> Mark Resolved
            </button>
        `;
    } else if (status === 'rejected') {
        actionsHtml = `<span class="text-muted">This complaint has been rejected</span>`;
    } else if (status === 'resolved') {
        actionsHtml = `<span class="text-muted">This complaint has been resolved</span>`;
    }
    
    $('#modal-actions').html(actionsHtml);
}

function updateComplaintStatus(complaintId, newStatus) {
    if (!confirm('Are you sure you want to update the status of this complaint?')) {
        return;
    }
    
    $.ajax({
        url: `/admin/blotter-complaints/${complaintId}/update-status`,
        method: 'POST',
        data: {
            status: newStatus,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert('Status updated successfully!');
                location.reload(); // Refresh the page to show updated data
            } else {
                alert('Failed to update status: ' + response.message);
            }
        },
        error: function() {
            alert('Failed to update status. Please try again.');
        }
    });
}

function scheduleMeeting(complaintId) {
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('meeting-date').min = today;
    
    // Get complaint details for the modal
    $.ajax({
        url: `/admin/blotter-complaints/${complaintId}`,
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            document.getElementById('meeting-complaint-id').value = complaintId;
            document.getElementById('meeting-case-number').value = response.case_number;
            $('#scheduleMeetingModal').modal('show');
        },
        error: function() {
            alert('Failed to load complaint details.');
        }
    });
}

function saveMeeting() {
    const complaintId = document.getElementById('meeting-complaint-id').value;
    const meetingDate = document.getElementById('meeting-date').value;
    const meetingTime = document.getElementById('meeting-time').value;
    const meetingLocation = document.getElementById('meeting-location').value;
    const meetingNotes = document.getElementById('meeting-notes').value;
    
    if (!meetingDate || !meetingTime || !meetingLocation) {
        alert('Please fill in all required fields.');
        return;
    }
    
    $.ajax({
        url: `/admin/blotter-complaints/${complaintId}/schedule-meeting`,
        method: 'POST',
        data: {
            meeting_date: meetingDate,
            meeting_time: meetingTime,
            meeting_location: meetingLocation,
            meeting_notes: meetingNotes,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                alert('Meeting scheduled successfully!');
                $('#scheduleMeetingModal').modal('hide');
                location.reload();
            } else {
                alert('Failed to schedule meeting: ' + response.message);
            }
        },
        error: function() {
            alert('Failed to schedule meeting. Please try again.');
        }
    });
}

function acceptComplaint(complaintId) {
    // Open the custom modal and store complaint ID
    $('#acceptComplaintModal').data('complaint-id', complaintId);
    $('#acceptComplaintModal').modal('show');
    }
    // Approve Complaint modal button handler
    $('#confirmAcceptBtn').off('click').on('click', function() {
        var complaintId = $('#acceptComplaintModal').data('complaint-id');
        if (!complaintId) return;
        $(this).prop('disabled', true);
        $.ajax({
            url: `/admin/blotter-complaints/${complaintId}/accept`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    showSuccess(response.message);
                    $('#acceptComplaintModal').modal('hide');
                    $('#viewComplaintModal').modal('hide');
                    location.reload();
                } else {
                    showError(response.message);
                }
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.message || 'Failed to accept complaint. Please try again.';
                showError(message);
            },
            complete: function() {
                $('#confirmAcceptBtn').prop('disabled', false);
            }
        });
    });

function showRejectModal(complaintId) {
    // Create reject modal if it doesn't exist
    if (!$('#rejectComplaintModal').length) {
        const rejectModalHtml = `
            <div class="modal fade" id="rejectComplaintModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Reject Blotter/Complaint</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="rejection-reason">Reason for Rejection <span class="text-danger">*</span></label>
                                <textarea id="rejection-reason" class="form-control" rows="4" 
                                    placeholder="Please provide a detailed reason for rejecting this complaint..." required></textarea>
                                <small class="text-muted">This reason will be sent to the complainant via email.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" onclick="confirmRejectComplaint()">
                                <i class="fas fa-times"></i> Reject Complaint
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        $('body').append(rejectModalHtml);
    }
    
    // Store complaint ID and clear previous reason
    $('#rejectComplaintModal').data('complaint-id', complaintId);
    $('#rejection-reason').val('');
    $('#rejectComplaintModal').modal('show');
}

function confirmRejectComplaint() {
    const complaintId = $('#rejectComplaintModal').data('complaint-id');
    const rejectionReason = $('#rejection-reason').val().trim();
    
    if (!rejectionReason) {
        showError('Please provide a reason for rejection.');
        return;
    }
    
    // Only use the custom modal, do not show browser confirm dialog
    $.ajax({
        url: `/admin/blotter-complaints/${complaintId}/reject`,
        method: 'POST',
        data: {
            rejection_reason: rejectionReason,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                showSuccess(response.message);
                $('#rejectComplaintModal').modal('hide');
                $('#viewComplaintModal').modal('hide');
                location.reload();
            } else {
                showError(response.message);
            }
        },
        error: function(xhr) {
            const message = xhr.responseJSON?.message || 'Failed to reject complaint. Please try again.';
            showError(message);
        }
    });
}

function confirmDelete(id, caseNumber) {
    document.getElementById('deleteCaseNumber').textContent = caseNumber;
    document.getElementById('deleteForm').action = `/admin/blotter-complaints/${id}`;
    $('#deleteModal').modal('show');
}

// Auto-hide alerts after 5 seconds
setTimeout(function() {
    $('.alert').fadeOut();
}, 5000);
</script>
@endpush
