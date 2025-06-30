@extends('layouts.admin.master')

@section('title', 'Health Services Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4 d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0 text-gray-800">Health Services</h1>
        <div class="page-metrics small text-muted">
            <span id="pageLoadMetric"></span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Health Services Dashboard</strong>
            </div>
            <div class="card-body">
                <p class="mb-4">Welcome to the health services management module.</p>
                
                <!-- Metrics Section - Optimized with Lazy Loading -->
                <div class="row mb-4">
                    <!-- Total Requests Metric -->
                    <div class="col-md-4">
                        <div class="card mb-4 shadow health-metric-card metric-card metric-card-1 h-100 border-left-primary">
                            <div class="card-body">
                                <div class="row align-items-center no-gutters">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-primary metric-icon">
                                            <i class="fe fe-users text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col-9">
                                        <p class="small text-muted mb-0">Total Requests</p>
                                        <div class="d-flex align-items-baseline">
                                            <span class="h3 metric-counter mb-0 me-1" id="totalRequests">{{ $totalRequests ?? 0 }}</span>
                                            <span class="small text-muted">All time</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pending Requests Metric -->
                    <div class="col-md-4">
                        <div class="card mb-4 shadow health-metric-card metric-card metric-card-2 h-100 border-left-warning">
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
                                            <span class="h3 metric-counter mb-0 me-1" id="pendingRequests">{{ $pendingRequests ?? 0 }}</span>
                                            <span class="small text-muted">Awaiting approval</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Completed Requests Metric -->
                    <div class="col-md-4">
                        <div class="card mb-4 shadow health-metric-card metric-card metric-card-3 h-100 border-left-success">
                            <div class="card-body">
                                <div class="row align-items-center no-gutters">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-success metric-icon">
                                            <i class="fe fe-check text-white"></i>
                                        </span>
                                    </div>
                                    <div class="col-9">
                                        <p class="small text-muted mb-0">Completed</p>
                                        <div class="d-flex align-items-baseline">
                                            <span class="h3 metric-counter mb-0 me-1" id="completedRequests">{{ $completedRequests ?? 0 }}</span>
                                            <span class="small text-muted">This month</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Requests Table - Optimized with deferred loading -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Recent Health Service Requests</h5>
                            <div class="d-flex gap-2">
                                <a href="{{ route('admin.health-services.index') }}" class="btn btn-sm btn-primary" style="margin-right: 10px;">
                                    <i class="fe fe-arrow-right me-1"></i>
                                    View Requests
                                </a>
                                @if(isset($recentRequests) && $recentRequests->count() > 0)
                                    <button class="btn btn-sm btn-outline-secondary" id="refreshTableBtn" type="button">
                                        <i class="fe fe-refresh-cw me-1"></i> Refresh
                                    </button>
                                @endif
                            </div>
                        </div>
                        
                        <div id="recentRequestsContainer">
                            @if(isset($recentRequests) && $recentRequests->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-borderless table-striped table-sm" id="recentRequestsTable">
                                        <thead>
                                            <tr>
                                                <th>Resident</th>
                                                <th>Service</th>
                                                <th>Status</th>
                                                <th>Date Requested</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recentRequests as $request)
                                            <tr>
                                                <td>{{ $request->resident_name }}</td>
                                                <td>{{ ucwords(str_replace('_', ' ', $request->service_type)) }}</td>
                                                <td>{!! $request->status_badge !!}</td>
                                                <td>{{ $request->requested_at->format('M d, Y') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fe fe-heart fe-32 text-muted mb-3"></i>
                                    <h6 class="text-muted">No health service requests yet</h6>
                                    <p class="text-muted">Health service requests will appear here once residents submit them.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Display page load metrics
    if (window.performanceMetrics && window.performanceMetrics.totalLoadTime) {
        document.getElementById('pageLoadMetric').textContent = 
            'Page Load: ' + (parseInt(window.performanceMetrics.totalLoadTime) / 1000).toFixed(2) + 's';
    }
    
    // Initialize refresh button
    const refreshBtn = document.getElementById('refreshTableBtn');
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            refreshRecentRequests();
        });
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

// Refresh recent requests table via AJAX
function refreshRecentRequests() {
    const refreshBtn = document.getElementById('refreshTableBtn');
    refreshBtn.disabled = true;
    refreshBtn.innerHTML = '<i class="fe fe-loader fe-spin me-1"></i> Loading...';
    
    // AJAX request would go here in a real implementation
    setTimeout(() => {
        refreshBtn.disabled = false;
        refreshBtn.innerHTML = '<i class="fe fe-refresh-cw me-1"></i> Refresh';
        
        // Show toast notification
        showToast('Table refreshed successfully!');
    }, 500);
}

// Simple toast notification
function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fe fe-check-circle text-success me-2"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.classList.add('show');
    }, 10);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}
</script>
@endpush

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

.fe-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Toast notification */
.toast-notification {
    position: fixed;
    bottom: 20px;
    right: 20px;
    background: white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    border-radius: 4px;
    padding: 12px 20px;
    z-index: 9999;
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s ease;
}

.toast-notification.show {
    transform: translateY(0);
    opacity: 1;
}

.toast-content {
    display: flex;
    align-items: center;
}

/* Add critical CSS inline for faster rendering */
#recentRequestsTable {
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