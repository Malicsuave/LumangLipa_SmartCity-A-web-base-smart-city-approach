<?php $__env->startSection('title', 'Health Services Dashboard'); ?>

<?php $__env->startSection('content'); ?>
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
                <!-- Metrics Section - Match Complaints Dashboard -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-4">
                        <div class="card document-metric-card shadow h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-primary">
                                            <i class="fe fe-users text-white mb-0"></i>
                                        </span>
                                    </div>
                                    <div class="col pr-0">
                                        <p class="small text-muted mb-0">Total Requests</p>
                                    </div>
                                    <div class="col-auto">
                                        <span class="h3 mb-0" id="totalRequests"><?php echo e($totalRequests ?? 0); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card document-metric-card shadow h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-warning">
                                            <i class="fe fe-clock text-white mb-0"></i>
                                        </span>
                                    </div>
                                    <div class="col pr-0">
                                        <p class="small text-muted mb-0">Pending</p>
                                    </div>
                                    <div class="col-auto">
                                        <span class="h3 mb-0" id="pendingRequests"><?php echo e($pendingRequests ?? 0); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card document-metric-card shadow h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-success">
                                            <i class="fe fe-check text-white mb-0"></i>
                                        </span>
                                    </div>
                                    <div class="col pr-0">
                                        <p class="small text-muted mb-0">Completed</p>
                                    </div>
                                    <div class="col-auto">
                                        <span class="h3 mb-0" id="completedRequests"><?php echo e($completedRequests ?? 0); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Metrics Section -->
                
                <!-- Recent Requests Table - Optimized with deferred loading -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Recent Health Service Requests</h5>
                            <div class="d-flex gap-2">
                                <a href="<?php echo e(route('admin.health-services.index')); ?>" class="btn btn-sm btn-primary" style="margin-right: 10px;">
                                    <i class="fe fe-arrow-right me-1"></i>
                                    View Requests
                                </a>
                                <?php if(isset($recentRequests) && $recentRequests->count() > 0): ?>
                                    <button class="btn btn-sm btn-outline-secondary" id="refreshTableBtn" type="button">
                                        <i class="fe fe-refresh-cw me-1"></i> Refresh
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div id="recentRequestsContainer">
                            <?php if(isset($recentRequests) && $recentRequests->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-borderless table-striped table-sm complaints-table">
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
                                            <?php $__currentLoopData = $recentRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($request->id); ?></td>
                                                <td><?php echo e($request->resident_name); ?></td>
                                                <td><?php echo e(ucwords(str_replace('_', ' ', $request->service_type))); ?></td>
                                                <td><?php echo $request->status_badge; ?></td>
                                                <td><?php echo e($request->requested_at->format('M d, Y')); ?></td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fe fe-heart fe-32 text-muted mb-3"></i>
                                    <h6 class="text-muted">No health service requests yet</h6>
                                    <p class="text-muted">Health service requests will appear here once residents submit them.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->startPush('styles'); ?>
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
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/lumanglipa/resources/views/admin/health.blade.php ENDPATH**/ ?>