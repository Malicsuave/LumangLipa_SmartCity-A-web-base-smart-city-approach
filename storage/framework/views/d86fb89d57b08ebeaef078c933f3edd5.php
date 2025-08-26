<?php $__env->startSection('title', 'Access Requests'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row align-items-center mb-4">
                    <div class="col">
                        <h2 class="h5 page-title">Access Request Management</h2>
                        <p class="text-muted">Review and process user access requests for system roles</p>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary" onclick="refreshPage()">
                            <i class="fe fe-refresh-cw fe-16 mr-2"></i>
                            Refresh List
                        </button>
                    </div>
                </div>
                
                <!-- Status Messages -->
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo e(session('error')); ?>

                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>

                <!-- Pending Requests Card -->
                <div class="card shadow-lg border-0 mb-4" style="box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.15) !important;">
                    <div class="card-header">
                        <strong class="card-title">Pending Access Requests</strong>
                    </div>
                    <div class="card-body">
                        <?php if($pendingRequests->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-borderless table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>User</th>
                                            <th>Role Requested</th>
                                            <th>Requested On</th>
                                            <th width="25%">Reason</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $pendingRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $isLastTwo = $loop->remaining < 2;
                                                $dropdownItems = [];
                                                $dropdownItems[] = [
                                                    'label' => 'Review Details',
                                                    'icon' => 'fe fe-eye fe-16 text-primary',
                                                    'class' => '',
                                                    'href' => route('admin.access-requests.show', $request),
                                                ];
                                                $dropdownItems[] = ['divider' => true];
                                                $dropdownItems[] = [
                                                    'label' => 'Approve Request',
                                                    'icon' => 'fe fe-check-circle fe-16 text-success',
                                                    'class' => '',
                                                    'attrs' => "data-toggle=\"modal\" data-target=\"#approveModal{$request->id}\" href='#'",
                                                ];
                                                $dropdownItems[] = [
                                                    'label' => 'Deny Request',
                                                    'icon' => 'fe fe-x-circle fe-16 text-danger',
                                                    'class' => '',
                                                    'attrs' => "data-toggle=\"modal\" data-target=\"#denyModal{$request->id}\" href='#'",
                                                ];
                                            ?>
                                            <tr>
                                                <td>
                                                    <div class="font-weight-bold"><?php echo e($request->name ?? 'Not provided'); ?></div>
                                                </td>
                                                <td>
                                                    <div class="font-weight-bold"><?php echo e($request->user ? $request->user->name : 'Unknown User'); ?></div>
                                                    <div class="small text-muted"><?php echo e($request->user ? $request->user->email : 'No email'); ?></div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-pill badge-primary">
                                                        <?php echo e($request->role ? $request->role->name : 'Unknown Role'); ?>

                                                    </span>
                                                </td>
                                                <td>
                                                    <?php echo e($request->requested_at ? $request->requested_at->format('M d, Y') : 'N/A'); ?><br>
                                                    <small class="text-muted"><?php echo e($request->requested_at ? $request->requested_at->format('h:i A') : ''); ?></small>
                                                </td>
                                                <td>
                                                    <div class="text-sm"><?php echo e(\Illuminate\Support\Str::limit($request->reason, 100)); ?></div>
                                                    <?php if(strlen($request->reason) > 100): ?>
                                                        <a href="<?php echo e(route('admin.access-requests.show', $request)); ?>" class="small">Read more</a>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center table-actions-col">
                                                    <?php echo $__env->make('components.custom-dropdown', ['items' => $dropdownItems, 'dropup' => $isLastTwo], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                    <!-- Approve Modal -->
                                                    <div class="modal fade" id="approveModal<?php echo e($request->id); ?>" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <form action="<?php echo e(route('admin.access-requests.approve', $request)); ?>" method="POST">
                                                                    <?php echo csrf_field(); ?>
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="approveModalLabel">Approve Access Request</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body text-left">
                                                                        <p>Are you sure you want to grant <strong><?php echo e($request->role ? $request->role->name : 'Unknown Role'); ?></strong> access to <strong><?php echo e($request->user ? $request->user->name : 'Unknown User'); ?></strong>?</p>
                                                                        
                                                                        <div class="form-group">
                                                                            <label for="notes">Notes (Optional)</label>
                                                                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add any notes that will be included in the approval email..."></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                                                        <button type="submit" class="btn btn-success">Approve Access</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Deny Modal -->
                                                    <div class="modal fade" id="denyModal<?php echo e($request->id); ?>" tabindex="-1" role="dialog" aria-labelledby="denyModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <form action="<?php echo e(route('admin.access-requests.deny', $request)); ?>" method="POST">
                                                                    <?php echo csrf_field(); ?>
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="denyModalLabel">Deny Access Request</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body text-left">
                                                                        <p>Are you sure you want to deny this access request from <strong><?php echo e($request->user ? $request->user->name : 'Unknown User'); ?></strong>?</p>
                                                                        
                                                                        <div class="form-group">
                                                                            <label for="denial_reason">Reason for Denial <span class="text-danger">*</span></label>
                                                                            <textarea class="form-control" id="denial_reason" name="denial_reason" rows="3" required placeholder="Provide a reason for denying this request. This will be sent to the user."></textarea>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                                                        <button type="submit" class="btn btn-danger">Deny Access</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <svg width="120" height="120" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="mb-3">
                                  <circle cx="60" cy="60" r="56" fill="#f3f4f6" stroke="#e5e7eb" stroke-width="4"/>
                                  <rect x="35" y="70" width="50" height="10" rx="5" fill="#e5e7eb"/>
                                  <ellipse cx="60" cy="54" rx="18" ry="20" fill="#e5e7eb"/>
                                  <ellipse cx="60" cy="54" rx="10" ry="12" fill="#f3f4f6"/>
                                  <circle cx="54" cy="52" r="2" fill="#bdbdbd"/>
                                  <circle cx="66" cy="52" r="2" fill="#bdbdbd"/>
                                  <rect x="56" y="58" width="8" height="2" rx="1" fill="#bdbdbd"/>
                                </svg>
                                <h4>No access requests found</h4>
                                <p class="text-muted">
                                    No access requests match your search criteria.
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Activity Card -->
                <div class="card shadow-lg border-0" style="box-shadow: 0 0.5rem 2rem rgba(0, 0, 0, 0.15) !important;">
                    <div class="card-header">
                        <strong class="card-title">Recently Processed Requests</strong>
                    </div>
                    <div class="card-body">
                        <?php if($processedRequests->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-borderless table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>User</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Processed By</th>
                                            <th>Processed On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $processedRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $request): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>
                                                    <div class="font-weight-bold"><?php echo e($request->name ?? 'Not provided'); ?></div>
                                                </td>
                                                <td>
                                                    <div class="font-weight-bold"><?php echo e($request->user ? $request->user->name : 'Unknown User'); ?></div>
                                                    <div class="small text-muted"><?php echo e($request->user ? $request->user->email : 'No email'); ?></div>
                                                </td>
                                                <td>
                                                    <span class="badge badge-pill badge-primary">
                                                        <?php echo e($request->role ? $request->role->name : 'Unknown Role'); ?>

                                                    </span>
                                                </td>
                                                <td>
                                                    <?php if($request->status == 'approved'): ?>
                                                        <span class="badge badge-success">Approved</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-danger">Denied</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($request->status == 'approved'): ?>
                                                        <?php echo e(optional($request->approver)->name ?? 'System'); ?>

                                                    <?php else: ?>
                                                        <?php echo e(optional($request->denier)->name ?? 'System'); ?>

                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($request->status == 'approved'): ?>
                                                        <?php echo e($request->approved_at ? $request->approved_at->format('M d, Y h:i A') : 'N/A'); ?>

                                                    <?php else: ?>
                                                        <?php echo e($request->denied_at ? $request->denied_at->format('M d, Y h:i A') : 'N/A'); ?>

                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <p class="text-muted">No processed requests yet.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Pagination for processed requests -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted small">
                        <?php if(method_exists($processedRequests, 'firstItem')): ?>
                            Showing <?php echo e($processedRequests->firstItem() ?? 0); ?> to <?php echo e($processedRequests->lastItem() ?? 0); ?> of <?php echo e($processedRequests->total()); ?> processed requests
                        <?php else: ?>
                            Showing <?php echo e($processedRequests->count()); ?> processed requests
                        <?php endif; ?>
                    </div>
                    <nav aria-label="Table Paging" class="mb-0">
                        <ul class="pagination justify-content-end mb-0">
                            <?php if(method_exists($processedRequests, 'onFirstPage')): ?>
                                <?php if($processedRequests->onFirstPage()): ?>
                                    <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="fe fe-arrow-left"></i> Previous</a></li>
                                <?php else: ?>
                                    <li class="page-item"><a class="page-link" href="<?php echo e($processedRequests->previousPageUrl()); ?>"><i class="fe fe-arrow-left"></i> Previous</a></li>
                                <?php endif; ?>
                                
                                <?php for($i = 1; $i <= $processedRequests->lastPage(); $i++): ?>
                                    <li class="page-item <?php echo e($i == $processedRequests->currentPage() ? 'active' : ''); ?>">
                                        <a class="page-link" href="<?php echo e($processedRequests->url($i)); ?>"><?php echo e($i); ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if($processedRequests->hasMorePages()): ?>
                                    <li class="page-item"><a class="page-link" href="<?php echo e($processedRequests->nextPageUrl()); ?>">Next <i class="fe fe-arrow-right"></i></a></li>
                                <?php else: ?>
                                    <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next <i class="fe fe-arrow-right"></i></a></li>
                                <?php endif; ?>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                
                <!-- Access Request Information -->
               
            </div>
        </div>
    </div>

    <script>
        function refreshPage() {
            window.location.reload();
        }
    </script>

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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/lumanglipa/resources/views/admin/access-requests/index.blade.php ENDPATH**/ ?>