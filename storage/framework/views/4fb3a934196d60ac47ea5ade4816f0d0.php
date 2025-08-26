<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/profile-page.css')); ?>">
<style>
/* Style active tab */
.nav-tabs .nav-link.active {
    background-color: #fff;
    border-color: #dee2e6 #dee2e6 #fff;
    color: #495057;
}

.nav-tabs .nav-link {
    border: 1px solid transparent;
    cursor: pointer;
}

.nav-tabs .nav-link:hover {
    border-color: #e9ecef #e9ecef #dee2e6;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card shadow mb-4 profile-page">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary">Settings</h4>
    </div>
    <div class="card-body">
        <!-- Profile Section -->
        <div class="mb-5">
            <h4 class="mb-4 text-primary">Profile</h4>
                
                <!-- Flash Messages for Profile Tab -->
                <?php if(session('profile_status')): ?>
                    <div class="alert alert-success">
                        <?php echo e(session('profile_status')); ?>

                    </div>
                <?php endif; ?>
                <?php if($errors->any() && ($errors->has('name') || $errors->has('email') || $errors->has('photo'))): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <!-- Profile Photo Section -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="mb-3">Profile Photo</h5>
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center">
                                <img src="<?php echo e(Auth::user()->profile_photo_url); ?>" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #eaeaea; box-shadow: 0 2px 10px rgba(0,0,0,0.1);" alt="Profile Photo" id="profilePhotoPreview">
                            </div>
                            <div class="col-md-9">
                                <form action="<?php echo e(route('admin.profile.photo.update')); ?>" method="POST" enctype="multipart/form-data" class="mb-2">
                                    <?php echo csrf_field(); ?>
                                    <div class="form-group">
                                        <label for="photo">Select New Photo</label>
                                        <input type="file" class="form-control-file <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="photo" name="photo" accept="image/*" onchange="previewPhoto(this)">
                                        <small class="form-text text-muted">JPG, JPEG, PNG. Max file size: 1MB</small>
                                        <?php $__errorArgs = ['photo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">Upload Photo</button>
                                </form>
                                
                                <?php if(Auth::user()->profile_photo_path): ?>
                                <form action="<?php echo e(route('admin.profile.photo.delete')); ?>" method="POST" onsubmit="return confirm('Are you sure you want to remove your profile photo?');">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Remove Photo</button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Profile Information Section -->
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="mb-3">Profile Information</h5>
                        <form action="<?php echo e(route('admin.profile.update')); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="name" name="name" value="<?php echo e(old('name', Auth::user()->name)); ?>" required>
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="email" name="email" value="<?php echo e(old('email', Auth::user()->email)); ?>" required>
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Role</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="<?php echo e(Auth::user()->role->name ?? 'N/A'); ?>" readonly>
                                    <small class="form-text text-muted">Role cannot be changed</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Registered</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="<?php echo e(Auth::user()->created_at->format('F d, Y')); ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-10 offset-sm-2">
                                    <button type="submit" class="btn btn-primary">Update Profile</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
        <!-- Security Section -->
        <div class="mt-5">
            <h4 class="mb-4 text-primary">Security</h4>
            <h5 class="mb-2">Security Settings</h5>
            <p class="text-muted mb-4">These settings help you keep your account secure.</p>

            <!-- Flash Messages for Security Section -->
            <?php if(session('security_status')): ?>
                <div class="alert alert-success">
                    <?php echo e(session('security_status')); ?>

                </div>
            <?php endif; ?>
            <?php if($errors->any() && !$errors->has('name') && !$errors->has('email') && !$errors->has('photo')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php if(session('error') || session('security_error')): ?>
                <div class="alert alert-danger">
                    <?php echo e(session('error') ?? session('security_error')); ?>

                </div>
            <?php endif; ?>

            <!-- Change Password Form -->
            <form method="POST" action="<?php echo e(route('admin.profile.password.update')); ?>" class="mb-4">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" class="form-control <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="current_password" name="current_password" required autocomplete="current-password">
                    <?php $__errorArgs = ['current_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="new_password" name="new_password" required autocomplete="new-password">
                    <?php $__errorArgs = ['new_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div class="form-group">
                    <label for="new_password_confirmation">Confirm New Password</label>
                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required autocomplete="new-password">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Change Password</button>
            </form>

            <!-- Google Authentication Notice -->
            <?php if(Auth::user()->google_id && !Auth::user()->two_factor_secret): ?>
            <div class="list-group list-group-flush mb-4">
                <div class="list-group-item bg-white d-flex justify-content-between align-items-center border-0">
                    <div>
                        <strong>Google Authentication</strong>
                        <span class="badge badge-info ml-2">Gmail Account</span>
                        <div class="text-muted small">You need to set a password before enabling Two-Factor Authentication.</div>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#setPasswordModal">
                        Set Password
                    </button>
                </div>
            </div>
            <?php endif; ?>

            <!-- 2FA Section -->
            <div class="list-group list-group-flush">
                <div class="list-group-item bg-white d-flex justify-content-between align-items-center border-0">
                    <div>
                        <strong>2FA Authentication</strong>
                        <?php if(Auth::user()->two_factor_secret): ?>
                            <span class="badge badge-success ml-2">Enabled</span>
                            <div class="text-muted small">Two-factor authentication is currently enabled for your account.</div>
                        <?php else: ?>
                            <span class="badge badge-danger ml-2">Disabled</span>
                            <div class="small">Two-factor authentication is not enabled on your account.</div>
                        <?php endif; ?>
                    </div>
                    <?php if(Auth::user()->two_factor_secret): ?>
                        <form method="POST" action="<?php echo e(route('two-factor.disable')); ?>" onsubmit="return confirm('Are you sure you want to disable Two-Factor Authentication?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger btn-sm">Disable</button>
                        </form>
                    <?php else: ?>
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#confirmTwoFactorModal">
                            Enable
                        </button>
                    <?php endif; ?>
                </div>

                <?php if(Auth::user()->two_factor_secret): ?>
                    <div class="mt-4">
                        <h5 class="text-muted">QR Code</h5>
                        <p class="mb-2">Scan the following QR code using your authentication app:</p>
                        <div class="mt-2 p-2 inline-block bg-light">
                            <?php echo auth()->user()->twoFactorQrCodeSvg(); ?>

                        </div>

                        <h5 class="mt-4 text-muted">Recovery Codes</h5>
                        <p class="mb-2">Save these recovery codes in a secure location:</p>
                        <div class="bg-light p-3 rounded">
                            <ul class="list-unstyled">
                                <?php $__currentLoopData = json_decode(decrypt(Auth::user()->two_factor_recovery_codes), true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="font-mono text-sm"><?php echo e($code); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                        <form method="POST" action="<?php echo e(route('two-factor.recovery-codes')); ?>" class="mt-3">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-secondary btn-sm">Regenerate Recovery Codes</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Password Confirmation Modal for 2FA -->
<div class="modal fade" id="confirmTwoFactorModal" tabindex="-1" aria-labelledby="confirmTwoFactorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('admin.two-factor.enable')); ?>" id="two-factor-form">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmTwoFactorModalLabel">Confirm Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>For your security, please confirm your password to enable two-factor authentication.</p>
                    
                    <div class="form-group">
                        <label for="modal_password">Password</label>
                        <input type="password" class="form-control" id="modal_password" name="password" required autocomplete="current-password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Set Password Modal -->
<div class="modal fade" id="setPasswordModal" tabindex="-1" aria-labelledby="setPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo e(route('password.update')); ?>" id="set-password-form">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="setPasswordModalLabel">Set Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="modal_new_password">New Password</label>
                        <input type="password" class="form-control" id="modal_new_password" name="new_password" required autocomplete="new-password">
                    </div>
                    <div class="form-group">
                        <label for="modal_new_password_confirmation">Confirm New Password</label>
                        <input type="password" class="form-control" id="modal_new_password_confirmation" name="new_password_confirmation" required autocomplete="new-password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Set Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<script>
// Preview profile photo before upload
function previewPhoto(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePhotoPreview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}

$(function () {
    var tabSet = false;
    // 1. Session or error-based
    <?php if(session('show_security_tab') || $errors->has('current_password') || $errors->has('new_password') || session('security_error') || session('from_2fa')): ?>
        $('#profileTab a[href="#security"]').tab('show');
        tabSet = true;
    <?php elseif(session('profile_status') || $errors->has('name') || $errors->has('email') || $errors->has('photo')): ?>
        $('#profileTab a[href="#profile"]').tab('show');
        tabSet = true;
    <?php endif; ?>

    // 2. Hash in URL
    if (!tabSet) {
        var hash = window.location.hash;
        if (hash && $('#profileTab a[href="' + hash + '"]').length) {
            $('#profileTab a[href="' + hash + '"]').tab('show');
            tabSet = true;
        }
    }
    // 3. localStorage
    if (!tabSet) {
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab && $('#profileTab a[href="' + activeTab + '"]').length) {
            $('#profileTab a[href="' + activeTab + '"]').tab('show');
            tabSet = true;
        }
    }
    // 4. Default to first tab
    if (!tabSet) {
        $('#profileTab a[data-toggle="tab"]').first().tab('show');
    }

    // Save tab on click
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var tabId = $(e.target).attr('href');
        localStorage.setItem('activeTab', tabId);
        window.location.hash = tabId;
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/lumanglipa/resources/views/admin/profile.blade.php ENDPATH**/ ?>