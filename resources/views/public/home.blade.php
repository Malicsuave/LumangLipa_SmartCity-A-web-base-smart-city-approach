@extends('layouts.public.master')

@section('title', 'Barangay Lumanglipa - Official Website')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/chatbot.css?v=2') }}">
@endpush

@section('content')
<!-- Hero Section with Full Image Background -->
<section class="hero-section position-relative" style="background: #eaf4fb;">
    <div class="hero-bg-image position-relative" style="width:100%; min-height:480px; background-image: url('/images/bglumanglipa.jpeg'); background-size:cover; background-position:center; border-radius:0; box-shadow:0 4px 24px rgba(42,123,196,0.08);">
        
        <div class="hero-overlay" style="position:absolute; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.45);"></div>
        <div class="container h-100 position-relative" style="z-index:2;">
            <div class="row justify-content-center align-items-center h-100" style="min-height:480px;">
                <div class="col-lg-8 col-xl-7 mx-auto">
                    <div class="hero-content text-center text-white" style="background:transparent; border-radius:18px; padding:2.5rem 2rem 2rem 2rem; box-shadow:none;">
                        <h1 class="hero-title mb-2" style="color:#fff; font-size: 2.8rem; font-weight: 800; line-height: 1.1; text-shadow:0 2px 12px rgba(0,0,0,0.18);">
                            Barangay Lumanglipa
                        </h1>
                        <p class="hero-location mb-3" style="color:#fff; font-size: 0.95rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; text-shadow:0 1px 8px rgba(0,0,0,0.18);">
                            📍 Mataas na Kahoy, Batangas
                        </p>
                        <p class="hero-description mb-4" style="font-size: 1.1rem; line-height: 1.6; color: #fff; font-weight: 400; text-shadow:0 1px 8px rgba(0,0,0,0.18);">
                            Your digital gateway to efficient government services. Experience seamless online transactions and stay connected with your community.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-n6">
    <div class="container">
        <div class="services-preview mb-4" style="margin-top:-60px; position:relative; z-index:2;">
            <div class="row g-2 justify-content-center">
                <div class="col-12 col-sm-6">
                    <div class="service-item p-3 bg-light rounded-3 h-100">
                        <div class="d-flex align-items-center">
                            <div class="service-icon-sm me-3" style="font-size: 1.6rem;">📄</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark" style="font-size: 0.9rem; line-height: 1.3;">Document Requests</div>
                                <div class="text-muted" style="font-size: 0.72rem; line-height: 1.2;">Clearance, Residency & More</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="service-item p-3 bg-light rounded-3 h-100">
                        <div class="d-flex align-items-center">
                            <div class="service-icon-sm me-3" style="font-size: 1.6rem;">🏥</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark" style="font-size: 0.9rem; line-height: 1.3;">Health Services</div>
                                <div class="text-muted" style="font-size: 0.72rem; line-height: 1.2;">Medical Assistance</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="service-item p-3 bg-light rounded-3 h-100">
                        <div class="d-flex align-items-center">
                            <div class="service-icon-sm me-3" style="font-size: 1.6rem;">📝</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark" style="font-size: 0.9rem; line-height: 1.3;">File Complaints</div>
                                <div class="text-muted" style="font-size: 0.72rem; line-height: 1.2;">24/7 Online System</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="service-item p-3 bg-light rounded-3 h-100">
                        <div class="d-flex align-items-center">
                            <div class="service-icon-sm me-3" style="font-size: 1.6rem;">🆔</div>
                            <div class="flex-grow-1">
                                <div class="fw-bold text-dark" style="font-size: 0.9rem; line-height: 1.3;">Resident Registration</div>
                                <div class="text-muted" style="font-size: 0.72rem; line-height: 1.2;">Barangay ID & More</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!-- Added space between service preview and recent announcements -->
