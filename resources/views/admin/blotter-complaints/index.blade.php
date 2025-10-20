@extends('layouts.admin.master')

@section('page-title', 'Blotter/Complaint Management')

@push('scripts')
<script src="/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="/adminlte/plugins/jszip/jszip.min.js"></script>
<script src="/adminlte/plugins/pdfmake/pdfmake.min.js"></script>
<script src="/adminlte/plugins/pdfmake/vfs_fonts.js"></script>
<script src="/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="/adminlte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script>
$(document).ready(function() {
    if ($.fn.DataTable.isDataTable('#blotterComplaintsTable')) {
        $('#blotterComplaintsTable').DataTable().destroy();
    }
    $('#blotterComplaintsTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
        ],
        order: [[ 5, "desc" ]], // Sort by submitted date
        columnDefs: [
            { orderable: false, targets: -1 }
        ]
    });
});
</script>
@endpush


 </style>

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
<div class="card">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Blotter/Complaint Cases
        </h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="blotterComplaintsTable" class="table table-bordered table-striped table-hover">
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
                                    <a class="dropdown-item text-dark" href="#" onclick="viewComplaintDetails('{{ $complaint->id }}')">
                                        <i class="fas fa-eye mr-2 text-dark"></i>View Details
                                    </a>
                                    @if($complaint->status == 'pending')
                                    <a class="dropdown-item text-dark" href="#" onclick="acceptComplaint({{ $complaint->id }})">
                                        <i class="fas fa-check mr-2 text-dark"></i>Accept
                                    </a>
                                    <a class="dropdown-item text-dark" href="#" onclick="showRejectModal({{ $complaint->id }})">
                                        <i class="fas fa-times mr-2 text-dark"></i>Reject
                                    </a>
                                    @elseif($complaint->status == 'waiting_for_meeting')
                                    <a class="dropdown-item text-dark" href="#" onclick="scheduleMeeting({{ $complaint->id }})">
                                        <i class="fas fa-calendar mr-2 text-dark"></i>Schedule Meeting
                                    </a>
                                    @elseif($complaint->status == 'meeting_scheduled')
                                    <a class="dropdown-item text-dark" href="#" onclick="updateComplaintStatus({{ $complaint->id }}, 'resolved')">
                                        <i class="fas fa-check-circle mr-2 text-dark"></i>Mark Resolved
                                    </a>
                                    @endif
                                    @if(in_array($complaint->status, ['pending', 'waiting_for_meeting', 'rejected']))
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-dark" href="#" onclick="confirmDelete({{ $complaint->id }}, '{{ $complaint->case_number }}')">
                                        <i class="fas fa-trash mr-2 text-dark"></i>Delete
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
@endsection

