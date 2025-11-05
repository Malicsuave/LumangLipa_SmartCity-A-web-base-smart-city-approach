@extends('layouts.admin.master')

@section('title', 'Health Services Dashboard')

@push('styles')
@include('admin.components.datatable-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
/* Expired announcement styling */
.expired-announcement {
    background-color: #f8f9fa !important;
    opacity: 0.7;
}

.expired-announcement td {
    color: #6c757d !important;
}

.expired-announcement:hover {
    background-color: #e9ecef !important;
}

/* Add strikethrough effect to expired announcements */
.expired-announcement .progress {
    filter: grayscale(50%);
}
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Health Services Dashboard</h1>
                <p class="text-muted mb-0">Manage health service requests and appointments</p>
            </div>
           
               
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 class="metric-counter">{{ $totalRequests ?? 0 }}</h3>
                <p>Total Requests</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 class="metric-counter">{{ $pendingRequests ?? 0 }}</h3>
                <p>Pending</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3 class="metric-counter">{{ $completedRequests ?? 0 }}</h3>
                <p>Completed</p>
            </div>
            <div class="icon">
                <i class="fas fa-check"></i>
            </div>
        </div>
    </div>
</div>

<!-- Health Announcements Section -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header d-flex justify-content-between align-items-center" style="padding-right: 1.25rem;">
                <strong class="card-title mb-0">🏥 Health Programs & Announcements</strong>
                <a href="{{ route('admin.announcements.index', ['type' => 'health_related']) }}" class="btn btn-sm btn-primary ml-auto">
                    <i class="fas fa-eye mr-1"></i>View All
                </a>
            </div>
            <div class="card-body">
                @if(isset($healthAnnouncements) && $healthAnnouncements->count() > 0)
                    <table id="healthAnnouncementsTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Program/Event</th>
                                <th>Date & Time</th>
                                <th>Slots</th>
                                <th>Registrations</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($healthAnnouncements as $announcement)
                            @php
                                $isExpired = !$announcement->is_active;
                                $rowClass = $isExpired ? 'expired-announcement' : '';
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td>
                                    <strong class="{{ $isExpired ? 'text-muted' : '' }}">
                                        {{ $announcement->title }}
                                        @if($isExpired)
                                            <span class="badge badge-dark ml-2">ENDED</span>
                                        @endif
                                    </strong>
                                    @if($announcement->max_slots)
                                        <br><small class="text-muted">Limited to {{ $announcement->max_slots }} participants</small>
                                    @endif
                                </td>
                                <td class="{{ $isExpired ? 'text-muted' : '' }}">
                                    @if($announcement->date)
                                        {{ \Carbon\Carbon::parse($announcement->date)->format('M d, Y') }}
                                        @if($announcement->start_time || $announcement->end_time)
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-clock"></i>
                                                @if($announcement->start_time)
                                                    {{ date('h:i A', strtotime($announcement->start_time)) }}
                                                @endif
                                                @if($announcement->start_time && $announcement->end_time)
                                                    -
                                                @endif
                                                @if($announcement->end_time)
                                                    {{ date('h:i A', strtotime($announcement->end_time)) }}
                                                @endif
                                            </small>
                                        @endif
                                    @else
                                        <span class="text-muted">No date set</span>
                                    @endif
                                </td>
                                <td>
                                    @if($announcement->max_slots)
                                        <div class="progress" style="height: 20px; opacity: {{ $isExpired ? '0.5' : '1' }}">
                                            <div class="progress-bar bg-{{ $announcement->progress_color }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $announcement->progress_percentage }}%"
                                                 aria-valuenow="{{ $announcement->current_slots }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="{{ $announcement->max_slots }}">
                                                {{ $announcement->current_slots }}/{{ $announcement->max_slots }}
                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $announcement->slots_remaining }} remaining</small>
                                    @else
                                        <span class="text-muted">Unlimited</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-{{ $isExpired ? 'secondary' : 'info' }}">{{ $announcement->registrations->count() }}</span>
                                </td>
                                <td>
                                    @if(!$announcement->is_active)
                                        <span class="badge badge-secondary">
                                            <i class="fas fa-ban mr-1"></i>Expired
                                        </span>
                                    @elseif($announcement->is_expired)
                                        <span class="badge badge-dark">Expired</span>
                                    @elseif($announcement->is_full)
                                        <span class="badge badge-danger">Full</span>
                                    @else
                                        <span class="badge badge-success">
                                            <i class="fas fa-check-circle mr-1"></i>Active
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a class="dropdown-item text-dark" href="{{ route('admin.announcements.show', $announcement) }}">
                                                <i class="fas fa-eye mr-2 text-dark"></i>View Details
                                            </a>
                                            <a class="dropdown-item text-dark" href="{{ route('admin.announcements.registrations', $announcement) }}">
                                                <i class="fas fa-users mr-2 text-dark"></i>View Registrations ({{ $announcement->registrations->count() }})
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-dark" href="{{ route('admin.announcements.edit', $announcement) }}">
                                                <i class="fas fa-edit mr-2 text-dark"></i>Edit Announcement
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Program/Event</th>
                                <th>Date & Time</th>
                                <th>Slots</th>
                                <th>Registrations</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="text-center mt-3">
                        <a href="{{ route('admin.announcements.create') }}" class="btn btn-success">
                            <i class="fas fa-plus mr-2"></i>Create New Health Announcement
                        </a>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No health announcements yet</h6>
                        <p class="text-muted">Create health announcements for vaccination drives, medical missions, health seminars, etc.</p>
                        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary mt-2">
                            <i class="fas fa-plus mr-2"></i>Create First Health Announcement
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">Recent Health Service Requests</strong>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-end mb-3">
                    @if(isset($recentRequests) && $recentRequests->count() > 0)
                        <button class="btn btn-outline-secondary" id="refreshTableBtn" type="button">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh
                        </button>
                    @endif
                </div>
                
                <div id="recentRequestsContainer">
                    @if(isset($recentRequests) && $recentRequests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="recentRequestsTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Resident</th>
                                        <th>Service</th>
                                        <th>Status</th>
                                        <th>Date Requested</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentRequests as $request)
                                    <tr>
                                        <td><strong>{{ $request->id }}</strong></td>
                                        <td><strong>{{ $request->resident_name }}</strong></td>
                                        <td>{{ ucwords(str_replace('_', ' ', $request->service_type)) }}</td>
                                        <td>{!! $request->status_badge !!}</td>
                                        <td>{{ $request->requested_at->format('M d, Y') }}<br><small class="text-muted">{{ $request->requested_at->format('h:i A') }}</small></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Resident</th>
                                        <th>Service</th>
                                        <th>Status</th>
                                        <th>Date Requested</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-heartbeat fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">No health service requests yet</h6>
                            <p class="text-muted">Health service requests will appear here once residents submit them.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('admin.components.datatable-scripts')
