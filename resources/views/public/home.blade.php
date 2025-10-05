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
            $feature = [
                'title' => 'Scheduled Power Interruption Advisory',
                'excerpt' => 'Magkakaroon ng pansamantalang pagkawala ng kuryente sa ilang purok para sa line maintenance ng BATELEC II.',
                'date' => 'Feb 18, 2025',
                'category' => 'Advisory',
                'cta' => 'Read Advisory',
                'image' => 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?auto=format&fit=crop&w=800&q=60'
            ];
            $announcements = [
                [
                    'title' => 'Free Medical & Dental Mission',
                    'excerpt' => 'Libreng konsultasyon, basic dental care at BP monitoring. First come, first served.',
                    'date' => 'Feb 22, 2025',
                    'category' => 'Health',
                    'image' => 'https://images.unsplash.com/photo-1580281658629-149f02f32b25?auto=format&fit=crop&w=800&q=60'
                ],
                [
                    'title' => 'Linggo ng Kabataan Sports Clinic',
                    'excerpt' => 'Open registration para sa basketball, volleyball at chess training sessions.',
                    'date' => 'Feb 20, 2025',
                    'category' => 'Youth',
                    'image' => 'https://images.unsplash.com/photo-1521412644187-c49fa049e84d?auto=format&fit=crop&w=800&q=60'
                ],
                [
                    'title' => 'Barangay Clean-Up & Waste Segregation Drive',
                    'excerpt' => 'Dalhin ang sariling gloves at supot. Focus: drainage clearing & plastic recovery.',
                    'date' => 'Feb 19, 2025',
                    'category' => 'Environment',
                    'image' => 'https://images.unsplash.com/photo-1503596476-1c12a8ba09a8?auto=format&fit=crop&w=800&q=60'
                ],
                [
                    'title' => 'Monthly Barangay Assembly',
                    'excerpt' => 'Pag-uusapan ang proposed livelihood projects at infra status reports.',
                    'date' => 'Feb 25, 2025',
                    'category' => 'Assembly',
                    'image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=60'
                ],
            ];
        @endphp
        <div class="row g-4">
            <!-- Feature Card -->
            <div class="col-lg-5">
                <div class="rotating-card-container">
                    <div class="card card-rotate card-background card-background-mask-primary shadow-dark mt-md-0 mt-5">
                        <div class="front front-background" style="background-image: url({{ $feature['image'] }}); background-size: cover; position:relative;">
                            <span class="badge bg-secondary" style="position:absolute; top:10px; left:10px; background:#2A7BC4; color:#fff; z-index:2;">{{ $feature['category'] }}</span>
                            <div class="card-body py-7 text-center">
                                <i class="material-symbols-rounded text-white text-4xl my-3">campaign</i>
                                <h3 class="text-white">{{ $feature['title'] }}</h3>
                                <p class="text-white opacity-8">{{ $feature['excerpt'] }}</p>
                            </div>
                        </div>
                       <div class="back back-background" style="background: #2A7BC4;">
    <div class="card-body pt-7 text-center">
        <h3 class="text-white">Read Advisory</h3>
        <p class="text-white opacity-8">{{ $feature['date'] }} &bull; {{ $feature['category'] }}</p>
        <a href="#" class="btn btn-white btn-sm w-50 mx-auto mt-3" style="color:#2A7BC4;">Read Full</a>
    </div>