<div style="height: 85px;"></div>
<div class="container">
    <!-- Recent Announcements Section (Styled like template) -->

    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 gap-2">
            <div>
                <h2 class="fw-bold mb-1" style="color:#2A7BC4; font-size: 2rem;">Recent Announcements</h2>
                <p class="text-muted mb-0" style="font-size:.9rem;">Stay updated with the latest barangay news and events</p>
            </div>
            <a href="{{ route('public.announcements') }}" class="btn btn-outline-primary btn-sm" style="border-radius:8px; background-color:#2A7BC4; color:#fff; border:none;">View All</a>
        </div>
        @php
            // Get announcements from database - split into feature and regular announcements
            $allAnnouncements = $announcements ?? collect();
            $feature = $allAnnouncements->first(); // Use first announcement as feature
            $regularAnnouncements = $allAnnouncements->skip(1)->take(4); // Take next 4 for grid
        @endphp
        <div class="row g-4">
            <!-- Feature Card -->
            @if($feature)
            <div class="col-lg-5">
                <div class="feature-announcement-card">
                    @if($feature->image)
                        @php
                            $featureImage = asset('storage/' . $feature->image);
                        @endphp
                        <div class="card shadow-lg" style="border-radius: 18px; overflow: hidden; min-height: 400px; background-image: url('{{ $featureImage }}'); background-size: cover; background-position: center; position: relative;">
                    @else
                        <div class="card shadow-lg" style="border-radius: 18px; overflow: hidden; min-height: 400px; background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%) !important; position: relative;">
                    @endif
                        <!-- Dark overlay for better text readability -->
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.3); z-index: 1;"></div>
                        
                        @php
                            $typeLabels = [
                                'general' => 'General',
                                'limited_slots' => 'Registration Required',
                                'event' => 'Event',
                                'service' => 'Service',
                                'program' => 'Program'
                            ];
                            $featureTypeLabel = $typeLabels[$feature->type] ?? ucfirst(str_replace('_', ' ', $feature->type));
                        @endphp
                        <span class="badge" style="position:absolute; top:20px; left:20px; background:#2A7BC4; color:#fff; z-index:3; font-size: 0.9rem; padding: 8px 12px;">{{ $featureTypeLabel }}</span>
                        
                        <div class="card-body d-flex flex-column justify-content-center text-center h-100" style="position: relative; z-index: 2; padding: 3rem 2rem;">
                            <i class="material-symbols-rounded text-white mb-3" style="font-size: 4rem;">campaign</i>
                            <h3 class="text-white mb-3 fw-bold">{{ $feature->title }}</h3>
                            <p class="text-white mb-4" style="opacity: 0.9; line-height: 1.6;">{{ \Str::limit(strip_tags($feature->content), 120) }}</p>
                            <div class="mt-auto">
                                <a href="{{ route('announcements.show', $feature) }}" class="btn btn-light px-4 py-2" style="border-radius: 25px; font-weight: 600;">
                                    Read More <i class="fas fa-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- Show placeholder when no feature announcement -->
            <div class="col-lg-5">
                <div class="card" style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); border-radius:18px; min-height: 300px;">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center text-white text-center">
                        <i class="material-symbols-rounded text-white text-4xl mb-3">announcement</i>
                        <h4 class="mb-3">No Announcements</h4>
                        <p class="mb-0">No active announcements at the moment. Check back later!</p>
                    </div>
                </div>
            </div>
            @endif
            <!-- List / Grid of other announcements -->
            <div class="col-lg-7">
                @if($regularAnnouncements->count() > 0)
                <div class="row g-4">
                    @foreach($regularAnnouncements as $announcement)
                        <div class="col-md-6">
                            <div class="card border-0 h-100" style="border:1.5px solid #2A7BC4 !important; border-radius:18px; overflow:hidden; background:#ffffff;">
                                @if($announcement->image)
                                <div style="position:relative;">
                                    <img src="{{ asset('storage/' . $announcement->image) }}" alt="{{ ucfirst($announcement->type) }}" style="width:100%; height:140px; object-fit:cover;">
                                    @php
                                        $typeLabels = [
                                            'general' => 'General',
                                            'limited_slots' => 'Registration Required',
                                            'event' => 'Event',
                                            'service' => 'Service',
                                            'program' => 'Program'
                                        ];
                                        $announcementTypeLabel = $typeLabels[$announcement->type] ?? ucfirst(str_replace('_', ' ', $announcement->type));
                                    @endphp
                                    <span class="badge" style="position:absolute; top:10px; left:10px; background:#2A7BC4 !important; color:#fff; border: none;">{{ $announcementTypeLabel }}</span>
                                </div>
                                @else
                                <div style="position:relative; background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%) !important; height:140px; display:flex; align-items:center; justify-content:center;">
                                    <i class="material-symbols-rounded text-white" style="font-size: 2.5rem;">announcement</i>
                                    @php
                                        $typeLabels = [
                                            'general' => 'General',
                                            'limited_slots' => 'Registration Required',
                                            'event' => 'Event',
                                            'service' => 'Service',
                                            'program' => 'Program'
                                        ];
                                        $announcementTypeLabel = $typeLabels[$announcement->type] ?? ucfirst(str_replace('_', ' ', $announcement->type));
                                    @endphp
                                    <span class="badge" style="position:absolute; top:10px; left:10px; background: rgba(255,255,255,0.2) !important; color:#fff; border: none;">{{ $announcementTypeLabel }}</span>
                                </div>
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h6 class="fw-semibold mb-2" style="line-height:1.25; color:#1e293b;">{{ $announcement->title }}</h6>
                                    <p class="text-muted small flex-grow-1 mb-2" style="line-height:1.4;">{{ \Str::limit(strip_tags($announcement->content), 80) }}</p>
                                    <div class="d-flex align-items-center justify-content-between mt-auto pt-1">
                                        <span class="text-muted small"><i class="far fa-clock me-1"></i>{{ $announcement->created_at->format('M d, Y') }}</span>
                                        <a href="{{ route('announcements.show', $announcement) }}" class="btn btn-sm" style="border-radius:8px; font-weight:600; font-size:.65rem; letter-spacing:.5px; background-color:#2A7BC4; color:#fff; border:none;">Read More</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @else
                <!-- When no other announcements available -->
                <div class="d-flex align-items-center justify-content-center h-100" style="min-height: 300px;">
                    <div class="text-center">
                        <i class="material-symbols-rounded text-muted mb-3" style="font-size: 3rem;">info</i>
                        <h5 class="text-muted mb-2">More announcements coming soon</h5>
                        <p class="text-muted mb-0">Stay tuned for updates from the barangay!</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Added space between announcements and officials -->
