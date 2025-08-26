<?php $__env->startSection('title', 'Security Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Security Overview Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Successful Logins Today</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($metrics['successful_logins_today']); ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Failed Logins Today</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($metrics['failed_logins_today']); ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Locked Accounts</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($metrics['locked_accounts']); ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-lock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Active Sessions</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($metrics['active_sessions']); ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Security Metrics -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <i class="fas fa-shield-alt fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">2FA Enabled</div>
                                    <div class="h5 mb-0 font-weight-bold"><?php echo e($metrics['accounts_with_2fa']); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <i class="fas fa-key fa-2x text-warning"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Password Changes Required</div>
                                    <div class="h5 mb-0 font-weight-bold"><?php echo e($metrics['accounts_requiring_password_change']); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <i class="fas fa-clock fa-2x text-danger"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Expired Passwords</div>
                                    <div class="h5 mb-0 font-weight-bold"><?php echo e($metrics['expired_passwords']); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card shadow h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <i class="fas fa-bug fa-2x text-danger"></i>
                                </div>
                                <div>
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Suspicious Activities Today</div>
                                    <div class="h5 mb-0 font-weight-bold"><?php echo e($metrics['suspicious_activities_today']); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Recent Security Events -->
                <div class="col-lg-8 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Security Events</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Time</th>
                                            <th>User</th>
                                            <th>Event</th>
                                            <th>IP Address</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__empty_1 = true; $__currentLoopData = $recentEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e(\Carbon\Carbon::parse($event['created_at'])->diffForHumans()); ?></td>
                                            <td>
                                                <small><?php echo e($event['user']); ?><br>
                                                <span class="text-muted"><?php echo e($event['email']); ?></span></small>
                                            </td>
                                            <td>
                                                <span class="badge badge-<?php echo e($event['type'] === 'login_success' ? 'success' : ($event['type'] === 'login_failed' ? 'danger' : 'warning')); ?>">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $event['type']))); ?>

                                                </span>
                                            </td>
                                            <td><small><?php echo e($event['ip_address']); ?></small></td>
                                            <td>
                                                <?php if($event['is_suspicious']): ?>
                                                    <i class="fas fa-exclamation-triangle text-warning" title="Suspicious activity"></i>
                                                <?php else: ?>
                                                    <i class="fas fa-check-circle text-success" title="Normal activity"></i>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No recent security events</td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Locked Accounts -->
                <div class="col-lg-4 mb-4">
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-danger">Locked Accounts</h6>
                        </div>
                        <div class="card-body">
                            <?php $__empty_1 = true; $__currentLoopData = $lockedAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                                <div>
                                    <strong><?php echo e($account['name']); ?></strong><br>
                                    <small class="text-muted"><?php echo e($account['email']); ?></small><br>
                                    <small class="text-danger"><?php echo e($account['failed_attempts']); ?> failed attempts</small>
                                </div>
                                <div class="text-right">
                                    <small class="text-muted">Unlocks <?php echo e($account['unlock_time']); ?></small><br>
                                    <button class="btn btn-sm btn-outline-success" onclick="unlockAccount(<?php echo e($account['id']); ?>)">
                                        <i class="fas fa-unlock"></i> Unlock
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="text-muted">No accounts are currently locked</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Suspicious Activities -->
            <?php if(count($suspiciousActivities) > 0): ?>
            <div class="row">
                <div class="col-12">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-warning">Suspicious Activities</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Time</th>
                                            <th>User</th>
                                            <th>Activity</th>
                                            <th>IP Address</th>
                                            <th>Details</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $suspiciousActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e(\Carbon\Carbon::parse($activity['created_at'])->format('M j, Y H:i')); ?></td>
                                            <td>
                                                <?php echo e($activity['user']); ?><br>
                                                <small class="text-muted"><?php echo e($activity['email']); ?></small>
                                            </td>
                                            <td>
                                                <span class="badge badge-warning">
                                                    <?php echo e(ucfirst(str_replace('_', ' ', $activity['type']))); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e($activity['ip_address']); ?></td>
                                            <td>
                                                <?php if(isset($activity['details']['reasons'])): ?>
                                                    <small><?php echo e(implode(', ', $activity['details']['reasons'])); ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($activity['email'] !== 'N/A'): ?>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-warning btn-sm" onclick="forcePasswordChange('<?php echo e($activity['email']); ?>')">
                                                        <i class="fas fa-key"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger btn-sm" onclick="disableAccount('<?php echo e($activity['email']); ?>')">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modals for Account Actions -->
<div class="modal fade" id="disableAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Disable User Account</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="disableAccountForm">
                    <div class="form-group">
                        <label>Reason for disabling account:</label>
                        <textarea class="form-control" name="reason" rows="3" required placeholder="Enter reason for account suspension..."></textarea>
                    </div>
                    <input type="hidden" name="user_email" id="disableUserEmail">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="submitDisableAccount()">Disable Account</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function unlockAccount(userId) {
    if (!confirm('Are you sure you want to unlock this account?')) return;
    
    fetch(`/admin/security/users/${userId}/unlock`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while unlocking the account');
    });
}

function forcePasswordChange(userEmail) {
    if (!confirm('Force this user to change their password on next login?')) return;
    
    // Find user ID by email (you'd need to pass this in the view or make an API call)
    // For now, we'll use a simplified approach
    fetch('/admin/security/users/force-password-change', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({email: userEmail})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('User will be required to change password on next login');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}

function disableAccount(userEmail) {
    document.getElementById('disableUserEmail').value = userEmail;
    $('#disableAccountModal').modal('show');
}

function submitDisableAccount() {
    const form = document.getElementById('disableAccountForm');
    const formData = new FormData(form);
    const userEmail = formData.get('user_email');
    const reason = formData.get('reason');
    
    if (!reason.trim()) {
        alert('Please provide a reason for disabling the account');
        return;
    }
    
    // Similar to above, this would need proper user ID resolution
    fetch('/admin/security/users/disable', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({email: userEmail, reason: reason})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#disableAccountModal').modal('hide');
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while disabling the account');
    });
}

// Auto-refresh every 30 seconds
setInterval(() => {
    location.reload();
}, 30000);
</script>
<?php $__env->stopSection(); ?>

<style>
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
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/lumanglipa/resources/views/admin/security/dashboard.blade.php ENDPATH**/ ?>