</div>
                    </div>
                </div>
            </div>
            <!-- List / Grid of other announcements -->
            <div class="col-lg-7">
                <div class="row g-4">
                    @foreach($announcements as $a)
                        <div class="col-md-6">
                            <div class="card border-0 h-100" style="border:1.5px solid #2A7BC4 !important; border-radius:18px; overflow:hidden; background:#ffffff;">
                                <div style="position:relative;">
                                    <img src="{{ $a['image'] }}" alt="{{ $a['category'] }}" style="width:100%; height:140px; object-fit:cover;">
                                    <span class="badge bg-secondary" style="position:absolute; top:10px; left:10px; background:#2A7BC4;">{{ $a['category'] }}</span>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h6 class="fw-semibold mb-2" style="line-height:1.25; color:#1e293b;">{{ $a['title'] }}</h6>
                                    <p class="text-muted small flex-grow-1 mb-2" style="line-height:1.4;">{{ $a['excerpt'] }}</p>
                                    <div class="d-flex align-items-center justify-content-between mt-auto pt-1">
                                        <span class="text-muted small"><i class="far fa-clock me-1"></i>{{ $a['date'] }}</span>
                                        <a href="#" class="btn btn-sm" style="border-radius:8px; font-weight:600; font-size:.65rem; letter-spacing:.5px; background-color:#2A7BC4; color:#fff; border:none;">Read Advisory</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
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
                    <div class="official-card staff-card" style="max-width: 220px;">
                        <div style="position:relative; background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); height: 170px; display: flex; align-items: center; justify-content: center; border-radius:15px 15px 0 0;">
                            @if($secretary->profile_pic_url)
                                <img src="{{ $secretary->profile_pic_url }}" alt="{{ $secretary->name }}" style="width:110px; height:110px; object-fit:cover; border-radius:50%; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                            @else
                                <div style="width:110px; height:110px; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                    <span style="font-size:2.2rem; font-weight:bold; color:#2A7BC4;">{{ $secretary->initials }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-body text-center p-3" style="background:#fff; border-radius:0 0 15px 15px; border:2px solid #2A7BC4; border-top:none;">
                            <h6 class="fw-bold mb-1" style="color:#1e293b; font-size:1rem;">{{ $secretary->name }}</h6>
                            <p class="text-muted mb-0" style="font-size:0.85rem; font-weight:600;">Secretary</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($treasurer)
                <div class="col-lg-4 col-md-6 d-flex justify-content-center">
                    <div class="official-card staff-card" style="max-width: 220px;">
                        <div style="position:relative; background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); height: 170px; display: flex; align-items: center; justify-content: center; border-radius:15px 15px 0 0;">
                            @if($treasurer->profile_pic_url)
                                <img src="{{ $treasurer->profile_pic_url }}" alt="{{ $treasurer->name }}" style="width:110px; height:110px; object-fit:cover; border-radius:50%; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                            @else
                                <div style="width:110px; height:110px; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                    <span style="font-size:2.2rem; font-weight:bold; color:#2A7BC4;">{{ $treasurer->initials }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-body text-center p-3" style="background:#fff; border-radius:0 0 15px 15px; border:2px solid #2A7BC4; border-top:none;">
                            <h6 class="fw-bold mb-1" style="color:#1e293b; font-size:1rem;">{{ $treasurer->name }}</h6>
                            <p class="text-muted mb-0" style="font-size:0.85rem; font-weight:600;">Treasurer</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($skChairman)
                <div class="col-lg-4 col-md-6 d-flex justify-content-center">
                    <div class="official-card staff-card" style="max-width: 220px;">
                        <div style="position:relative; background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); height: 170px; display: flex; align-items: center; justify-content: center; border-radius:15px 15px 0 0;">
                            @if($skChairman->profile_pic_url)
                                <img src="{{ $skChairman->profile_pic_url }}" alt="{{ $skChairman->name }}" style="width:110px; height:110px; object-fit:cover; border-radius:50%; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                            @else
                                <div style="width:110px; height:110px; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                    <span style="font-size:2.2rem; font-weight:bold; color:#2A7BC4;">{{ $skChairman->initials }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-body text-center p-3" style="background:#fff; border-radius:0 0 15px 15px; border:2px solid #2A7BC4; border-top:none;">
                            <h6 class="fw-bold mb-1" style="color:#1e293b; font-size:1rem;">HON. {{ $skChairman->name }}</h6>
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
