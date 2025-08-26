<?php
// This partial expects $pendingRenewal to be passed in
?>
<div class="card-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-0"><i class="fe fe-refresh-cw fe-16 mr-2"></i>IDs Pending Renewal</h4>
            <p class="text-muted mb-0">Residents with IDs marked for renewal</p>
        </div>
    </div>
</div>
<div class="card-body">
    <?php echo $__env->make('admin.residents.pending-ids-filter-renewal', ['pendingRenewal' => $pendingRenewal], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div> <?php /**PATH /var/www/html/lumanglipa/resources/views/admin/residents/partials/pending-ids-renewal.blade.php ENDPATH**/ ?>