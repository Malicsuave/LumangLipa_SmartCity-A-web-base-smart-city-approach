@extends('layouts.public.master')

@section('title', $announcement->title)

@push('styles')
<style>
.announcement-detail {
    transition: all 0.3s ease;
}

.announcement-detail:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(42, 123, 196, 0.1);
}

.badge {
    backdrop-filter: blur(10px);
}

.progress {
    background-color: rgba(0,0,0,0.1);
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.announcement-detail {
    animation: slideInUp 0.6s ease-out;
}
</style>
@endpush

@section('content')
<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-4">
    <div class="container py-5">
        <!-- Back Navigation -->
        <div class="mb-4">
            <a href="{{ route('public.announcements') }}" class="btn text-white fw-bold d-inline-flex align-items-center shadow-sm" 
               style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); border: none; border-radius: 12px; padding: 10px 20px; font-size: 0.95rem; transition: all 0.3s ease;"
               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(42, 123, 196, 0.3)'"
               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)'">
                <i class="fas fa-arrow-left me-2"></i> Back to Announcements
            </a>
        </div>
        
        <!-- Enhanced Header Section -->
        <div class="text-center mb-5">
            <!-- Status Badges -->
            <div class="d-flex justify-content-center align-items-center flex-wrap mb-4">
                @php
                    $statusConfig = [
                        'active' => ['color' => '#2A7BC4', 'icon' => 'fas fa-check-circle'],
                        'upcoming' => ['color' => '#17a2b8', 'icon' => 'fas fa-clock'],
                        'full' => ['color' => '#ffc107', 'icon' => 'fas fa-users'],
                        'expired' => ['color' => '#6c757d', 'icon' => 'fas fa-calendar-times']
                    ];
                    $status = $statusConfig[$announcement->status] ?? $statusConfig['active'];
                    
                    $typeConfig = [
                        'event' => ['color' => '#FF6B6B', 'icon' => 'fas fa-calendar-check'],
                        'program' => ['color' => '#4ECDC4', 'icon' => 'fas fa-hands-helping'],
                        'health_related' => ['color' => '#FFA726', 'icon' => 'fas fa-user-md'],
                        'general' => ['color' => '#2A7BC4', 'icon' => 'fas fa-bullhorn'],
                        'service' => ['color' => '#9C27B0', 'icon' => 'fas fa-cogs']
                    ];
                    $type = $typeConfig[$announcement->type] ?? $typeConfig['general'];
                @endphp
                
                <div class="badge me-3 mb-2 px-3 py-2 d-flex align-items-center" 
                     style="background: #2A7BC4; color: white; border-radius: 20px; font-size: 0.9rem; font-weight: 600;">
                    <i class="{{ $status['icon'] }} me-2"></i>{{ ucfirst($announcement->status) }}
                </div>
                <div class="badge me-3 mb-2 px-3 py-2 d-flex align-items-center" 
                     style="background: #2A7BC4; color: white; border-radius: 20px; font-size: 0.9rem; font-weight: 600;">
                    <i class="{{ $type['icon'] }} me-2"></i>{{ $announcement->type_display }}
                </div>
                @if($announcement->has_slots)
                    <div class="badge mb-2 px-3 py-2 d-flex align-items-center" 
                         style="background: linear-gradient(135deg, #fd7e14, #e55100); color: white; border-radius: 20px; font-size: 0.9rem; font-weight: 600;">
                        <i class="fas fa-users me-2"></i>{{ $announcement->available_slots }}/{{ $announcement->max_slots }} Available
                    </div>
                @endif
            </div>
            
            <!-- Title -->
            <h1 class="fw-bold mb-4" style="color:#2A7BC4; font-size: 2.8rem; line-height: 1.2;">{{ $announcement->title }}</h1>
            
            <!-- Date Information -->
            <div class="d-flex justify-content-center align-items-center flex-wrap mb-4">
                @if($announcement->date)
                    <div class="me-4 mb-2 d-flex align-items-center">
                        <i class="fas fa-calendar-check me-2" style="color: #2A7BC4;"></i>
                        <span class="fw-semibold" style="color: #555;">Date: {{ $announcement->date->format('F d, Y') }}</span>
                        @if($announcement->time)
                            <span class="mx-2" style="color: #999;">at</span>
                            <span class="fw-semibold" style="color: #555;">{{ date('g:i A', strtotime($announcement->time)) }}</span>
                        @endif
                    </div>
                @endif
                <div class="me-4 mb-2 d-flex align-items-center">
                    <i class="fas fa-clock me-2" style="color: #FFD700;"></i>
                    <span class="fw-semibold" style="color: #555;">Posted {{ $announcement->created_at->format('M d, Y') }}</span>
                </div>
            </div>
            
            <!-- Decorative Line -->
            <div style="width: 100px; height: 4px; background: linear-gradient(90deg, #2A7BC4, #FFD700); margin: 0 auto; border-radius: 2px;"></div>
        </div>
        
        <!-- Main Content Grid -->
        <div class="row g-4">
            <!-- Left Content Column -->
            <div class="col-lg-8">
                <!-- Featured Image -->
                @if($announcement->image)
                    <div class="card border-0 shadow-lg mb-5" style="border-radius: 20px; overflow: hidden;">
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $announcement->image) }}" 
                                 alt="{{ $announcement->title }}" 
                                 class="img-fluid w-100"
                                 style="height: 400px; object-fit: cover;">
                            <div class="position-absolute top-0 start-0 w-100 h-100" 
                                 style="background: linear-gradient(45deg, rgba(42, 123, 196, 0.1) 0%, rgba(255, 215, 0, 0.1) 100%);"></div>
                        </div>
                    </div>
                @endif
                
                <!-- Main Description Card -->
                <div class="card border-0 shadow-lg mb-5" style="border-radius: 20px; overflow: hidden;">
                    <div class="card-header border-0 py-4" 
                         style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); color: white !important;">
                        <h3 class="mb-0 fw-bold d-flex align-items-center" style="color: white !important;">
                            <i class="fas fa-info-circle me-3" style="color: white !important;"></i>
                            About This {{ $announcement->type_display }}
                        </h3>
                    </div>
                    <div class="card-body p-5">
                        <div class="content-text" 
                             style="line-height: 2; font-size: 1.15rem; color: #444; text-align: justify;">
                            {!! nl2br(e($announcement->content)) !!}
                        </div>
                    </div>
                </div>
                
                <!-- Additional Information Card -->
                @if($announcement->additional_info && is_array($announcement->additional_info) && count($announcement->additional_info) > 0)
                    <div class="card border-0 shadow-lg mb-5" style="border-radius: 20px; overflow: hidden;">
                        <div class="card-header border-0 py-4" 
                             style="background: linear-gradient(135deg, #FFD700 0%, #FFA000 100%); color: #2A7BC4;">
                            <h4 class="mb-0 fw-bold d-flex align-items-center">
                                <i class="fas fa-list-ul me-3"></i>
                                Additional Information
                            </h4>
                        </div>
                        <div class="card-body p-5">
                            <div class="row g-3">
                                @foreach($announcement->additional_info as $key => $value)
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center p-3 h-100" 
                                             style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 15px; border-left: 4px solid #2A7BC4;">
                                            <div class="flex-grow-1">
                                                <div class="fw-bold mb-1" style="color: #2A7BC4; font-size: 0.9rem;">
                                                    {{ ucwords(str_replace('_', ' ', $key)) }}
                                                </div>
                                                <div style="color: #555; font-size: 1.05rem; font-weight: 500;">
                                                    {{ $value }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Right Sidebar -->
            <div class="col-lg-4">
                @if(!$announcement->has_slots && $announcement->type === 'health_related')
                    <!-- Appointment Only Card for Health Related -->
                    <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px; overflow: hidden;">
                        <div class="card-header border-0 py-4" 
                             style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); color: white !important;">
                            <h5 class="mb-0 fw-bold d-flex align-items-center" style="color: white !important;">
                                <i class="fas fa-user-md me-3" style="color: white !important;"></i>Health Services
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="text-center mb-4">
                                <i class="fas fa-stethoscope fa-3x mb-3" style="color: #2A7BC4;"></i>
                                <h6 class="fw-bold" style="color: #2A7BC4;">Book Your Health Appointment</h6>
                                <p class="text-muted mb-0">Schedule your health consultation or medical check-up</p>
                            </div>
                            
                            <a href="{{ url('/health/request') }}" 
                               class="btn w-100 py-3" 
                               style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); color: white; border: none; border-radius: 12px; font-weight: bold; font-size: 1.1rem; transition: all 0.3s ease; text-decoration: none;" 
                               onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(42, 123, 196, 0.3)'" 
                               onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                <i class="fas fa-calendar-plus me-2"></i> Make an Appointment
                            </a>
                        </div>
                    </div>
                @endif
                
                @if($announcement->has_slots)
                    <!-- Registration Status Card -->
                    <div class="card border-0 shadow-lg mb-4" style="border-radius: 20px; overflow: hidden;">
                        <div class="card-header border-0 py-4" 
                             style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); color: white !important;">
                            <h5 class="mb-0 fw-bold d-flex align-items-center" style="color: white !important;">
                                <i class="fas fa-users me-3" style="color: white !important;"></i>Registration Status
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <!-- Statistics Cards -->
                            <div class="row g-2 mb-4">
                                <div class="col-12">
                                    <div class="text-center p-4" 
                                         style="background: linear-gradient(135deg, #dc3545, #c82333); border-radius: 15px; color: white; box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);">
                                        <div class="h2 mb-1 fw-bold">{{ $announcement->current_slots }}</div>
                                        <div class="fw-semibold">Already Registered</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-3" 
                                         style="background: linear-gradient(135deg, #2A7BC4, #1e5f8c); border-radius: 15px; color: white; box-shadow: 0 4px 15px rgba(42, 123, 196, 0.3);">
                                        <div class="h4 mb-1 fw-bold">{{ $announcement->available_slots }}</div>
                                        <small class="fw-semibold">Available</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-3" 
                                         style="background: linear-gradient(135deg, #2A7BC4, #1e5f8c); border-radius: 15px; color: white; box-shadow: 0 4px 15px rgba(42, 123, 196, 0.3);">
                                        <div class="h4 mb-1 fw-bold">{{ $announcement->max_slots }}</div>
                                        <small class="fw-semibold">Total Slots</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-semibold text-muted">Registration Progress</span>
                                    @php
                                        $percentage = $announcement->max_slots > 0 ? 
                                            ($announcement->current_slots / $announcement->max_slots) * 100 : 0;
                                    @endphp
                                    <span class="fw-bold" style="color: #2A7BC4;">{{ round($percentage) }}%</span>
                                </div>
                                <div class="progress" style="height: 12px; border-radius: 10px; background: #e9ecef;">
                                    @php
                                        $progressColor = $announcement->is_full ? '#dc3545' : '#2A7BC4';
                                    @endphp
                                    <div class="progress-bar" 
                                         data-width="{{ $percentage }}"
                                         data-color="{{ $progressColor }}"
                                         style="border-radius: 10px; transition: all 0.3s ease;"></div>
                                </div>
                            </div>
                            
                            @if($announcement->canRegister())
                                <!-- Registration Form -->
                                <form action="{{ route('announcements.register', $announcement) }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               name="name" value="{{ old('name') }}" required
                                               style="border-radius: 10px; border: 2px solid #e9ecef; padding: 12px; transition: all 0.3s ease;"
                                               onfocus="this.style.borderColor='#2A7BC4'; this.style.boxShadow='0 0 10px rgba(42, 123, 196, 0.2)'"
                                               onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               name="email" value="{{ old('email') }}" required
                                               style="border-radius: 10px; border: 2px solid #e9ecef; padding: 12px; transition: all 0.3s ease;"
                                               onfocus="this.style.borderColor='#2A7BC4'; this.style.boxShadow='0 0 10px rgba(42, 123, 196, 0.2)'"
                                               onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group mb-4">
                                        <label class="form-label fw-semibold">Phone Number</label>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                               name="phone" value="{{ old('phone') }}"
                                               style="border-radius: 10px; border: 2px solid #e9ecef; padding: 12px; transition: all 0.3s ease;"
                                               onfocus="this.style.borderColor='#2A7BC4'; this.style.boxShadow='0 0 10px rgba(42, 123, 196, 0.2)'"
                                               onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <button type="submit" class="btn w-100 py-3 mb-3" 
                                            style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); color: white; border: none; border-radius: 12px; font-weight: bold; font-size: 1.1rem; transition: all 0.3s ease;" 
                                            onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(42, 123, 196, 0.3)'" 
                                            onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                        <i class="fas fa-user-plus me-2"></i> Register Now
                                    </button>
                                </form>
                                
                                <!-- Make an Appointment Button for Health Related -->
                                @if($announcement->type === 'health_related')
                                <a href="{{ url('/health/request') }}" 
                                   class="btn w-100 py-3" 
                                   style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); color: white; border: none; border-radius: 12px; font-weight: bold; font-size: 1.1rem; transition: all 0.3s ease; text-decoration: none;" 
                                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(42, 123, 196, 0.3)'" 
                                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-calendar-plus me-2"></i> Make an Appointment
                                </a>
                                @endif
                            @elseif($announcement->is_full)
                                <div class="alert text-center border-0 shadow-sm mb-3" style="background: linear-gradient(135deg, #ffc107, #e0a800); color: white; border-radius: 12px; padding: 25px;">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-3"></i><br>
                                    <strong style="font-size: 1.2rem;">Registration Full</strong><br>
                                    <span style="opacity: 0.9;">All slots have been taken.</span>
                                </div>
                                
                                <!-- Make an Appointment Button for Health Related -->
                                @if($announcement->type === 'health_related')
                                <a href="{{ url('/health/request') }}" 
                                   class="btn w-100 py-3" 
                                   style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); color: white; border: none; border-radius: 12px; font-weight: bold; font-size: 1.1rem; transition: all 0.3s ease; text-decoration: none;" 
                                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(42, 123, 196, 0.3)'" 
                                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-calendar-plus me-2"></i> Make an Appointment
                                </a>
                                @endif
                            @elseif($announcement->status === 'expired')
                                <div class="alert text-center border-0 shadow-sm mb-3" style="background: linear-gradient(135deg, #6c757d, #495057); color: white; border-radius: 12px; padding: 25px;">
                                    <i class="fas fa-clock fa-2x mb-3"></i><br>
                                    <strong style="font-size: 1.2rem;">Registration Closed</strong><br>
                                    <span style="opacity: 0.9;">This event has expired.</span>
                                </div>
                                
                                <!-- Make an Appointment Button for Health Related -->
                                @if($announcement->type === 'health_related')
                                <a href="{{ url('/health/request') }}" 
                                   class="btn w-100 py-3" 
                                   style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); color: white; border: none; border-radius: 12px; font-weight: bold; font-size: 1.1rem; transition: all 0.3s ease; text-decoration: none;" 
                                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(42, 123, 196, 0.3)'" 
                                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-calendar-plus me-2"></i> Make an Appointment
                                </a>
                                @endif
                            @else
                                <div class="alert text-center border-0 shadow-sm mb-3" style="background: linear-gradient(135deg, #17a2b8, #138496); color: white; border-radius: 12px; padding: 25px;">
                                    <i class="fas fa-info-circle fa-2x mb-3"></i><br>
                                    <strong style="font-size: 1.2rem;">Registration Not Available</strong><br>
                                    <span style="opacity: 0.9;">Registration is currently not open.</span>
                                </div>
                                
                                <!-- Make an Appointment Button for Health Related -->
                                @if($announcement->type === 'health_related')
                                <a href="{{ url('/health/request') }}" 
                                   class="btn w-100 py-3" 
                                   style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); color: white; border: none; border-radius: 12px; font-weight: bold; font-size: 1.1rem; transition: all 0.3s ease; text-decoration: none;" 
                                   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(42, 123, 196, 0.3)'" 
                                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-calendar-plus me-2"></i> Make an Appointment
                                </a>
                                @endif
                            @endif
                        </div>
                    </div>
                @endif
                
                <!-- Comprehensive Event/Appointment Details -->
                <div class="card border-0 mt-4" style="border-radius: 20px; overflow: hidden; box-shadow: 0 8px 30px rgba(42, 123, 196, 0.15);">
                    <div class="card-header border-0 py-4" 
                         style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); color: white !important;">
                        <h5 class="mb-0 fw-bold d-flex align-items-center" style="color: white !important;">
                            @if($announcement->type === 'health_related')
                                <i class="fas fa-user-md me-3" style="color: white !important;"></i>Appointment Information
                            @else
                                <i class="fas fa-calendar-alt me-3" style="color: white !important;"></i>Event Information
                            @endif
                        </h5>
                    </div>
                    <div class="card-body p-4" style="background: #f8f9fa;">
                        <div class="row g-3">
                            @if($announcement->date)
                                <div class="col-12">
                                    <div class="d-flex align-items-center p-3" 
                                         style="background: white; border-radius: 12px; border-left: 4px solid #2A7BC4; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                                        <div class="me-3">
                                            <i class="fas fa-calendar-check" style="color: #2A7BC4; font-size: 1.2rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold mb-1" style="color: #2A7BC4; font-size: 0.85rem;">DATE</div>
                                            <div class="fw-bold" style="color: #1e5f8c; font-size: 1.1rem;">{{ $announcement->date->format('F d, Y') }}</div>
                                        </div>
                                    </div>
                                </div>
                                @if($announcement->time)
                                    <div class="col-12">
                                        <div class="d-flex align-items-center p-3" 
                                             style="background: white; border-radius: 12px; border-left: 4px solid #007bff; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                                            <div class="me-3">
                                                <i class="fas fa-clock" style="color: #007bff; font-size: 1.2rem;"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-bold mb-1" style="color: #007bff; font-size: 0.85rem;">TIME</div>
                                                <div class="fw-bold" style="color: #1e5f8c; font-size: 1.1rem;">{{ date('g:i A', strtotime($announcement->time)) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            
                            <div class="col-12">
                                <div class="d-flex align-items-center p-3" 
                                     style="background: white; border-radius: 12px; border-left: 4px solid #5a9bd4; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                                    <div class="me-3">
                                        <i class="fas fa-tag" style="color: #5a9bd4; font-size: 1.2rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold mb-1" style="color: #5a9bd4; font-size: 0.85rem;">CATEGORY</div>
                                        <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #2A7BC4, #5a9bd4); color: white; font-size: 0.9rem; font-weight: bold;">{{ $announcement->type_display }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="d-flex align-items-center p-3" 
                                     style="background: white; border-radius: 12px; border-left: 4px solid #4a90c2; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                                    <div class="me-3">
                                        <i class="fas fa-clock" style="color: #4a90c2; font-size: 1.2rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fw-bold mb-1" style="color: #4a90c2; font-size: 0.85rem;">POSTED ON</div>
                                        <div class="fw-bold" style="color: #2A7BC4; font-size: 1.1rem;">{{ $announcement->created_at->format('F d, Y \a\t g:i A') }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($announcement->location)
                                <div class="col-12">
                                    <div class="d-flex align-items-center p-3" 
                                         style="background: white; border-radius: 12px; border-left: 4px solid #6ba3d6; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                                        <div class="me-3">
                                            <i class="fas fa-map-marker-alt" style="color: #6ba3d6; font-size: 1.2rem;"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-bold mb-1" style="color: #6ba3d6; font-size: 0.85rem;">LOCATION</div>
                                            <div class="fw-bold" style="color: #2A7BC4; font-size: 1.1rem;">{{ $announcement->location }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Check Registration Status -->
                @if($announcement->has_slots)
                    <div class="card border-0 shadow-lg mt-4" style="border-radius: 15px; overflow: hidden;">
                        <div class="card-header border-0" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white !important;">
                            <h6 class="mb-0 fw-bold" style="color: white !important;"><i class="fas fa-search me-2" style="color: white !important;"></i> Check Registration</h6>
                        </div>
                        <div class="card-body p-4">
                            <form id="checkRegistrationForm">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Email Address</label>
                                    <input type="email" class="form-control" id="checkEmail" required
                                           style="border-radius: 10px; border: 2px solid #e9ecef; padding: 12px; transition: all 0.3s ease;"
                                           onfocus="this.style.borderColor='#17a2b8'; this.style.boxShadow='0 0 10px rgba(23, 162, 184, 0.2)'"
                                           onblur="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'">
                                </div>
                                <button type="submit" class="btn w-100 py-3" 
                                        style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; border: none; border-radius: 10px; font-weight: bold; transition: all 0.3s ease;"
                                        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(23, 162, 184, 0.3)'"
                                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                                    <i class="fas fa-search me-2"></i> Check Status
                                </button>
                            </form>
                            <div id="registrationResult" class="mt-4" style="display: none;"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if($announcement->has_slots)
<script>
// Set progress bar width and color using data attributes
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.progress-bar[data-width]').forEach(function(bar) {
        bar.style.width = bar.getAttribute('data-width') + '%';
        if (bar.hasAttribute('data-color')) {
            bar.style.backgroundColor = bar.getAttribute('data-color');
        }
    });
});

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
            announcement_id: '{{ $announcement->id }}'
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