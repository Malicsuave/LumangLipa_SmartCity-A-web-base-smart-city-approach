@extends('layouts.admin.master')

@section('title', 'Complaint Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-common.css') }}">
<style>
.filter-btn-hover {
    transition: transform 0.2s ease-in-out;
}
.filter-btn-hover:hover {
    transform: scale(1.1);
    background-color: transparent !important;
    border-color: #6c757d !important;
    color: #6c757d !important;
}
.filter-btn-hover:focus {
    background-color: transparent !important;
    border-color: #6c757d !important;
    color: #6c757d !important;
    box-shadow: none !important;
}

/* Match action icon button hover/focus to other tables */
.btn-icon {
    border-radius: 50%;
    padding: 0.375rem;
    transition: background 0.15s, color 0.15s;
    background: transparent;
    border: none;
}
.btn-icon:focus, .btn-icon:active {
    outline: none !important;
    box-shadow: none !important;
    background: transparent !important;
}
.btn-icon:hover {
    background: #f0f1f3 !important;
    color: #4a90e2 !important;
}
.btn-icon i {
    transition: color 0.15s;
}
.btn-icon:hover i {
    color: #4a90e2 !important;
}
.table-responsive {
    overflow: visible !important;
}
.custom-dropdown-menu[style*="bottom: 100%"] {
    margin-bottom: 8px !important;
}
.table-responsive,
.card-body,
.collapse,
#filterSection {
    overflow: visible !important;
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
                <h1 class="h3 mb-0 text-gray-800">Complaint Management</h1>
                <p class="text-muted mb-0">Review and manage resident complaints</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">Complaints</strong>
            </div>
            <div class="card-body">
                <!-- Search and Filter Form -->
                <form method="GET" action="{{ route('admin.complaint-management') }}" id="filterForm">
                <div class="row mb-4">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" 
                                   placeholder="Search by complainant name, barangay ID, or subject..." 
                                   value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button class="btn btn-primary border-0" type="submit" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                    <i class="fe fe-search fe-16"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary border-0 filter-btn-hover" data-toggle="collapse" data-target="#filterSection" aria-expanded="false" title="Filter Options" style="border-left: 1px solid #dee2e6;">
                                    <i class="fe fe-filter fe-16"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        @if(request()->hasAny(['search', 'status', 'type', 'date_from', 'date_to']))
                            <a href="{{ route('admin.complaint-management') }}" class="btn btn-outline-secondary">
                                <i class="fe fe-x fe-16 mr-1"></i>Clear All Filters
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Collapsible Filter Section -->
                <div class="collapse {{ request()->hasAny(['status', 'type', 'date_from', 'date_to']) ? 'show' : '' }}" id="filterSection">
                    <div class="card border-left-primary mb-4">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 text-primary">
                                <i class="fe fe-filter fe-16 mr-2"></i>Filter Options
                                <small class="text-muted ml-2">Filter complaints by various criteria</small>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control form-control-sm">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                        <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="dismissed" {{ request('status') == 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Complaint Type</label>
                                    <select name="type" class="form-control form-control-sm">
                                        <option value="">All Types</option>
                                        <option value="noise" {{ request('type') == 'noise' ? 'selected' : '' }}>Noise Complaint</option>
                                        <option value="property" {{ request('type') == 'property' ? 'selected' : '' }}>Property Dispute</option>
                                        <option value="safety" {{ request('type') == 'safety' ? 'selected' : '' }}>Safety Concern</option>
                                        <option value="sanitation" {{ request('type') == 'sanitation' ? 'selected' : '' }}>Sanitation Issue</option>
                                        <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Date From</label>
                                    <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Date To</label>
                                    <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
                                </div>
                            </div>
                            <!-- Filter Actions -->
                            <div class="row mt-4">
                                <div class="col-md-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fe fe-filter fe-16 mr-1"></i>Apply Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
                <!-- Active Filters Display -->
                @if(request()->hasAny(['search', 'status', 'type', 'date_from', 'date_to']))
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Active filters:</small>
                                <span class="badge badge-info ml-2">{{ $complaints->total() }} results found</span>
                            </div>
                            <small class="text-muted">Click on any filter badge to remove it</small>
                        </div>
                        <div class="mt-2">
                            @if(request('search'))
                                <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}" class="badge badge-dark mr-1 text-decoration-none">
                                    Search: {{ request('search') }} <i class="fe fe-x"></i>
                                </a>
                            @endif
                            @if(request('status'))
                                <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="badge badge-primary mr-1 text-decoration-none">
                                    Status: {{ ucfirst(request('status')) }} <i class="fe fe-x"></i>
                                </a>
                            @endif
                            @if(request('type'))
                                <a href="{{ request()->fullUrlWithQuery(['type' => null]) }}" class="badge badge-success mr-1 text-decoration-none">
                                    Type: {{ ucwords(str_replace('_', ' ', request('type'))) }} <i class="fe fe-x"></i>
                                </a>
                            @endif
                            @if(request('date_from'))
                                <a href="{{ request()->fullUrlWithQuery(['date_from' => null]) }}" class="badge badge-info mr-1 text-decoration-none">
                                    Date From: {{ request('date_from') }} <i class="fe fe-x"></i>
                                </a>
                            @endif
                            @if(request('date_to'))
                                <a href="{{ request()->fullUrlWithQuery(['date_to' => null]) }}" class="badge badge-info mr-1 text-decoration-none">
                                    Date To: {{ request('date_to') }} <i class="fe fe-x"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                @if($complaints->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-borderless table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
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
                                    @php
                                        $isLastTwo = $loop->remaining < 2;
                                        $dropdownItems = [];
                                        $dropdownItems[] = [
                                            'label' => 'View Details',
                                            'icon' => 'fe fe-eye fe-16 text-primary',
                                            'class' => '',
                                            'attrs' => "onclick=\"viewComplaint({$complaint->id})\" href='#'",
                                        ];
                                        if ($complaint->status === 'pending') {
                                            $dropdownItems[] = [
                                                'label' => 'Approve',
                                                'icon' => 'fe fe-check fe-16 text-success',
                                                'class' => '',
                                                'attrs' => "onclick=\"approveComplaint({$complaint->id})\" href='#'",
                                            ];
                                            $dropdownItems[] = [
                                                'label' => 'Dismiss',
                                                'icon' => 'fe fe-x fe-16 text-warning',
                                                'class' => '',
                                                'attrs' => "onclick=\"dismissComplaint({$complaint->id})\" href='#'",
                                            ];
                                        }
                                        if ($complaint->status === 'approved') {
                                            $dropdownItems[] = [
                                                'label' => 'Schedule Meeting',
                                                'icon' => 'fe fe-calendar fe-16 text-info',
                                                'class' => '',
                                                'attrs' => "onclick=\"scheduleMeeting({$complaint->id})\" href='#'",
                                            ];
                                            $dropdownItems[] = [
                                                'label' => 'Mark Resolved',
                                                'icon' => 'fe fe-check-circle fe-16 text-success',
                                                'class' => '',
                                                'attrs' => "onclick=\"resolveComplaint({$complaint->id})\" href='#'",
                                            ];
                                        }
                                        if ($complaint->status === 'scheduled') {
                                            $dropdownItems[] = [
                                                'label' => 'Mark Resolved',
                                                'icon' => 'fe fe-check-circle fe-16 text-success',
                                                'class' => '',
                                                'attrs' => "onclick=\"resolveComplaint({$complaint->id})\" href='#'",
                                            ];
                                        }
                                        $dropdownItems[] = ['divider' => true];
                                        $dropdownItems[] = [
                                            'label' => 'Delete',
                                            'icon' => 'fe fe-trash-2 fe-16 text-danger',
                                            'class' => '',
                                            'attrs' => "onclick=\"deleteComplaint({$complaint->id})\" href='#'",
                                        ];
                                    @endphp
                                    <tr>
                                        <td>{{ $complaint->id }}</td>
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
                                            <strong>{{ Str::limit($complaint->subject, 30) }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($complaint->description, 50) }}</small>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match($complaint->status) {
                                                    'pending' => 'badge-warning',
                                                    'approved' => 'badge-success',
                                                    'scheduled' => 'badge-info',
                                                    'resolved' => 'badge-primary',
                                                    'dismissed' => 'badge-danger',
                                                    default => 'badge-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }} text-capitalize">{{ $complaint->status }}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $complaint->filed_at->format('M d, Y') }}</span>
                                            <br>
                                            <small class="text-muted">{{ $complaint->filed_at->format('h:i A') }}</small>
                                        </td>
                                        <td class="text-center table-actions-col">
                                            @include('components.custom-dropdown', ['items' => $dropdownItems, 'dropup' => $isLastTwo])
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted small">
                            Showing {{ $complaints->firstItem() }} to {{ $complaints->lastItem() }} of {{ $complaints->total() }} complaints
                        </div>
                        {{ $complaints->appends(request()->query())->links() }}
                    </div>
                @else
                <div class="text-center py-5" id="complaintsNoResults">
                    <div class="d-flex justify-content-center mb-3">
                        <span style="display:inline-block;width:120px;height:120px;border-radius:50%;background:#f3f4f6;border:4px solid #e5e7eb;display:flex;align-items:center;justify-content:center;">
                            <svg width="56" height="56" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="28" cy="28" r="28" fill="#e5e7eb"/>
                                <ellipse cx="28" cy="24" rx="10" ry="12" fill="#f3f4f6"/>
                                <circle cx="23" cy="22" r="2" fill="#bdbdbd"/>
                                <circle cx="33" cy="22" r="2" fill="#bdbdbd"/>
                                <rect x="26" y="28" width="4" height="2" rx="1" fill="#bdbdbd"/>
                            </svg>
                        </span>
                    </div>
                    <h4>No complaints found</h4>
                    <p class="text-muted">
                        @if(request()->has('search'))
                            No complaints match your search criteria. <a href="{{ route('admin.complaint-management') }}">Clear search</a> to see all complaints.
                        @else
                            Complaints will appear here once residents submit them.
                        @endif
                    </p>
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

function clearAllFilters() {
    document.getElementById('filterForm').reset();
    window.location.href = "{{ route('admin.complaint-management') }}";
}

function viewComplaint(id) {
    // Implement view modal logic here
    alert('View details for complaint ' + id);
}

function approveComplaint(id) {
    // Implement approve modal logic here
    alert('Approve complaint ' + id);
}

function dismissComplaint(id) {
    // Implement dismiss modal logic here
    alert('Dismiss complaint ' + id);
}

function scheduleMeeting(id) {
    // Implement schedule meeting modal logic here
    alert('Schedule meeting for complaint ' + id);
}

function resolveComplaint(id) {
    // Implement resolve logic here
    alert('Resolve complaint ' + id);
}

function deleteComplaint(id) {
    // Implement delete modal logic here
    alert('Delete complaint ' + id);
}

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
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fe fe-info"></i> Complaint Information</h6>
                            <p><strong>Type:</strong> ${data.formatted_complaint_type}</p>
                            <p><strong>Status:</strong> <span class="badge ${data.status_badge}">${data.status}</span></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <h6><i class="fe fe-file-text"></i> Complaint Details</h6>
                            <p><strong>Subject:</strong> ${data.subject}</p>
                            <p><strong>Description:</strong></p>
                            <p class="text-muted">${data.description}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fe fe-calendar"></i> Timeline</h6>
                            <p><strong>Filed:</strong> ${data.filed_at}</p>
                            ${data.resolved_at ? `<p><strong>Resolved:</strong> ${data.resolved_at}</p>` : ''}
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fe fe-map-pin"></i> Location</h6>
                            <p><strong>Address:</strong> ${data.address || 'N/A'}</p>
                        </div>
                    </div>
                `;
                
                $('#complaintDetails').html(details);
                $('#viewModal').modal('show');
            })
            .catch(error => {
                console.error('Error fetching complaint details:', error);
                alert('Error loading complaint details');
            });
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
        const complainant = $(this).data('complainant');
        const type = $(this).data('type');
        
        $('#meeting_title').val(`Meeting: ${type} - ${complainant}`);
        $('#scheduleMeetingModal').modal('show');
    });

    // Handle form submissions
    $('#dismissForm').on('submit', function(e) {
        e.preventDefault();
        const reason = $('#dismissal_reason').val();
        
        if (!reason.trim()) {
            alert('Please provide a reason for dismissal');
            return;
        }
        
        // Submit dismissal
        fetch(`/admin/complaints/${currentComplaintId}/dismiss`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({ dismissal_reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#dismissModal').modal('hide');
                location.reload();
            } else {
                alert('Error dismissing complaint: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error dismissing complaint');
        });
    });

    $('#scheduleMeetingForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        // Submit meeting schedule
        fetch('/admin/complaint-meetings', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                $('#scheduleMeetingModal').modal('hide');
                location.reload();
            } else {
                alert('Error scheduling meeting: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error scheduling meeting');
        });
    });
});
</script>
<script>
$(document).ready(function() {
    const urlParams = new URLSearchParams(window.location.search);
    const filterKeys = ['search','status','type','date_from','date_to'];
    let hasFilter = false;
    for (const key of filterKeys) {
        if (urlParams.get(key)) {
            hasFilter = true;
            break;
        }
    }
    if (hasFilter) {
        setTimeout(function() {
            const table = document.querySelector('.table-responsive table');
            if (table) {
                table.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                const noResults = document.getElementById('complaintsNoResults');
                if (noResults) {
                    noResults.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            }
        }, 400);
    }
});
</script>
@endpush

