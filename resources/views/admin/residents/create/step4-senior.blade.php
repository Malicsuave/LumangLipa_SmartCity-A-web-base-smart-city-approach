
@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.senior.index') }}">Senior Citizens</a></li>
<li class="breadcrumb-item active" aria-current="page">Register Senior Citizen</li>
@endsection

@section('page-title', 'Register Senior Citizen')
@section('page-subtitle', 'Standalone Senior Citizen Registration')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="fe fe-user fe-16 mr-2"></i>Senior Citizen Registration
                </h4>
                <p class="text-muted mb-0">Fill out the form below to register a senior citizen.</p>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.senior.store') }}" method="POST">
                    @csrf
                    <!-- Personal Information -->
                    <div class="form-group">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                        @error('full_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="birthdate" class="form-label">Birthdate</label>
                        <input type="date" class="form-control @error('birthdate') is-invalid @enderror" id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
                        @error('birthdate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" required>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Health Information -->
                    <div class="form-group">
                        <label for="health_conditions" class="form-label">Health Conditions</label>
                        <textarea class="form-control @error('health_conditions') is-invalid @enderror" id="health_conditions" name="health_conditions" rows="2">{{ old('health_conditions') }}</textarea>
                        @error('health_conditions')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Benefits & Programs -->
                    <div class="form-group">
                        <label for="benefits" class="form-label">Benefits</label>
                        <input type="text" class="form-control @error('benefits') is-invalid @enderror" id="benefits" name="benefits" value="{{ old('benefits') }}">
                        @error('benefits')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="programs" class="form-label">Programs Enrolled</label>
                        <input type="text" class="form-control @error('programs') is-invalid @enderror" id="programs" name="programs" value="{{ old('programs') }}">
                        @error('programs')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Emergency Contact -->
                    <div class="form-group">
                        <label for="emergency_contact" class="form-label">Emergency Contact</label>
                        <input type="text" class="form-control @error('emergency_contact') is-invalid @enderror" id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact') }}">
                        @error('emergency_contact')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Additional Notes -->
                    <div class="form-group">
                        <label for="notes" class="form-label">Additional Notes</label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Register Senior Citizen</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(session('error'))
<script>
    window.addEventListener('DOMContentLoaded', function() {
        alert('Error: {{ session('error') }}');
    });
</script>
@endif
@endsection