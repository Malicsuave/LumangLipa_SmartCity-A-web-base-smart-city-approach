@extends('layouts.public.master')

@section('title', $announcement->title)

@section('content')
<!-- Hero Section -->
<section class="position-relative" style="background: #eaf4fb; padding-top: 6rem; margin-top: -20px;">
    <div class="container py-4">
        <div class="text-center mb-4">
            <div class="d-flex justify-content-center mb-3">
                @php
                    $statusColors = [
                        'active' => 'success',
                        'upcoming' => 'info', 
                        'full' => 'warning',
                        'expired' => 'secondary'
                    ];
                    $color = $statusColors[$announcement->status] ?? 'secondary';
                @endphp
                <span class="badge bg-gradient-{{ $color }} me-2">{{ ucfirst($announcement->status) }}</span>
                <span class="badge bg-gradient-{{ $announcement->type === 'limited_slots' ? 'warning' : 'info' }}">
                    {{ $announcement->type_display }}
                </span>
            </div>
            <h1 class="fw-bold mb-2" style="color: #2A7BC4; font-size: 2.2rem;">{{ $announcement->title }}</h1>
            <p class="text-muted" style="font-size: 1rem;">
                @if($announcement->start_date)
                    {{ $announcement->start_date->format('F d, Y') }}
                @else
                    Posted {{ $announcement->created_at->format('F d, Y') }}
                @endif
            </p>
        </div>
    </div>
</section>

<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n4" style="border-radius: 18px;">
    <div class="container py-4">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                @if($announcement->image)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $announcement->image) }}" 
                             alt="{{ $announcement->title }}" 
                             class="img-fluid rounded"
                             style="width: 100%; max-height: 400px; object-fit: cover;">
                    </div>
                @endif
                
                <div class="announcement-content">
                    <h3>About This {{ $announcement->type_display }}</h3>
                    <div class="content-text" style="line-height: 1.8; font-size: 1.1rem;">
                        {!! nl2br(e($announcement->content)) !!}
                    </div>
                </div>
                
                @if($announcement->additional_info && is_array($announcement->additional_info) && count($announcement->additional_info) > 0)
                    <div class="mt-4">
                        <h4>Additional Information</h4>
                        <div class="bg-light p-3 rounded">
                            @foreach($announcement->additional_info as $key => $value)
                                <div class="mb-2">
                                    <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Sidebar -->
            <div class="col-lg-4">
                @if($announcement->has_slots)
                    <!-- Registration Card -->
                    <div class="card border" style="border-color: #2A7BC4 !important;">
                        <div class="card-header" style="background-color: #2A7BC4; color: white;">
                            <h5 class="mb-0">
                                <i class="fas fa-users"></i> Registration
                            </h5>
                        </div>
                        <div class="card-body">
                            <!-- Slots Info -->
                            <div class="text-center mb-4">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="h4 text-primary mb-0">{{ $announcement->current_slots }}</div>
                                        <small class="text-muted">Registered</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="h4 text-warning mb-0">{{ $announcement->available_slots }}</div>
                                        <small class="text-muted">Available</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="h4 text-info mb-0">{{ $announcement->max_slots }}</div>
                                        <small class="text-muted">Total</small>
                                    </div>
                                </div>
                                <div class="progress mt-3" style="height: 8px;">
                                    @php
                                        $percentage = $announcement->max_slots > 0 ? 
                                            ($announcement->current_slots / $announcement->max_slots) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-gradient-{{ $announcement->is_full ? 'danger' : 'primary' }}" 
                                         style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                            
                            @if($announcement->canRegister())
                                <!-- Registration Form -->
                                <form action="{{ route('announcements.register', $announcement) }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                               name="phone" value="{{ old('phone') }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <button type="submit" class="btn bg-gradient-primary w-100">
                                        <i class="fas fa-user-plus"></i> Register Now
                                    </button>
                                </form>
                            @elseif($announcement->is_full)
                                <div class="alert alert-warning text-center">
                                    <i class="fas fa-exclamation-triangle"></i><br>
                                    <strong>Registration Full</strong><br>
                                    All slots have been taken.
                                </div>
                            @elseif($announcement->status === 'expired')
                                <div class="alert alert-secondary text-center">
                                    <i class="fas fa-clock"></i><br>
                                    <strong>Registration Closed</strong><br>
                                    Registration period has ended.
                                </div>
                            @else
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle"></i><br>
                                    <strong>Registration Not Available</strong><br>
                                    Registration is currently not open.
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                
                <!-- Event Details -->
                <div class="card border mt-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Event Details</h6>
                    </div>
                    <div class="card-body">
                        @if($announcement->start_date)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Start Date:</span>
                                <span>{{ $announcement->start_date->format('M d, Y') }}</span>
                            </div>
                        @endif
                        
                        @if($announcement->end_date)
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">End Date:</span>
                                <span>{{ $announcement->end_date->format('M d, Y') }}</span>
                            </div>
                        @endif
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Category:</span>
                            <span>{{ $announcement->type_display }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Posted:</span>
                            <span>{{ $announcement->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Check Registration Status -->
                @if($announcement->has_slots)
                    <div class="card border mt-4">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-search"></i> Check Registration</h6>
                        </div>
                        <div class="card-body">
                            <form id="checkRegistrationForm">
                                <div class="form-group mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="checkEmail" required>
                                </div>
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    Check Status
                                </button>
                            </form>
                            <div id="registrationResult" class="mt-3" style="display: none;"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Navigation -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('public.announcements') }}" class="btn bg-gradient-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Announcements
                    </a>
                    <a href="{{ route('public.home') }}" class="btn bg-gradient-primary">
                        <i class="fas fa-home"></i> Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@if($announcement->has_slots)
<script>
document.getElementById('checkRegistrationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = document.getElementById('checkEmail').value;
    const resultDiv = document.getElementById('registrationResult');
    
    fetch('{{ route("announcements.check-registration") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            email: email,
            announcement_id: {{ $announcement->id }}
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.registered) {
            resultDiv.innerHTML = `
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> 
                    You are registered! Registration ID: #${data.registration_id}
                    <br><small>Registered on: ${data.registered_at}</small>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    No registration found for this email address.
                </div>
            `;
        }
        resultDiv.style.display = 'block';
    })
    .catch(error => {
        resultDiv.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> 
                Error checking registration status.
            </div>
        `;
        resultDiv.style.display = 'block';
    });
});
</script>
@endif
@endsection