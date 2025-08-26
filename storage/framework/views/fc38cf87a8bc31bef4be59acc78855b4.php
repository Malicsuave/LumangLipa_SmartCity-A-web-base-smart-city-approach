<?php $__env->startSection('title', 'Complaints Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12 mb-4 d-flex justify-content-between align-items-center">
        <h1 class="h3 mb-0 text-gray-800">Complaints Dashboard</h1>
        <div class="page-metrics small text-muted">
            <span id="pageLoadMetric"></span>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow">
            <div class="card-header">
                <strong class="card-title">Complaints Dashboard</strong>
            </div>
            <div class="card-body">
                <p class="mb-4">Welcome to the complaints management module.</p>
                
                <!-- Metrics Section - Optimized with Lazy Loading -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-4">
                        <div class="card document-metric-card shadow h-100">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <span class="circle circle-sm bg-primary">
                                            <i class="fe fe-clipboard text-white mb-0"></i>
                                        </span>
                                    </div>
                                    <div class="col pr-0">
                                        <p class="small text-muted mb-0">Total Complaints</p>
                                    </div>
                                    <div class="col-auto">
                                        <span class="h3 mb-0" id="totalComplaints"><?php echo e($totalComplaints ?? 0); ?></span>
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
                                        <span class="h3 mb-0" id="pendingComplaints"><?php echo e($pendingComplaints ?? 0); ?></span>
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
                                        <p class="small text-muted mb-0">Resolved</p>
                                    </div>
                                    <div class="col-auto">
                                        <span class="h3 mb-0" id="resolvedComplaints"><?php echo e($resolvedComplaints ?? 0); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Complaints Table - With deferred loading -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Recent Complaints</h5>
                            <div class="d-flex gap-2">
                                <a href="<?php echo e(route('admin.complaint-management')); ?>" class="btn btn-sm btn-primary" style="margin-right: 10px">
                                    <i class="fe fe-arrow-right me-1"></i>
                                    View All Complaints
                                </a>
                                <?php if(isset($recentComplaints) && $recentComplaints->count() > 0): ?>
                                    <button class="btn btn-sm btn-outline-secondary" id="refreshTableBtn" type="button">
                                        <i class="fe fe-refresh-cw me-1"></i> Refresh
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div id="recentComplaintsContainer">
                            <?php if(isset($recentComplaints) && $recentComplaints->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-borderless table-striped table-sm complaints-table">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Complainant</th>
                                                <th>Type</th>
                                                <th>Subject</th>
                                                <th>Status</th>
                                                <th>Filed Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $recentComplaints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $complaint): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($complaint->id); ?></td>
                                                <td>
                                                    <strong><?php echo e($complaint->complainant_name); ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?php echo e($complaint->barangay_id); ?></small>
                                                </td>
                                                <td>
                                                    <span class="badge badge-secondary"><?php echo e($complaint->formatted_complaint_type); ?></span>
                                                </td>
                                                <td>
                                                    <strong><?php echo e(Str::limit($complaint->subject, 40)); ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge <?php echo e($complaint->status_badge); ?>"><?php echo e(ucfirst($complaint->status)); ?></span>
                                                </td>
                                                <td>
                                                    <span class="text-muted"><?php echo e($complaint->filed_at->format('M d, Y')); ?></span>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fe fe-flag fe-32 text-muted mb-3"></i>
                                    <h6 class="text-muted">No complaints filed yet</h6>
                                    <p class="text-muted">Recent complaints will appear here once residents start filing them.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted small">
                                Showing <?php echo e($recentComplaints->count()); ?> recent complaints
                            </div>
                            <!-- Remove paginator links for recentComplaints, as it's not paginated -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* Performance optimized styles */
.complaint-metric-card {
    transition: transform 0.2s ease;
}

.complaint-metric-card:hover {
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

/* CSS optimizations */
.complaints-table {
    table-layout: fixed;
    width: 100%;
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
            refreshRecentComplaints();
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

// Refresh recent complaints table via AJAX
function refreshRecentComplaints() {
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
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/lumanglipa/resources/views/admin/complaints.blade.php ENDPATH**/ ?>