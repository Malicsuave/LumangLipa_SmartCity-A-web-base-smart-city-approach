@extends('layouts.admin.master')

@section('title', 'Edit Admin Approval')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="row align-items-center mb-4">
                    <div class="col">
                        <h2 class="h5 page-title">Edit Admin Approval</h2>
                        <p class="text-muted">Modify Gmail account authorization for {{ $approval->email }}</p>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('admin.approvals.index') }}" class="btn btn-secondary">
                            <i class="fe fe-arrow-left fe-16 mr-2"></i>
                            Back to List
                        </a>
                    </div>
                </div>
                
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <strong class="card-title">Edit Admin Approval</strong>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.approvals.update', $approval->id) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group row">
                                <label for="email" class="col-md-3 col-form-label">Gmail Email Address <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $approval->email) }}" 
                                           placeholder="example@gmail.com" required>
                                    <small class="form-text text-muted">
                                        The Gmail address that is authorized for admin access.
                                    </small>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="role_id" class="col-md-3 col-form-label">Admin Role <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <select class="form-control @error('role_id') is-invalid @enderror" 
                                            id="role_id" name="role_id" required>
                                        <option value="">-- Select Role --</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ (old('role_id', $approval->role_id) == $role->id) ? 'selected' : '' }}>
                                                {{ $role->name }} - {{ $role->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        The role that determines what features this admin can access.
                                    </small>
                                    @error('role_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="is_active" class="col-md-3 col-form-label">Status <span class="text-danger">*</span></label>
                                <div class="col-md-9 pt-2">
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input" 
                                               id="is_active" name="is_active" value="1" 
                                               {{ (old('is_active', $approval->is_active) ? 'checked' : '') }}>
                                        <label class="custom-control-label" for="is_active">
                                            Active
                                        </label>
                                        <input type="hidden" name="is_active" value="0">
                                    </div>
                                    <small class="form-text text-muted">
                                        Inactive accounts cannot access the admin dashboard.
                                    </small>
                                    @error('is_active')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="notes" class="col-md-3 col-form-label">Notes</label>
                                <div class="col-md-9">
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3">{{ old('notes', $approval->notes) }}</textarea>
                                    <small class="form-text text-muted">
                                        Optional notes about this admin account (position, reason for access, etc.)
                                    </small>
                                    @error('notes')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <hr class="my-4">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <h6 class="text-muted mb-2">Admin Information</h6>
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <th class="pl-0" style="width: 40%">Last Approved By:</th>
                                                <td>{{ $approval->approved_by ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="pl-0">Last Approved Date:</th>
                                                <td>{{ $approval->approved_at ? $approval->approved_at->format('M d, Y H:i') : 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <th class="pl-0">Created:</th>
                                                <td>{{ $approval->created_at->format('M d, Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-warning mb-4">
                                        <div class="d-flex">
                                            <div class="pr-3">
                                                <i class="fe fe-alert-triangle fe-24"></i>
                                            </div>
                                            <div>
                                                <h5>Security Warning</h5>
                                                <p class="mb-0">
                                                    Changing role permissions or deactivating an account will take effect the next time the user signs in.
                                                    If an admin is currently online, consider asking them to log out.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row mb-0">
                                <div class="col-md-9 offset-md-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fe fe-save fe-16 mr-2"></i>
                                        Update Admin Approval
                                    </button>
                                    <a href="{{ route('admin.approvals.index') }}" class="btn btn-link">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    // Fix for the hidden input issue with checkboxes
    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('is_active');
        const hiddenInput = document.querySelector('input[type="hidden"][name="is_active"]');
        
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                hiddenInput.disabled = true;
            } else {
                hiddenInput.disabled = false;
            }
        });
        
        // Initialize on page load
        if (checkbox.checked) {
            hiddenInput.disabled = true;
        }
    });
</script>
@endpush