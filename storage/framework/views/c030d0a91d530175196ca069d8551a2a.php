<thead>
<tr>
    <th>
        <a href="<?php echo e(request()->fullUrlWithQuery(['renewal_sort' => 'barangay_id', 'renewal_direction' => request('renewal_sort') == 'barangay_id' && request('renewal_direction') == 'asc' ? 'desc' : 'asc', 'tab' => 'renewal'])); ?>" 
           class="text-decoration-none d-flex align-items-center" style="color: inherit;">
            Barangay ID
            <?php if(request('renewal_sort') == 'barangay_id'): ?>
                <?php if(request('renewal_direction') == 'asc'): ?>
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
        <a href="<?php echo e(request()->fullUrlWithQuery(['renewal_sort' => 'name', 'renewal_direction' => request('renewal_sort') == 'name' && request('renewal_direction') == 'asc' ? 'desc' : 'asc', 'tab' => 'renewal'])); ?>" 
           class="text-decoration-none d-flex align-items-center" style="color: inherit;">
            Name
            <?php if(request('renewal_sort') == 'name'): ?>
                <?php if(request('renewal_direction') == 'asc'): ?>
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
        <a href="<?php echo e(request()->fullUrlWithQuery(['renewal_sort' => 'type', 'renewal_direction' => request('renewal_sort') == 'type' && request('renewal_direction') == 'asc' ? 'desc' : 'asc', 'tab' => 'renewal'])); ?>" 
           class="text-decoration-none d-flex align-items-center" style="color: inherit;">
            Type
            <?php if(request('renewal_sort') == 'type'): ?>
                <?php if(request('renewal_direction') == 'asc'): ?>
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
        <a href="<?php echo e(request()->fullUrlWithQuery(['renewal_sort' => 'age', 'renewal_direction' => request('renewal_sort') == 'age' && request('renewal_direction') == 'asc' ? 'desc' : 'asc', 'tab' => 'renewal'])); ?>" 
           class="text-decoration-none d-flex align-items-center" style="color: inherit;">
            Age/Gender
            <?php if(request('renewal_sort') == 'age'): ?>
                <?php if(request('renewal_direction') == 'asc'): ?>
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
        <a href="<?php echo e(request()->fullUrlWithQuery(['renewal_sort' => 'issued_date', 'renewal_direction' => request('renewal_sort') == 'issued_date' && request('renewal_direction') == 'asc' ? 'desc' : 'asc', 'tab' => 'renewal'])); ?>" 
           class="text-decoration-none d-flex align-items-center" style="color: inherit;">
            Issued Date
            <?php if(request('renewal_sort') == 'issued_date'): ?>
                <?php if(request('renewal_direction') == 'asc'): ?>
                    <i class="fe fe-chevron-up ml-1"></i>
                <?php else: ?>
                    <i class="fe fe-chevron-down ml-1"></i>
                <?php endif; ?>
            <?php else: ?>
                <i class="fe fe-chevrons-up-down ml-1 text-muted" style="font-size: 12px;"></i>
            <?php endif; ?>
        </a>
    </th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php $__currentLoopData = $pendingRenewal; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
    <td>
        <?php echo e($resident->type_of_resident); ?>

    </td>
    <td>
        <?php echo e(\Carbon\Carbon::parse($resident->birthdate)->age); ?> years old
        <br><small class="text-muted"><?php echo e($resident->sex); ?></small>
    </td>
    <td><?php echo e($resident->id_issued_at ? $resident->id_issued_at->format('M d, Y') : 'N/A'); ?></td>
    <td>
        <?php
            $dropdownItems = [];
            $dropdownItems[] = [
                'label' => 'Manage ID',
                'icon' => 'fe fe-credit-card fe-16 text-primary',
                'href' => route('admin.residents.id.show', $resident->id),
            ];
            $dropdownItems[] = [
                'label' => 'Issue New ID (Renew)',
                'icon' => 'fe fe-check-circle fe-16 text-success',
                'class' => '',
                'attrs' => 'onclick="return confirm(\'Are you sure you want to issue a new ID for this resident?\')"',
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
                'label' => 'Revoke ID',
                'icon' => 'fe fe-x-circle fe-16 text-danger',
                'attrs' => 'onclick="return confirm(\'Are you sure you want to revoke this resident\\\'s ID? This action cannot be undone.\')"',
                'href' => '',
                'is_form' => true,
                'form_action' => route('admin.residents.id.revoke', $resident->id),
            ];
            $dropdownItems[] = [
                'label' => 'Remove from Renewal Queue',
                'icon' => 'fe fe-minus-circle fe-16 text-warning',
                'class' => '',
                'attrs' => 'onclick="return confirm(\'Remove this resident from the renewal queue?\')"',
                'href' => '',
                'is_form' => true,
                'form_action' => route('admin.residents.id.remove-renewal', $resident->id),
            ];
        ?>
        <?php echo $__env->make('components.custom-dropdown', ['items' => $dropdownItems, 'dropup' => $isLast], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </td>
</tr>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tbody> <?php /**PATH /var/www/html/lumanglipa/resources/views/admin/residents/partials/pending-ids-table-renewal.blade.php ENDPATH**/ ?>