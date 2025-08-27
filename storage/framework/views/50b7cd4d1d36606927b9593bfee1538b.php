<?php $__env->startSection('content'); ?>
<div class="container-fluid mt-4">
    <h4>Edit Barangay Officials</h4>
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <form action="<?php echo e(route('admin.officials.update-single')); ?>" method="POST" class="card card-body shadow-sm">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label for="captain_name">Barangay Captain</label>
            <input type="text" name="captain_name" id="captain_name" class="form-control <?php $__errorArgs = ['captain_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('captain_name', $officials->captain_name)); ?>">
            <?php $__errorArgs = ['captain_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <div class="mt-1 text-muted"><strong>Current:</strong> <?php echo e($officials->captain_name); ?></div>
        </div>
        <?php for($i = 1; $i <= 7; $i++): ?>
        <div class="form-group">
            <label for="councilor<?php echo e($i); ?>_name">Councilor <?php echo e($i); ?></label>
            <input type="text" name="councilor<?php echo e($i); ?>_name" id="councilor<?php echo e($i); ?>_name" class="form-control <?php $__errorArgs = ['councilor'.$i.'_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('councilor'.$i.'_name', $officials->{'councilor'.$i.'_name'})); ?>">
            <?php $__errorArgs = ['councilor'.$i.'_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <div class="mt-1 text-muted"><strong>Current:</strong> <?php echo e($officials->{'councilor'.$i.'_name'}); ?></div>
            <?php $committee = $officials->{'councilor'.$i.'_committee'} ?? null; ?>
            <div class="mt-1 text-muted"><strong>Committee:</strong> <?php echo e($committee ?: '—'); ?></div>
        </div>
        <?php endfor; ?>
        <div class="form-group">
            <label for="sk_chairperson_name">SK Chairperson</label>
            <input type="text" name="sk_chairperson_name" id="sk_chairperson_name" class="form-control <?php $__errorArgs = ['sk_chairperson_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('sk_chairperson_name', $officials->sk_chairperson_name)); ?>">
            <?php $__errorArgs = ['sk_chairperson_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <div class="mt-1 text-muted"><strong>Current:</strong> <?php echo e($officials->sk_chairperson_name); ?></div>
            <?php $sk_committee = $officials->sk_chairperson_committee ?? null; ?>
            <div class="mt-1 text-muted"><strong>Committee:</strong> <?php echo e($sk_committee ?: '—'); ?></div>
        </div>
        <div class="form-group">
            <label for="secretary_name">Barangay Secretary</label>
            <input type="text" name="secretary_name" id="secretary_name" class="form-control <?php $__errorArgs = ['secretary_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('secretary_name', $officials->secretary_name)); ?>">
            <?php $__errorArgs = ['secretary_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <div class="mt-1 text-muted"><strong>Current:</strong> <?php echo e($officials->secretary_name); ?></div>
        </div>
        <div class="form-group">
            <label for="treasurer_name">Barangay Treasurer</label>
            <input type="text" name="treasurer_name" id="treasurer_name" class="form-control <?php $__errorArgs = ['treasurer_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('treasurer_name', $officials->treasurer_name)); ?>">
            <?php $__errorArgs = ['treasurer_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <div class="mt-1 text-muted"><strong>Current:</strong> <?php echo e($officials->treasurer_name); ?></div>
        </div>
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/lumanglipa/resources/views/admin/officials/edit-single.blade.php ENDPATH**/ ?>