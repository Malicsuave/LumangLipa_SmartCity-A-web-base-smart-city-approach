@extends('layouts.admin.master')

@section('title', 'Add New Admin Approval')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <div class="row align-items-center mb-4">
                    <div class="col">
                        <h2 class="h5 page-title">Add New Admin Approval</h2>
                        <p class="text-muted">Pre-approve a Gmail account for admin access</p>
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
                        <strong class="card-title">New Admin Approval</strong>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.approvals.store') }}">
                            @csrf
                            
                            <div class="form-group row">
                                <label for="email" class="col-md-3 col-form-label">Gmail Email Address <span class="text-danger">*</span></label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" 
                                           placeholder="example@gmail.com" required>
                                    <small class="form-text text-muted">
                                        Enter the Gmail address that will be authorized for admin access.
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
                                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }} - {{ $role->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        Select the role this Gmail account should have when they sign in.
                                    </small>
                                    @error('role_id')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="notes" class="col-md-3 col-form-label">Notes</label>
                                <div class="col-md-9">
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
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
                            
                            <div class="alert alert-info">
                                <div class="d-flex">
                                    <div class="pr-3">
                                        <i class="fe fe-info fe-24"></i>
                                    </div>
                                    <div>
                                        <h5>Important Security Information</h5>
                                        <p>
                                            By approving this Gmail account, you are granting administrative access to the system.
                                            The approved user will be able to sign in with their Gmail account and access features
                                            based on their assigned role. Only approve trusted individuals who require admin access.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row mb-0">
                                <div class="col-md-9 offset-md-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fe fe-check fe-16 mr-2"></i>
                                        Create Admin Approval
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