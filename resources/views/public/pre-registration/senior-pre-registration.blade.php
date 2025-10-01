@extends('layouts.public.master')

@section('title', 'Senior Citizen Pre-Registration')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/material-kit/css/material-kit.min.css') }}">
@endpush

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="material-icons me-2">person</i> Senior Citizen Pre-Registration</h4>
                    <span class="badge bg-light text-primary">Step 1 of 5</span>
                </div>
                <form action="{{ route('public.senior-pre-registration.store') }}" method="POST" id="seniorPreRegForm">
                    @csrf
                    <div class="card-body">
                        <!-- Type of Resident -->
                        <div class="mb-4">
                            <label for="type_of_resident" class="form-label">Type of Resident <span class="text-danger">*</span></label>
                            <select class="form-select @error('type_of_resident') is-invalid @enderror" id="type_of_resident" name="type_of_resident" required>
                                <option value="">Select type of resident</option>
                                <option value="Non-Migrant" {{ old('type_of_resident') == 'Non-Migrant' ? 'selected' : '' }}>Non-Migrant</option>
                                <option value="Migrant" {{ old('type_of_resident') == 'Migrant' ? 'selected' : '' }}>Migrant</option>
                                <option value="Transient" {{ old('type_of_resident') == 'Transient' ? 'selected' : '' }}>Transient</option>
                            </select>
                            @error('type_of_resident')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <!-- Name Fields -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="Enter first name" required>
                                @error('first_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" class="form-control @error('middle_name') is-invalid @enderror" id="middle_name" name="middle_name" value="{{ old('middle_name') }}" placeholder="Enter middle name (optional)">
                                @error('middle_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <!-- Add more fields as needed, following the admin registration structure -->
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Next <i class="material-icons ms-2">arrow_forward</i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
