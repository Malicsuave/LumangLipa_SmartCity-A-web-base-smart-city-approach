<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row mb-2 align-items-center">
                <div class="col">
                    <h2 class="h5 page-title">Analytics Dashboard</h2>
                </div>
            </div>
            
            <!-- Summary Cards -->
            <div class="row my-4">
                <div class="col-md-3">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fe fe-users fa-2x text-gray-300 mr-3"></i>
                                    <div>
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Residents
                                        </div>
                                    </div>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($totalResidents)); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fe fe-user-plus fa-2x text-gray-300 mr-3"></i>
                                    <div>
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            New This Month
                                        </div>
                                    </div>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($newResidentsThisMonth)); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fe fe-clock fa-2x text-gray-300 mr-3"></i>
                                    <div>
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pending Registrations
                                        </div>
                                    </div>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($pendingPreRegistrations)); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i class="fe fe-file-text fa-2x text-gray-300 mr-3"></i>
                                    <div>
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Document Requests
                                        </div>
                                    </div>
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($totalDocumentRequests)); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Charts Row -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Gender Distribution</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Age Groups</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="ageChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Monthly Trends -->
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Monthly Registration Trends</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="trendsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Residents</h6>
                        </div>
                        <div class="card-body">
                            <?php $__currentLoopData = $recentResidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold"><?php echo e($resident->first_name); ?> <?php echo e($resident->last_name); ?></div>
                                    <small class="text-muted"><?php echo e($resident->created_at->format('M d, Y')); ?></small>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Documents</h6>
                        </div>
                        <div class="card-body">
                            <?php $__currentLoopData = $recentDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold"><?php echo e($document->document_type); ?></div>
                                    <small class="text-muted"><?php echo e($document->created_at->format('M d, Y')); ?></small>
                                </div>
                                <span class="badge badge-<?php echo e($document->status == 'approved' ? 'success' : ($document->status == 'pending' ? 'warning' : 'danger')); ?>">
                                    <?php echo e(ucfirst($document->status)); ?>

                                </span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Complaints</h6>
                        </div>
                        <div class="card-body">
                            <?php $__currentLoopData = $recentComplaints; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $complaint): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold"><?php echo e($complaint->formatted_complaint_type); ?></div>
                                    <small class="text-muted"><?php echo e($complaint->created_at->format('M d, Y')); ?></small>
                                </div>
                                <span class="badge badge-<?php echo e($complaint->status == 'resolved' ? 'success' : ($complaint->status == 'pending' ? 'warning' : 'info')); ?>">
                                    <?php echo e(ucfirst($complaint->status)); ?>

                                </span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gender Distribution Chart
const genderCtx = document.getElementById('genderChart').getContext('2d');
new Chart(genderCtx, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode(array_keys($genderDistribution)); ?>,
        datasets: [{
            data: <?php echo json_encode(array_values($genderDistribution)); ?>,
            backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

// Age Groups Chart
const ageCtx = document.getElementById('ageChart').getContext('2d');
new Chart(ageCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode(array_keys($ageGroups)); ?>,
        datasets: [{
            data: <?php echo json_encode(array_values($ageGroups)); ?>,
            backgroundColor: ['#f6c23e', '#e74a3b', '#858796']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Monthly Trends Chart
const trendsCtx = document.getElementById('trendsChart').getContext('2d');
new Chart(trendsCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_keys($monthlyRegistrations)); ?>,
        datasets: [{
            label: 'New Registrations',
            data: <?php echo json_encode(array_values($monthlyRegistrations)); ?>,
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/lumanglipa/resources/views/admin/analytics/index.blade.php ENDPATH**/ ?>