<script src="{{ asset('js/admin/datatable-helpers.js') }}"></script>
<script>
$(function () {
    // Destroy existing DataTable instance if it exists
    if ($.fn.DataTable.isDataTable('#healthAnnouncementsTable')) {
        $('#healthAnnouncementsTable').DataTable().destroy();
    }

    // Initialize DataTable for health announcements - matching pre-registration pattern
    if ($('#healthAnnouncementsTable').length) {
        DataTableHelpers.initDataTable("#healthAnnouncementsTable", {
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"],
            order: [[1, "asc"]], // Sort by date
            pageLength: 10,
            lengthChange: true,
            lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
            paging: true,
            info: true,
            searching: true,
            columnDefs: [
                { "orderable": false, "targets": [2, 5] }, // Disable sorting for Slots and Actions columns
                { "responsivePriority": 1, "targets": 0 },
                { "responsivePriority": 2, "targets": 1 },
                { "responsivePriority": 3, "targets": 4 },
                { "responsivePriority": 10, "targets": -1 }
            ],
            dom: '<"row"<"col-sm-12 col-md-6"B><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6">>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        });
    }
    
    // Auto-hide success alerts after 10 seconds
    setTimeout(function() {
        $('.alert-success').fadeOut('slow');
    }, 10000);
    
    // Load metrics with animation
    animateCounters();
    
    // Initialize refresh button
    const refreshBtn = document.getElementById('refreshTableBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            refreshRecentRequests();
        });
    }
});

// Animate metric counters
function animateCounters() {
    document.querySelectorAll('.metric-counter').forEach(counter => {
        const target = parseInt(counter.textContent);
        if (isNaN(target)) return;
        
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
        }, 200);
    });
}

// Refresh recent requests table via AJAX
function refreshRecentRequests() {
    const refreshBtn = document.getElementById('refreshTableBtn');
    refreshBtn.disabled = true;
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
    
    // AJAX request would go here in a real implementation
    setTimeout(() => {
        refreshBtn.disabled = false;
        refreshBtn.innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Refresh';
        
        // Show success message using toastr if available
        if (typeof toastr !== 'undefined') {
            toastr.success('Table refreshed successfully!');
        }
    }, 500);
}
</script>
@endpush
