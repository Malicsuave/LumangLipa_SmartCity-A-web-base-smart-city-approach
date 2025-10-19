@extends('layouts.admin.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/profile-page.css') }}">
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
@endsection

@section('content')
<div class="card shadow mb-4 profile-page">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary">Settings</h4>
    </div>
    <div class="card-body">
        <!-- Profile Section -->
        <div class="mb-5">
            <h4 class="mb-4 text-primary">Profile</h4>
                
                <!-- Flash Messages for Profile Tab -->
                @if (session('profile_status'))
                    <div class="alert alert-success">
                        {{ session('profile_status') }}
                    </div>
                @endif
                @if ($errors->any() && ($errors->has('name') || $errors->has('email') || $errors->has('photo')))
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- Profile Photo Section -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <h5 class="mb-3">Profile Photo</h5>
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center">
                                <img src="{{ Auth::user()->profile_photo_url }}" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #eaeaea; box-shadow: 0 2px 10px rgba(0,0,0,0.1);" alt="Profile Photo" id="profilePhotoPreview">
                            </div>
                            <div class="col-md-9">
                                <form action="{{ route('admin.profile.photo.update') }}" method="POST" enctype="multipart/form-data" class="mb-2">
                                    @csrf
                                    <div class="form-group">
                                        <label for="photo">Select New Photo</label>
                                        <input type="file" class="form-control-file @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*" onchange="previewPhoto(this)">
                                        <small class="form-text text-muted">JPG, JPEG, PNG. Max file size: 1MB</small>
                                        @error('photo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">Upload Photo</button>
                                </form>
                                
                                @if(Auth::user()->profile_photo_path)
                                <form action="{{ route('admin.profile.photo.delete') }}" method="POST" onsubmit="return confirm('Are you sure you want to remove your profile photo?');">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Remove Photo</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Profile Information Section -->
                <div class="row">
                    <div class="col-md-12">
                        <h5 class="mb-3">Profile Information</h5>
                        <form action="{{ route('admin.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Role</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ Auth::user()->role->name ?? 'N/A' }}" readonly>
                                    <small class="form-text text-muted">Role cannot be changed</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Registered</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ Auth::user()->created_at->format('F d, Y') }}" readonly>
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
            @if (session('security_status'))
                <div class="alert alert-success">
                    {{ session('security_status') }}
                </div>
            @endif
            @if ($errors->any() && !$errors->has('name') && !$errors->has('email') && !$errors->has('photo'))
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('error') || session('security_error'))
                <div class="alert alert-danger">
                    {{ session('error') ?? session('security_error') }}
                </div>
            @endif

            <!-- Change Password Form -->
            <form method="POST" action="{{ route('admin.profile.password.update') }}" class="mb-4">
                @csrf
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required autocomplete="current-password">
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required autocomplete="new-password">
                    @error('new_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="new_password_confirmation">Confirm New Password</label>
                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required autocomplete="new-password">
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Change Password</button>
            </form>

            <!-- Google Authentication Notice -->
            @if(Auth::user()->google_id && !Auth::user()->two_factor_secret)
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
            @endif

            <!-- 2FA Section -->
            <div class="list-group list-group-flush">
                <div class="list-group-item bg-white d-flex justify-content-between align-items-center border-0">
                    <div>
                        <strong>2FA Authentication</strong>
                        @if (Auth::user()->two_factor_secret)
                            <span class="badge badge-success ml-2">Enabled</span>
                            <div class="text-muted small">Two-factor authentication is currently enabled for your account.</div>
                        @else
                            <span class="badge badge-danger ml-2">Disabled</span>
                            <div class="small">Two-factor authentication is not enabled on your account.</div>
                        @endif
                    </div>
                    @if (Auth::user()->two_factor_secret)
                        <form method="POST" action="{{ route('two-factor.disable') }}" onsubmit="return confirm('Are you sure you want to disable Two-Factor Authentication?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Disable</button>
                        </form>
                    @else
                        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#confirmTwoFactorModal">
                            Enable
                        </button>
                    @endif
                </div>

                @if (Auth::user()->two_factor_secret)
                    <div class="mt-4">
                        <h5 class="text-muted">QR Code</h5>
                        <p class="mb-2">Scan the following QR code using your authentication app:</p>
                        <div class="mt-2 p-2 inline-block bg-light">
                            {!! auth()->user()->twoFactorQrCodeSvg() !!}
                        </div>

                        <h5 class="mt-4 text-muted">Recovery Codes</h5>
                        <p class="mb-2">Save these recovery codes in a secure location:</p>
                        <div class="bg-light p-3 rounded">
                            <ul class="list-unstyled">
                                @foreach (json_decode(decrypt(Auth::user()->two_factor_recovery_codes), true) as $code)
                                    <li class="font-mono text-sm">{{ $code }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <form method="POST" action="{{ route('two-factor.recovery-codes') }}" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm">Regenerate Recovery Codes</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Password Confirmation Modal for 2FA -->
<div class="modal fade" id="confirmTwoFactorModal" tabindex="-1" aria-labelledby="confirmTwoFactorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.two-factor.enable') }}" id="two-factor-form">
                @csrf
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
            <form method="POST" action="{{ route('password.update') }}" id="set-password-form">
                @csrf
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
@endsection

@section('scripts')

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
    @if(session('show_security_tab') || $errors->has('current_password') || $errors->has('new_password') || session('security_error') || session('from_2fa'))
        $('#profileTab a[href="#security"]').tab('show');
        tabSet = true;
    @elseif(session('profile_status') || $errors->has('name') || $errors->has('email') || $errors->has('photo'))
        $('#profileTab a[href="#profile"]').tab('show');
        tabSet = true;
    @endif

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
@endsection