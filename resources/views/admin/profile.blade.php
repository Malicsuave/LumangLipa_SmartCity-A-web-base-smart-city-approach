@extends('layouts.admin.master')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/profile-page.css') }}">
@endsection

@section('content')
<div class="card shadow mb-4 profile-page">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary">Settings</h4>
    </div>
    <div class="card-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs mb-4" id="profileTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ !session('from_2fa') && !session('security_error') && (!$errors->any() || $errors->has('name') || $errors->has('email') || $errors->has('photo')) ? 'active' : '' }}" 
                   id="profile-tab" data-toggle="tab" href="#profile" role="tab" 
                   aria-controls="profile" 
                   aria-selected="{{ !session('from_2fa') && !session('security_error') && (!$errors->any() || $errors->has('name') || $errors->has('email') || $errors->has('photo')) ? 'true' : 'false' }}">
                    Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ session('from_2fa') || session('security_error') || ($errors->any() && !$errors->has('name') && !$errors->has('email') && !$errors->has('photo')) ? 'active' : '' }}" 
                   id="security-tab" data-toggle="tab" href="#security" role="tab" 
                   aria-controls="security" 
                   aria-selected="{{ session('from_2fa') || session('security_error') || ($errors->any() && !$errors->has('name') && !$errors->has('email') && !$errors->has('photo')) ? 'true' : 'false' }}">
                    Security
                </a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content" id="profileTabContent">
            <div class="tab-pane fade {{ !session('from_2fa') && !session('security_error') && (!$errors->any() || $errors->has('name') || $errors->has('email') || $errors->has('photo')) ? 'show active' : '' }}" 
                 id="profile" role="tabpanel" aria-labelledby="profile-tab">
                
                <!-- Flash Messages for Profile Tab -->
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
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
            </div>
            
            <div class="tab-pane fade {{ session('from_2fa') || session('security_error') || ($errors->any() && !$errors->has('name') && !$errors->has('email') && !$errors->has('photo')) ? 'show active' : '' }}" 
                 id="security" role="tabpanel" aria-labelledby="security-tab">
                <h4 class="mb-2">Security Settings</h4>
                <p class="text-muted mb-4">These settings help you keep your account secure.</p>

                <!-- Change Password Button -->
                <button type="button" class="btn btn-primary btn-sm mb-3" data-toggle="modal" data-target="#changePasswordModal">
                    Change Password
                </button>

                <!-- Change Password Modal -->
                <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="{{ route('admin.profile.password.update') }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="current_password">Current Password</label>
                                        <input type="password" class="form-control" id="current_password" name="current_password" required autocomplete="current-password">
                                    </div>
                                    <div class="form-group">
                                        <label for="new_password">New Password</label>
                                        <input type="password" class="form-control" id="new_password" name="new_password" required autocomplete="new-password">
                                    </div>
                                    <div class="form-group">
                                        <label for="new_password_confirmation">Confirm New Password</label>
                                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required autocomplete="new-password">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Change Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Flash Messages -->
                @if (session('status') && (session('from_2fa') || session('security_error')))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any() && !$errors->has('name') && !$errors->has('email') && !$errors->has('photo'))
                    <div class="alert alert-danger">
                        <ul>
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
                
                <!-- Google Authentication Notice -->
                <div class="list-group list-group-flush mb-4">
                    @if(Auth::user()->google_id && !Auth::user()->two_factor_secret)
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
                    @endif
                </div>

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
                            <!-- Modify the 2FA enable button to use password confirmation -->
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
                    
                    <!-- Display validation errors -->
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if($errors->has('password'))
                        <div class="alert alert-danger">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
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
    document.addEventListener('DOMContentLoaded', function() {
        // Show modal if there are password errors
        @if($errors->has('password') || session('error'))
            $('#confirmTwoFactorModal').modal('show');
        @endif
        
        // Show set password modal if there are password modal errors
        @if($errors->has('new_password') || session('from_password_modal'))
            $('#setPasswordModal').modal('show');
        @endif
    });

    // Preview profile photo before upload
    function previewPhoto(input) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePhotoPreview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
</script>
@endsection