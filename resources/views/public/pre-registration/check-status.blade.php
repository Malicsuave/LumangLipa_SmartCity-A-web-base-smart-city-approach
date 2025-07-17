@extends('layouts.public')

@section('title', 'Check Registration Status - Barangay Lumanglipa')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fe fe-search"></i> Check Registration Status</h4>
                    <p class="mb-0 mt-2">Enter your email to check your pre-registration status</p>
                </div>
                
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger">
                            <i class="fe fe-alert-circle"></i> {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('public.pre-registration.check-status.post') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fe fe-search"></i> Check Status
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-muted">Don't have a registration yet?</p>
                <a href="{{ route('public.pre-registration.step1') }}" class="btn btn-outline-primary">
                    <i class="fe fe-user-plus"></i> Register Now
                </a>
            </div>
        </div>
    </div>
</div>
@endsection