@extends('layouts.admin.master')

@section('title', 'Edit Admin Approval')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h2 class="h5 page-title mb-0">Edit Admin Approval</h2>
                        <p class="text-muted mb-0">Modify Gmail account authorization for {{ $approval->email }}</p>
                    </div>
                    <div>
                        <a href="{{ route('admin.approvals.index') }}" class="btn btn-outline-secondary ml-2">
                            <i class="fe fe-arrow-left fe-16 mr-2"></i>
                            Back to List
                        </a>
                    </div>
                </div>
                
                <div class="card shadow">
                    <div class="card-body p-0">
                        <div class="row no-gutters">
                            <div class="col-lg-8">
                                <form method="POST" action="{{ route('admin.approvals.update', $approval->id) }}" class="p-4">
                                    @csrf
                                    @method('PUT')
                                    
                                    <div class="form-group">
                                        <label for="email">Gmail Email Address <span class="text-danger">*</span></label>
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

                                    <div class="form-group">
                                        <label for="role_id">Admin Role <span class="text-danger">*</span></label>
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
                                    
                                    <div class="form-group">
                                        <label for="is_active">Status <span class="text-danger">*</span></label>
                                        <div class="pt-1">
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
                                    
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea class="form-control @error('notes') is-invalid @enderror" 
                                                  id="notes" name="notes" rows="4">{{ old('notes', $approval->notes) }}</textarea>
                                        <small class="form-text text-muted">
                                            Optional notes about this admin account (position, reason for access, etc.)
                                        </small>
                                        @error('notes')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group mb-0">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fe fe-save fe-16 mr-2"></i>
                                            Update Admin Approval
                                        </button>
                                        <a href="{{ route('admin.approvals.index') }}" class="btn btn-link">Cancel</a>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="col-lg-4 bg-light">
                                <div class="p-4">
                                    <h6 class="border-bottom pb-2 mb-3">Admin Information</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th class="pl-0 text-muted" style="width: 50%">Last Approved By:</th>
                                            <td>{{ $approval->approved_by ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0 text-muted">Last Approved Date:</th>
                                            <td>{{ $approval->approved_at ? $approval->approved_at->format('M d, Y H:i') : 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th class="pl-0 text-muted">Created:</th>
                                            <td>{{ $approval->created_at->format('M d, Y H:i') }}</td>
                                        </tr>
                                    </table>
                                    
                                    <div class="alert alert-warning mt-4">
                                        <div class="d-flex">
                                            <div class="pr-3">
                                                <i class="fe fe-alert-triangle fe-16"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-1">Security Warning</h5>
                                                <p class="mb-0 small">
                                                    Changing role permissions or deactivating an account will take effect the next time the user signs in.
                                                    If an admin is currently online, consider asking them to log out.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
    });
</script>
@endpush