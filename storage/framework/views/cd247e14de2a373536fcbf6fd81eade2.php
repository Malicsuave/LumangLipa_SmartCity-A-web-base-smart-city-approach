<div class="custom-dropdown" x-data="{
    open: false,
    openUp: <?php echo e(isset($dropup) && $dropup ? 'true' : 'false'); ?>,
    dropdownId: Math.random().toString(36).substr(2, 9),
    closeOthers() {
        window.dispatchEvent(new CustomEvent('close-all-dropdowns', { detail: { except: this.dropdownId } }));
    }
}"
@keydown.escape.window="open = false"
@click.away="open = false"
@close-all-dropdowns.window="if ($event.detail.except !== dropdownId) open = false">
    <?php if(isset($buttonText)): ?>
        <button type="button" class="btn btn-dark custom-dropdown-btn"
            @click="closeOthers(); open = !open;"
            :aria-expanded="open"
            style="vertical-align: middle;">
            <i class="fe fe-save fe-16 mr-2"></i> <?php echo e($buttonText); ?>

        </button>
    <?php else: ?>
        <button type="button" class="btn btn-sm btn-icon custom-dropdown-btn"
            @click="closeOthers(); open = !open;"
            :aria-expanded="open"
            style="vertical-align: middle;">
            <i class="fas fa-ellipsis-h"></i>
        </button>
    <?php endif; ?>
    <div class="custom-dropdown-menu"
        x-show="open"
        x-transition
        x-cloak
        @click.away="open = false"
        :style="openUp ? 'bottom: 100%; top: auto;' : 'top: 100%; bottom: auto;'">
        <?php $first = true; ?>
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(isset($item['divider']) && $item['divider']): ?>
                <?php $first = true; ?>
                <?php continue; ?>
            <?php endif; ?>
            <?php if(!isset($item['label'])): ?>
                <?php continue; ?>
            <?php endif; ?>
            <?php if(!$first): ?>
                <div style="height: 2px;"></div>
            <?php endif; ?>
            <?php $first = false; ?>
            <?php
                $iconClass = $item['label'] === 'Reject'
                    ? 'fe fe-x-circle fe-16 text-danger'
                    : $item['icon'];
            ?>

            <?php if(isset($item['is_form']) && $item['is_form']): ?>
                <form action="<?php echo e($item['form_action']); ?>" method="POST" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="custom-dropdown-item d-flex align-items-center <?php echo e($item['class'] ?? ''); ?>"
                        style="padding: 0.35rem 0.75rem; gap: 0.35rem; align-items: center; background:none; border:none; width:100%; text-align:left;">
                        <span style="display: flex; align-items: center; justify-content: center; min-width: 22px; height: 22px;">
                            <i class="<?php echo e($iconClass); ?>" style="font-size: 1.05em; width: 18px; height: 18px; line-height: 1; display: flex; align-items: center; justify-content: center;"></i>
                        </span>
                        <span style="flex:1; display: flex; align-items: center;"><?php echo e($item['label']); ?></span>
                    </button>
                </form>
            <?php else: ?>
                <a href="<?php echo e($item['href'] ?? '#'); ?>" class="custom-dropdown-item d-flex align-items-center <?php echo e($item['class'] ?? ''); ?>"
                   <?php echo isset($item['attrs']) ? $item['attrs'] : ''; ?>

                   <?php if(isset($item['class']) && !empty($item['class'])): ?>
                       @click="open = false"
                   <?php endif; ?>
                   style="padding: 0.35rem 0.75rem; gap: 0.35rem; align-items: center;">
                    <span style="display: flex; align-items: center; justify-content: center; min-width: 22px; height: 22px;">
                        <i class="<?php echo e($iconClass); ?>" style="font-size: 1.05em; width: 18px; height: 18px; line-height: 1; display: flex; align-items: center; justify-content: center;"></i>
                    </span>
                    <span style="flex:1; display: flex; align-items: center;"><?php echo e($item['label']); ?></span>
                </a>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<style>
.custom-dropdown-item {
    transition: background 0.15s, color 0.15s;
    border-radius: 4px;
    cursor: pointer;
    color: #22223b !important;
    font-weight: 400;
}
.custom-dropdown-item:hover, .custom-dropdown-item:focus {
    background: #f0f1f3 !important;
    color: #4a90e2 !important;
    text-decoration: none;
}
.custom-dropdown-item.danger-action:hover, .custom-dropdown-item.danger-action:focus {
    background: #fbeaea !important;
    color: #e3342f !important;
}
</style><?php /**PATH /var/www/html/lumanglipa/resources/views/components/custom-dropdown.blade.php ENDPATH**/ ?>