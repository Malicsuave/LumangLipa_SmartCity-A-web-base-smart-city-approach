@extends('layouts.admin.master')

@section('title', 'Health Services Management')

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
.section-title {
    font-weight: 700;
    color: #22223b;
    font-size: 1.08rem;
    margin-bottom: 12px;
    margin-top: 8px;
    letter-spacing: 0.01em;
}
.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 7px 0;
    border-bottom: 1px solid #f3f4f6;
}
.info-label {
    color: #6366f1;
    font-weight: 600;
    min-width: 120px;
    flex: 0 0 45%;
    font-size: 1rem;
}
.info-value {
    color: #22223b;
    font-weight: 400;
    flex: 1;
    text-align: right;
    font-size: 1rem;
    word-break: break-word;
}
.purpose-box {
    background: #f3f4f6;
    border-radius: 8px;
    padding: 14px 16px;
    color: #374151;
    font-size: 1rem;
    margin-top: 4px;
    border: 1px solid #e5e7eb;
}
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
                <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Health Services Management</h1>
                <p class="text-muted mb-0">Review and manage health service requests</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">Health Service Requests</strong>
            </div>
            <div class="card-body">
                <!-- Search and Filter Form -->
                <form method="GET" action="{{ route('admin.health-services.index') }}" id="filterForm">
                <div class="row mb-4">
                    <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Search by resident name, barangay ID, or service type..." 
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
                            @if(request()->hasAny(['search', 'status', 'service_type', 'date_from', 'date_to']))
                                <a href="{{ route('admin.health-services.index') }}" class="btn btn-outline-secondary">
                                    <i class="fe fe-x fe-16 mr-1"></i>Clear All Filters
                                </a>
                            @endif
                    </div>
                    </div>

                    <!-- Collapsible Filter Section -->
                    <div class="collapse {{ request()->hasAny(['status', 'service_type', 'date_from', 'date_to']) ? 'show' : '' }}" id="filterSection">
                        <div class="card border-left-primary mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 text-primary">
                                    <i class="fe fe-filter fe-16 mr-2"></i>Filter Options
                                    <small class="text-muted ml-2">Filter health service requests by various criteria</small>
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
                                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Service Type</label>
                                        <select name="service_type" class="form-control form-control-sm">
                                            <option value="">All Services</option>
                                            <option value="medical_consultation" {{ request('service_type') == 'medical_consultation' ? 'selected' : '' }}>Medical Consultation</option>
                                            <option value="health_certificate" {{ request('service_type') == 'health_certificate' ? 'selected' : '' }}>Health Certificate</option>
                                            <option value="immunization" {{ request('service_type') == 'immunization' ? 'selected' : '' }}>Immunization</option>
                                            <option value="prenatal_care" {{ request('service_type') == 'prenatal_care' ? 'selected' : '' }}>Prenatal Care</option>
                                            <option value="family_planning" {{ request('service_type') == 'family_planning' ? 'selected' : '' }}>Family Planning</option>
                                            <option value="nutrition_counseling" {{ request('service_type') == 'nutrition_counseling' ? 'selected' : '' }}>Nutrition Counseling</option>
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
                @if(request()->hasAny(['search', 'status', 'service_type', 'date_from', 'date_to']))
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Active filters:</small>
                                <span class="badge badge-info ml-2">{{ $healthRequests->total() }} results found</span>
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
                            @if(request('service_type'))
                                <a href="{{ request()->fullUrlWithQuery(['service_type' => null]) }}" class="badge badge-success mr-1 text-decoration-none">
                                    Service: {{ ucwords(str_replace('_', ' ', request('service_type'))) }} <i class="fe fe-x"></i>
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
                    @if($healthRequests->count() > 0)
                        <div class="table-responsive">
                        <table class="table table-borderless table-striped table-hover">
                            <thead>
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
                                        @php
                                            $isLastTwo = $loop->remaining < 2;
                                            $dropdownItems = [];
                                            $dropdownItems[] = [
                                                'label' => 'View Details',
                                                'icon' => 'fe fe-eye fe-16 text-primary',
                                                'class' => '',
                                                'attrs' => "onclick=\"viewRequest({$request->id})\" @click=\"open = false\" href='#'",
                                            ];
                                            if ($request->status === 'pending') {
                                                $dropdownItems[] = [
                                                    'label' => 'Approve',
                                                    'icon' => 'fe fe-check fe-16 text-success',
                                                    'class' => '',
                                                    'attrs' => "onclick=\"approveRequest({$request->id})\" @click=\"open = false\" href='#'",
                                                ];
                                                $dropdownItems[] = [
                                                    'label' => 'Reject',
                                                    'icon' => 'fe fe-x fe-16 text-warning',
                                                    'class' => '',
                                                    'attrs' => "onclick=\"rejectRequest({$request->id})\" @click=\"open = false\" href='#'",
                                                ];
                                            }
                                            if ($request->status === 'approved') {
                                                $dropdownItems[] = [
                                                    'label' => 'Schedule Meeting',
                                                    'icon' => 'fe fe-calendar fe-16 text-info',
                                                    'class' => '',
                                                    'attrs' => "onclick=\"scheduleMeeting({$request->id})\" @click=\"open = false\" href='#'",
                                                ];
                                            }
                                            if ($request->status === 'scheduled') {
                                                $dropdownItems[] = [
                                                    'label' => 'Mark Complete',
                                                    'icon' => 'fe fe-check-circle fe-16 text-success',
                                                    'class' => '',
                                                    'attrs' => "onclick=\"markComplete({$request->id})\" @click=\"open = false\" href='#'",
                                                ];
                                            }
                                            $dropdownItems[] = ['divider' => true];
                                            $dropdownItems[] = [
                                                'label' => 'Delete',
                                                'icon' => 'fe fe-trash-2 fe-16 text-danger',
                                                'class' => '',
                                                'attrs' => "onclick=\"deleteRequest({$request->id})\" @click=\"open = false\" href='#'",
                                            ];
                                        @endphp
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
                                Showing {{ $healthRequests->firstItem() ?? 0 }} to {{ $healthRequests->lastItem() ?? 0 }} of {{ $healthRequests->total() }} health service requests
                            </div>
                            <nav aria-label="Table Paging" class="mb-0">
                                <ul class="pagination justify-content-end mb-0">
                                    @if($healthRequests->onFirstPage())
                                        <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="fe fe-arrow-left"></i> Previous</a></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $healthRequests->previousPageUrl() }}"><i class="fe fe-arrow-left"></i> Previous</a></li>
                                    @endif
                                    @for($i = 1; $i <= $healthRequests->lastPage(); $i++)
                                        <li class="page-item {{ $i == $healthRequests->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $healthRequests->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                    @if($healthRequests->hasMorePages())
                                        <li class="page-item"><a class="page-link" href="{{ $healthRequests->nextPageUrl() }}">Next <i class="fe fe-arrow-right"></i></a></li>
                                    @else
                                        <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next <i class="fe fe-arrow-right"></i></a></li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    @else
                <div class="text-center py-5" id="healthNoResults">
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
                    <h4>No health service requests found</h4>
                    <p class="text-muted">
                        @if(request()->hasAny(['search', 'status', 'service_type', 'date_from', 'date_to']))
                            No health service requests match your search criteria. <a href="{{ route('admin.health-services.index') }}">Clear all filters</a>
                        @else
                            No health service requests have been submitted yet.
                        @endif
                    </p>
                </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Health Service Request Details</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6 mb-3">
            <div class="section-title">Request Information</div>
            <div class="info-row"><span class="info-label">Request ID:</span> <span class="info-value" id="modal-request-id"></span></div>
            <div class="info-row"><span class="info-label">Service Type:</span> <span class="info-value" id="modal-service-type"></span></div>
            <div class="info-row"><span class="info-label">Status:</span> <span class="info-value" id="modal-status"></span></div>
            <div class="info-row"><span class="info-label">Date Requested:</span> <span class="info-value" id="modal-requested-date"></span></div>
          </div>
          <div class="col-md-6 mb-3">
            <div class="section-title">Resident Information</div>
            <div class="info-row"><span class="info-label">Name:</span> <span class="info-value" id="modal-resident-name"></span></div>
            <div class="info-row"><span class="info-label">Barangay ID:</span> <span class="info-value" id="modal-barangay-id"></span></div>
            <div class="info-row"><span class="info-label">Address:</span> <span class="info-value" id="modal-resident-address"></span></div>
          </div>
        </div>
        <div class="row mt-2">
          <div class="col-12">
            <div class="section-title">Purpose</div>
            <div id="modal-purpose" class="purpose-box"></div>
          </div>
        </div>
        <div class="info-row" id="admin-notes-row" style="display:none;">
            <span class="info-label">Admin Notes:</span>
            <span class="info-value" id="modal-admin-notes"></span>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- Mark Complete Modal -->
