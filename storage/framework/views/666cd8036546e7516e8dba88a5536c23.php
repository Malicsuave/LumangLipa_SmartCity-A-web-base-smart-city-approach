<?php
// This partial expects $pendingIssuance to be passed in
?>
<div class="card-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0"><i class="fe fe-credit-card fe-16 mr-2"></i>Pending ID Issuance</h4>
            <p class="text-muted mb-0">Residents ready for ID card issuance</p>
        </div>
        <div>
            <a href="<?php echo e(route('admin.residents.id.bulk-upload')); ?>" class="btn btn-primary mr-2">
                <i class="fe fe-upload-cloud fe-16 mr-2"></i>Bulk Photo Upload
            </a>
            <a href="<?php echo e(route('admin.residents.index')); ?>" class="btn btn-outline-secondary">
                <i class="fe fe-users fe-16 mr-2"></i>All Residents
            </a>
        </div>
    </div>
</div>
<div class="card-body">
    <?php echo $__env->make('admin.residents.pending-ids-filter-main', ['pendingIssuance' => $pendingIssuance], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="table-responsive mt-4">
        <?php if($pendingIssuance->count() > 0): ?>
            <table class="table table-borderless table-striped" id="pendingIssuanceTable">
                <thead>
                    <tr>
                        <th>
                            <a href="<?php echo e(request()->fullUrlWithQuery(['issuance_sort' => 'barangay_id', 'issuance_direction' => request('issuance_sort') == 'barangay_id' && request('issuance_direction') == 'asc' ? 'desc' : 'asc', 'tab' => 'issuance'])); ?>" class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                Barangay ID
                                <?php if(request('issuance_sort') == 'barangay_id'): ?>
                                    <?php if(request('issuance_direction') == 'asc'): ?>
                                        <i class="fe fe-chevron-up ml-1"></i>
                                    <?php else: ?>
                                        <i class="fe fe-chevron-down ml-1"></i>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?php echo e(request()->fullUrlWithQuery(['issuance_sort' => 'name', 'issuance_direction' => request('issuance_sort') == 'name' && request('issuance_direction') == 'asc' ? 'desc' : 'asc', 'tab' => 'issuance'])); ?>" class="text-decoration-none d-flex align-items-center" style="color: inherit;">
                                Name
                                <?php if(request('issuance_sort') == 'name'): ?>
                                    <?php if(request('issuance_direction') == 'asc'): ?>
                                        <i class="fe fe-chevron-up ml-1"></i>
                                    <?php else: ?>
                                        <i class="fe fe-chevron-down ml-1"></i>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
                                <?php endif; ?>
                            </a>
                        </th>
                        <th>Type</th>
                        <th>Age/Gender</th>
                        <th>Requested At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $pendingIssuance; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $isLast = $loop->last;
                        ?>
                        <tr>
                            <td><strong><?php echo e($resident->barangay_id); ?></strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm mr-2">
                                        <?php if($resident->photo): ?>
                                            <img src="<?php echo e($resident->photo_url); ?>" alt="<?php echo e($resident->full_name); ?>" class="avatar-img rounded-circle">
                                        <?php else: ?>
                                            <div class="avatar-letter rounded-circle bg-warning"><?php echo e(substr($resident->first_name, 0, 1)); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <strong><?php echo e($resident->last_name); ?>, <?php echo e($resident->first_name); ?></strong>
                                        <?php if($resident->middle_name): ?>
                                            <?php echo e(substr($resident->middle_name, 0, 1)); ?>.
                                        <?php endif; ?>
                                        <?php echo e($resident->suffix); ?>

                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($resident->type_of_resident); ?></td>
                            <td>
                                <?php echo e(\Carbon\Carbon::parse($resident->birthdate)->age); ?> years old
                                <br><small class="text-muted"><?php echo e($resident->sex); ?></small>
                            </td>
                            <td><?php echo e($resident->created_at ? $resident->created_at->format('M d, Y') : 'N/A'); ?></td>
                            <td>
                                <?php
                                    $dropdownItems = [];
                                    $dropdownItems[] = [
                                        'label' => 'Manage ID',
                                        'icon' => 'fe fe-credit-card fe-16 text-primary',
                                        'href' => route('admin.residents.id.show', $resident->id),
                                    ];
                                    $dropdownItems[] = [
                                        'label' => 'Issue ID',
                                        'icon' => 'fe fe-check-circle fe-16 text-success',
                                        'class' => '',
                                        'attrs' => 'onclick="return confirm(\'Are you sure you want to issue an ID for this resident?\')"',
                                        'href' => '',
                                        'is_form' => true,
                                        'form_action' => route('admin.residents.id.issue', $resident->id),
                                    ];
                                    $dropdownItems[] = [
                                        'label' => 'Preview ID',
                                        'icon' => 'fe fe-image fe-16 text-info',
                                        'href' => route('admin.residents.id.preview', $resident->id),
                                    ];
                                    $dropdownItems[] = [
                                        'label' => 'Download ID',
                                        'icon' => 'fe fe-download fe-16 text-success',
                                        'href' => route('admin.residents.id.download', $resident->id),
                                    ];
                                    $dropdownItems[] = ['divider' => true];
                                    $dropdownItems[] = [
                                        'label' => 'Remove from Issuance Queue',
                                        'icon' => 'fe fe-minus-circle fe-16 text-warning',
                                        'class' => '',
                                        'attrs' => 'onclick="return confirm(\'Remove this resident from the issuance queue?\')"',
                                        'href' => '',
                                        'is_form' => true,
                                        'form_action' => route('admin.residents.id.remove-issuance', $resident->id),
                                    ];
                                ?>
                                <?php echo $__env->make('components.custom-dropdown', ['items' => $dropdownItems, 'dropup' => $isLast], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Showing <?php echo e($pendingIssuance->firstItem() ?? 0); ?> to <?php echo e($pendingIssuance->lastItem() ?? 0); ?> of <?php echo e($pendingIssuance->total()); ?> pending issuance
                </div>
                <nav aria-label="Table Paging" class="mb-0">
                    <ul class="pagination justify-content-end mb-0">
                        <?php if($pendingIssuance->onFirstPage()): ?>
                            <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true"><i class="fe fe-arrow-left"></i> Previous</a></li>
                        <?php else: ?>
                            <li class="page-item"><a class="page-link" href="<?php echo e($pendingIssuance->previousPageUrl()); ?>&tab=issuance"><i class="fe fe-arrow-left"></i> Previous</a></li>
                        <?php endif; ?>
                        <?php for($i = 1; $i <= $pendingIssuance->lastPage(); $i++): ?>
                            <li class="page-item <?php echo e($i == $pendingIssuance->currentPage() ? 'active' : ''); ?>">
                                <a class="page-link" href="<?php echo e($pendingIssuance->url($i)); ?>&tab=issuance"><?php echo e($i); ?></a>
                            </li>
                        <?php endfor; ?>
                        <?php if($pendingIssuance->hasMorePages()): ?>
                            <li class="page-item"><a class="page-link" href="<?php echo e($pendingIssuance->nextPageUrl()); ?>&tab=issuance">Next <i class="fe fe-arrow-right"></i></a></li>
                        <?php else: ?>
                            <li class="page-item disabled"><a class="page-link" href="#" tabindex="-1" aria-disabled="true">Next <i class="fe fe-arrow-right"></i></a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php else: ?>
            <div class="text-center py-5" id="pendingIssuanceNoResults">
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
                <h4>No residents pending ID issuance</h4>
                <p class="text-muted">No residents match your search or filter criteria.</p>
            </div>
        <?php endif; ?>
    </div>
</div> <?php /**PATH /var/www/html/lumanglipa/resources/views/admin/residents/partials/pending-ids-issuance.blade.php ENDPATH**/ ?>