@extends('layouts.admin.master')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h4 class="m-0 font-weight-bold text-primary">Settings</h4>
    </div>
    <div class="card-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs mb-4" id="profileTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link {{ !$errors->any() && !session('status') && !session('error') && !session('from_2fa') ? 'active' : '' }}" 
                   id="profile-tab" data-toggle="tab" href="#profile" role="tab" 
                   aria-controls="profile" 
                   aria-selected="{{ !$errors->any() && !session('status') && !session('error') && !session('from_2fa') ? 'true' : 'false' }}">
                    Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $errors->any() || session('status') || session('error') || session('from_2fa') ? 'active' : '' }}" 
                   id="security-tab" data-toggle="tab" href="#security" role="tab" 
                   aria-controls="security" 
                   aria-selected="{{ $errors->any() || session('status') || session('error') || session('from_2fa') ? 'true' : 'false' }}">
                    Security
                </a>
            </li>
        </ul>
        <!-- Tab panes -->
        <div class="tab-content" id="profileTabContent">
            <div class="tab-pane fade {{ !$errors->any() && !session('status') && !session('error') && !session('from_2fa') ? 'show active' : '' }}" 
                 id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img src="{{ Auth::user()->profile_photo_url }}" class="img-fluid rounded-circle mb-3" style="max-width: 150px;" alt="Profile Photo">
                    </div>
                    <div class="col-md-9">
                        <table class="table table-borderless">
                            <tr>
                                <th>Name:</th>
                                <td>{{ Auth::user()->name }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ Auth::user()->email }}</td>
                            </tr>
                            <tr>
                                <th>Role:</th>
                                <td>{{ Auth::user()->role->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Registered At:</th>
                                <td>{{ Auth::user()->created_at->format('F d, Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade {{ $errors->any() || session('status') || session('error') || session('from_2fa') ? 'show active' : '' }}" 
                 id="security" role="tabpanel" aria-labelledby="security-tab">
                <h4 class="mb-2">Security Settings</h4>
                <p class="text-muted mb-4">These settings help you keep your account secure.</p>

                <!-- Flash Messages -->
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
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
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show modal if there are password errors
        @if($errors->has('password') || session('error'))
            $('#confirmTwoFactorModal').modal('show');
        @endif
    });
</script>
@endsection