<div style="height: 85px;"></div>

<!-- Barangay Officials Section -->
<section class="container">
    <div class="d-flex flex-wrap justify-content-between align-items-end mb-4 gap-2">
        <div>
            <h2 class="fw-bold mb-1" style="color:#2A7BC4; font-size: 2rem;">Barangay Officials</h2>
            <p class="text-muted mb-0" style="font-size:.9rem;">Meet our key leaders</p>
        </div>
        <a href="{{ route('public.officials') }}" class="btn btn-outline-primary btn-sm" style="border-radius:8px; background-color:#2A7BC4; color:#fff; border:none;">View All Officials</a>
    </div>
    
    <!-- Key Officials Layout -->
    <div class="key-officials">
        @php
            $captain = $officials->where('position', 'Captain')->first();
            $secretary = $officials->where('position', 'Secretary')->first();
            $treasurer = $officials->where('position', 'Treasurer')->first();
            $skChairman = $officials->where('position', 'SK Chairman')->first();
        @endphp
        
        <!-- Barangay Captain (Center Top) -->
        @if($captain)
        <div class="captain-section mb-5">
            <div class="d-flex justify-content-center">
                <div class="official-card captain-card" style="max-width: 280px;">
                    <div style="position:relative; background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); height: 200px; display: flex; align-items: center; justify-content: center; border-radius:18px 18px 0 0;">
                        @if($captain->profile_pic_url)
                            <img src="{{ $captain->profile_pic_url }}" alt="{{ $captain->name }}" style="width:130px; height:130px; object-fit:cover; border-radius:50%; border: 4px solid #fff; box-shadow: 0 6px 12px rgba(0,0,0,0.3);">
                        @else
                            <div style="width:130px; height:130px; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; border: 4px solid #fff; box-shadow: 0 6px 12px rgba(0,0,0,0.3);">
                                <span style="font-size:2.8rem; font-weight:bold; color:#2A7BC4;">{{ $captain->initials }}</span>
                            </div>
                        @endif
                        <span class="badge bg-dark" style="position:absolute; top:15px; right:15px; font-size:0.75rem; padding:6px 12px;">BARANGAY CAPTAIN</span>
                    </div>
                    <div class="card-body text-center p-4" style="background:#fff; border-radius:0 0 18px 18px; border:2px solid #FFD700; border-top:none;">
                        <h5 class="fw-bold mb-1" style="color:#1e293b; font-size:1.1rem;">HON. {{ $captain->name }}</h5>
                        <p class="text-muted mb-0" style="font-size:0.9rem; font-weight:600;">Barangay Captain</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Key Staff (Bottom Row) -->
        <div class="key-staff-section">
            <div class="row g-4 justify-content-center">
                @if($secretary)
                <div class="col-lg-4 col-md-6 d-flex justify-content-center">
                    <div class="official-card staff-card" style="width: 220px; min-height: 250px; display: flex; flex-direction: column;">
                        <div style="position:relative; background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); height: 170px; display: flex; align-items: center; justify-content: center; border-radius:15px 15px 0 0;">
                            @if($secretary->profile_pic_url)
                                <img src="{{ $secretary->profile_pic_url }}" alt="{{ $secretary->name }}" style="width:110px; height:110px; object-fit:cover; border-radius:50%; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                            @else
                                <div style="width:110px; height:110px; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                    <span style="font-size:2.2rem; font-weight:bold; color:#2A7BC4;">{{ $secretary->initials }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-body text-center p-3 d-flex flex-column justify-content-center" style="background:#fff; border-radius:0 0 15px 15px; border:2px solid #2A7BC4; border-top:none; height: 80px; min-height: 80px;">
                            <h6 class="fw-bold mb-1" style="color:#1e293b; font-size:1rem; line-height: 1.2;">{{ $secretary->name }}</h6>
                            <p class="text-muted mb-0" style="font-size:0.85rem; font-weight:600;">Secretary</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($treasurer)
                <div class="col-lg-4 col-md-6 d-flex justify-content-center">
                    <div class="official-card staff-card" style="width: 220px; min-height: 250px; display: flex; flex-direction: column;">
                        <div style="position:relative; background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); height: 170px; display: flex; align-items: center; justify-content: center; border-radius:15px 15px 0 0;">
                            @if($treasurer->profile_pic_url)
                                <img src="{{ $treasurer->profile_pic_url }}" alt="{{ $treasurer->name }}" style="width:110px; height:110px; object-fit:cover; border-radius:50%; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                            @else
                                <div style="width:110px; height:110px; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                    <span style="font-size:2.2rem; font-weight:bold; color:#2A7BC4;">{{ $treasurer->initials }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-body text-center p-3 d-flex flex-column justify-content-center" style="background:#fff; border-radius:0 0 15px 15px; border:2px solid #2A7BC4; border-top:none; height: 80px; min-height: 80px;">
                            <h6 class="fw-bold mb-1" style="color:#1e293b; font-size:1rem; line-height: 1.2;">{{ $treasurer->name }}</h6>
                            <p class="text-muted mb-0" style="font-size:0.85rem; font-weight:600;">Treasurer</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($skChairman)
                <div class="col-lg-4 col-md-6 d-flex justify-content-center">
                    <div class="official-card staff-card" style="width: 220px; min-height: 250px; display: flex; flex-direction: column;">
                        <div style="position:relative; background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); height: 170px; display: flex; align-items: center; justify-content: center; border-radius:15px 15px 0 0;">
                            @if($skChairman->profile_pic_url)
                                <img src="{{ $skChairman->profile_pic_url }}" alt="{{ $skChairman->name }}" style="width:110px; height:110px; object-fit:cover; border-radius:50%; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                            @else
                                <div style="width:110px; height:110px; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                    <span style="font-size:2.2rem; font-weight:bold; color:#2A7BC4;">{{ $skChairman->initials }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-body text-center p-3 d-flex flex-column justify-content-center" style="background:#fff; border-radius:0 0 15px 15px; border:2px solid #2A7BC4; border-top:none; height: 80px; min-height: 80px;">
                            <h6 class="fw-bold mb-1" style="color:#1e293b; font-size:1rem; line-height: 1.2;">HON. {{ $skChairman->name }}</h6>
                            <p class="text-muted mb-0" style="font-size:0.85rem; font-weight:600;">SK Chairman</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
    
</div>











@endsection
