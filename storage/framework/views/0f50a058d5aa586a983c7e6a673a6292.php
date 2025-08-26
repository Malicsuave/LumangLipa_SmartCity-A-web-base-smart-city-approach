<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Audit Logs</h1>
    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-2">
                <input type="text" name="user" class="form-control" placeholder="User ID" value="<?php echo e(request('user')); ?>">
            </div>
            <div class="col-md-2">
                <input type="text" name="log_name" class="form-control" placeholder="Log Name" value="<?php echo e(request('log_name')); ?>">
            </div>
            <div class="col-md-2">
                <input type="text" name="description" class="form-control" placeholder="Action" value="<?php echo e(request('description')); ?>">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-block">Filter</button>
            </div>
        </div>
    </form>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Date/Time</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Model</th>
                            <th>Record ID</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($log->created_at->format('Y-m-d H:i:s')); ?></td>
                                <td>
                                    <?php if($log->causer): ?>
                                        <?php echo e($log->causer->name); ?> (ID: <?php echo e($log->causer_id); ?>)
                                    <?php else: ?>
                                        System
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($log->description); ?></td>
                                <td><?php echo e(class_basename($log->subject_type)); ?></td>
                                <td><?php echo e($log->subject_id); ?></td>
                                <td>
                                    <?php if($log->properties && ($log->properties['attributes'] ?? null)): ?>
                                        <details>
                                            <summary>View</summary>
                                            <pre class="mb-0"><?php echo e(json_encode($log->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)); ?></pre>
                                        </details>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="6" class="text-center">No logs found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                <?php echo e($logs->links()); ?>

            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/lumanglipa/resources/views/admin/audit-logs.blade.php ENDPATH**/ ?>