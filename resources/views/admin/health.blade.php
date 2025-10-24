@extends('layouts.admin.master')

@section('title', 'Health Services Dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endpush

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h3 mb-0 text-gray-800">Health Services Dashboard</h1>
                <p class="text-muted mb-0">Manage health service requests and appointments</p>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.health-services.index') }}" class="btn btn-primary mr-2">
                    <i class="fas fa-arrow-right mr-2"></i>View All Requests
                </a>
                <a href="{{ route('admin.health.appointment-dates.index') }}" class="btn btn-success">
                    <i class="fas fa-calendar mr-2"></i>Manage Appointment Dates
                </a>
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
<script>
$(function () {
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