<div class="modal fade" id="markCompleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark Request as Complete</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to mark this health service request as <strong>complete</strong>?</p>
                <p class="text-info">
                    <i class="fe fe-info"></i> This action cannot be undone and will update the request status to <strong>Completed</strong>.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmMarkComplete">Mark as Complete</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function clearAllFilters() {
    document.getElementById('filterForm').reset();
    window.location.href = "{{ route('admin.health-services.index') }}";
}
function getStatusBadge(status) {
    switch (status) {
        case 'pending':
            return '<span class="badge badge-warning">Pending</span>';
        case 'approved':
            return '<span class="badge badge-success">Approved</span>';
        case 'scheduled':
            return '<span class="badge badge-info">Scheduled</span>';
        case 'completed':
            return '<span class="badge badge-primary">Completed</span>';
        case 'rejected':
            return '<span class="badge badge-danger">Rejected</span>';
        default:
            return '<span class="badge badge-secondary">' + status + '</span>';
    }
}
function viewRequest(id) {
    // Close any open dropdowns
    document.querySelectorAll('.table-dropdown-menu.show').forEach(function(menu) {
        menu.classList.remove('show');
    });
    fetch(`/admin/health-services/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modal-request-id').textContent = data.id;
            // Format service type: replace underscores with spaces and capitalize each word
            document.getElementById('modal-service-type').textContent = data.service_type
                ? data.service_type.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase())
                : '';
            document.getElementById('modal-status').innerHTML = getStatusBadge(data.status);
            document.getElementById('modal-requested-date').textContent = new Date(data.requested_at).toLocaleDateString();
            document.getElementById('modal-resident-name').textContent = data.resident ? 
                `${data.resident.first_name} ${data.resident.middle_name || ''} ${data.resident.last_name}`.trim() : 'Unknown';
            document.getElementById('modal-barangay-id').textContent = data.barangay_id;
            document.getElementById('modal-resident-address').textContent = data.resident ? data.resident.address : 'N/A';
            document.getElementById('modal-purpose').textContent = data.purpose;
            if (data.admin_notes) {
                document.getElementById('admin-notes-row').style.display = '';
                document.getElementById('modal-admin-notes').textContent = data.admin_notes;
            } else {
                document.getElementById('admin-notes-row').style.display = 'none';
            }
            $('#viewDetailsModal').modal('show');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading request details');
        });
}
function approveRequest(id) {
    // Implement approve modal logic here
    alert('Approve request ' + id);
}
function rejectRequest(id) {
    // Implement reject modal logic here
    alert('Reject request ' + id);
}
function scheduleMeeting(id) {
    // Implement schedule meeting modal logic here
    alert('Schedule meeting for request ' + id);
}
let currentMarkCompleteId = null;
function markComplete(id) {
    // Close any open dropdowns
    document.querySelectorAll('.table-dropdown-menu.show').forEach(function(menu) {
        menu.classList.remove('show');
    });
    currentMarkCompleteId = id;
    $('#markCompleteModal').modal('show');
}
document.addEventListener('DOMContentLoaded', function() {
    const confirmBtn = document.getElementById('confirmMarkComplete');
    if (confirmBtn) {
        confirmBtn.onclick = function() {
            if (!currentMarkCompleteId) return;
            fetch(`/admin/health-services/${currentMarkCompleteId}/complete`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#markCompleteModal').modal('hide');
                    alert('Request marked as complete!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Could not mark as complete.'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error marking request as complete.');
            });
        };
    }
});
function deleteRequest(id) {
    // Implement delete modal logic here
    alert('Delete request ' + id);
}

// Auto-scroll to table or no results after filtering/searching
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        if (window.location.search.length > 0) {
            var table = document.querySelector('.table-responsive');
            var noResults = document.getElementById('healthNoResults');
            if (table) {
                table.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else if (noResults) {
                noResults.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
})();
</script>
@endpush
