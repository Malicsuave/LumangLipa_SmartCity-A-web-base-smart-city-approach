@extends('layouts.public.master')
@section('title', 'Barangay Officials')
@section('content')
<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-4">
    <div class="container py-5">
        <!-- Page Header -->
        <div class="text-center mb-5">
            <h1 class="fw-bold mb-3" style="color:#2A7BC4; font-size: 2.5rem;">Barangay Officials</h1>
            <p class="text-muted" style="font-size:1.1rem;">Meet our complete team of dedicated public servants</p>
            <div style="width: 80px; height: 4px; background: linear-gradient(90deg, #2A7BC4, #FFD700); margin: 20px auto; border-radius: 2px;"></div>
        </div>

    <!-- Complete Family Tree Structure -->
    <div class="officials-hierarchy">
        @php
            use App\Models\Official;
            $officials = Official::getOrderedOfficials();
            $captain = $officials->where('position', 'Captain')->first();
            $councilors = $officials->where('position', 'Councilor');
            $secretary = $officials->where('position', 'Secretary')->first();
            $treasurer = $officials->where('position', 'Treasurer')->first();
            $skChairman = $officials->where('position', 'SK Chairman')->first();
        @endphp
        
        <!-- Barangay Captain (Top Level) -->
        @if($captain)
        <div class="captain-level mb-5">
            <div class="d-flex justify-content-center">
                <div class="official-card captain-card" style="width: 220px; min-height: 230px; display: flex; flex-direction: column;">
                    <div style="position:relative; background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); height: 170px; display: flex; align-items: center; justify-content: center; border-radius:15px 15px 0 0; box-shadow: 0 4px 15px rgba(42, 123, 196, 0.3);">
                        @if($captain->profile_pic_url)
                            <img src="{{ $captain->profile_pic_url }}" alt="{{ $captain->name }}" style="width:110px; height:110px; object-fit:cover; border-radius:50%; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.3);">
                        @else
                            <div style="width:110px; height:110px; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.3);">
                                <span style="font-size:2.2rem; font-weight:bold; color:#2A7BC4;">{{ $captain->initials }}</span>
                            </div>
                        @endif
                        <span class="badge bg-dark" style="position:absolute; top:10px; right:10px; font-size:0.6rem; padding:4px 8px; border-radius:10px;">CAPTAIN</span>
                    </div>
                    <div class="card-body text-center p-3 d-flex flex-column justify-content-center" style="background:#fff; border-radius:0 0 15px 15px; border:2px solid #2A7BC4; border-top:none; box-shadow: 0 4px 15px rgba(42, 123, 196, 0.2); height: 80px; min-height: 80px;">
                        <h6 class="fw-bold mb-1" style="color:#1e293b; font-size:1rem; line-height:1.2;">HON. {{ $captain->name }}</h6>
                        <p class="text-muted mb-0" style="font-size:0.85rem; font-weight:600;">Barangay Captain</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Connecting Line -->
        <div class="d-flex justify-content-center mb-5">
            <div style="width:4px; height:50px; background: linear-gradient(to bottom, #FFD700, #2A7BC4); border-radius: 2px;"></div>
        </div>

        <!-- Second Level: Councilors -->
        @if($councilors->count() > 0)
        <div class="councilors-level mb-5">
            <div class="text-center mb-4">
                <h3 class="fw-bold" style="color:#2A7BC4; font-size:1.8rem;">Sangguniang Barangay</h3>
                <p class="text-muted">Legislative Body of the Barangay</p>
            </div>
            <div class="row g-4 justify-content-center">
                @foreach($councilors->take(7) as $index => $councilor)
                <div class="col-xl-auto col-lg-4 col-md-6 d-flex justify-content-center">
                    <div class="official-card councilor-card" style="width: 220px; min-height: 250px; display: flex; flex-direction: column;">
                        <div style="position:relative; background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); height: 170px; display: flex; align-items: center; justify-content: center; border-radius:15px 15px 0 0; box-shadow: 0 4px 15px rgba(42, 123, 196, 0.3);">
                            @if($councilor->profile_pic_url)
                                <img src="{{ $councilor->profile_pic_url }}" alt="{{ $councilor->name }}" style="width:110px; height:110px; object-fit:cover; border-radius:50%; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                            @else
                                <div style="width:110px; height:110px; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                    <span style="font-size:2.2rem; font-weight:bold; color:#2A7BC4;">{{ $councilor->initials }}</span>
                                </div>
                            @endif
                        </div>
                        <div class="card-body text-center p-3 d-flex flex-column justify-content-center" style="background:#fff; border-radius:0 0 15px 15px; border:2px solid #2A7BC4; border-top:none; box-shadow: 0 4px 15px rgba(42, 123, 196, 0.2); min-height: 80px;">
                            <h6 class="fw-bold mb-1" style="color:#1e293b; font-size:1rem; line-height:1.2;">HON. {{ $councilor->name }}</h6>
                            <p class="text-muted mb-0" style="font-size:0.85rem; font-weight:600;">Councilor</p>
                            @if($councilor->committee)
                                <p class="text-muted small mb-0 mt-1" style="font-size:0.7rem; line-height:1.2; background:#f8f9fa; padding:2px 4px; border-radius:4px;">{{ $councilor->committee }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Connecting Line -->
        <div class="d-flex justify-content-center mb-5">
            <div style="width:4px; height:40px; background: linear-gradient(to bottom, #2A7BC4, #28a745); border-radius: 2px;"></div>
        </div>

        <!-- Third Level: Support Staff -->
        <div class="support-level">
            <div class="text-center mb-4">
                <h3 class="fw-bold" style="color:#2A7BC4; font-size:1.8rem;">Barangay Support Staff</h3>
                <p class="text-muted">Administrative and Youth Leadership</p>
            </div>
            <div class="row g-4 justify-content-center">
                @if($secretary)
                <div class="col-lg-4 col-md-6 d-flex justify-content-center">
                    <div class="official-card support-card" style="width: 220px; min-height: 250px; display: flex; flex-direction: column;">
                        <div style="position:relative; background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); height: 170px; display: flex; align-items: center; justify-content: center; border-radius:15px 15px 0 0; box-shadow: 0 4px 15px rgba(42, 123, 196, 0.3);">
                            @if($secretary->profile_pic_url)
                                <img src="{{ $secretary->profile_pic_url }}" alt="{{ $secretary->name }}" style="width:110px; height:110px; object-fit:cover; border-radius:50%; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                            @else
                                <div style="width:110px; height:110px; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                    <span style="font-size:2.2rem; font-weight:bold; color:#2A7BC4;">{{ $secretary->initials }}</span>
                                </div>
                            @endif
                            <span class="badge bg-light text-dark" style="position:absolute; top:10px; right:10px; font-size:0.6rem; padding:4px 8px; border-radius:10px;">SEC</span>
                        </div>
                        <div class="card-body text-center p-3 d-flex flex-column justify-content-center" style="background:#fff; border-radius:0 0 15px 15px; border:2px solid #2A7BC4; border-top:none; box-shadow: 0 4px 15px rgba(42, 123, 196, 0.2); height: 80px; min-height: 80px;">
                            <h6 class="fw-bold mb-1" style="color:#1e293b; font-size:1rem; line-height:1.2;">{{ $secretary->name }}</h6>
                            <p class="text-muted mb-0" style="font-size:0.85rem; font-weight:600;">Secretary</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($treasurer)
                <div class="col-lg-4 col-md-6 d-flex justify-content-center">
                    <div class="official-card support-card" style="width: 220px; min-height: 250px; display: flex; flex-direction: column;">
                        <div style="position:relative; background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); height: 170px; display: flex; align-items: center; justify-content: center; border-radius:15px 15px 0 0; box-shadow: 0 4px 15px rgba(42, 123, 196, 0.3);">
                            @if($treasurer->profile_pic_url)
                                <img src="{{ $treasurer->profile_pic_url }}" alt="{{ $treasurer->name }}" style="width:110px; height:110px; object-fit:cover; border-radius:50%; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                            @else
                                <div style="width:110px; height:110px; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                    <span style="font-size:2.2rem; font-weight:bold; color:#2A7BC4;">{{ $treasurer->initials }}</span>
                                </div>
                            @endif
                            <span class="badge bg-light text-dark" style="position:absolute; top:10px; right:10px; font-size:0.6rem; padding:4px 8px; border-radius:10px;">TREAS</span>
                        </div>
                        <div class="card-body text-center p-3 d-flex flex-column justify-content-center" style="background:#fff; border-radius:0 0 15px 15px; border:2px solid #2A7BC4; border-top:none; box-shadow: 0 4px 15px rgba(42, 123, 196, 0.2); height: 80px; min-height: 80px;">
                            <h6 class="fw-bold mb-1" style="color:#1e293b; font-size:1rem; line-height:1.2;">{{ $treasurer->name }}</h6>
                            <p class="text-muted mb-0" style="font-size:0.85rem; font-weight:600;">Treasurer</p>
                        </div>
                    </div>
                </div>
                @endif

                @if($skChairman)
                <div class="col-lg-4 col-md-6 d-flex justify-content-center">
                    <div class="official-card support-card" style="width: 220px; min-height: 250px; display: flex; flex-direction: column;">
                        <div style="position:relative; background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); height: 170px; display: flex; align-items: center; justify-content: center; border-radius:15px 15px 0 0; box-shadow: 0 4px 15px rgba(42, 123, 196, 0.3);">
                            @if($skChairman->profile_pic_url)
                                <img src="{{ $skChairman->profile_pic_url }}" alt="{{ $skChairman->name }}" style="width:110px; height:110px; object-fit:cover; border-radius:50%; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                            @else
                                <div style="width:110px; height:110px; background:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; border: 3px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                                    <span style="font-size:2.2rem; font-weight:bold; color:#2A7BC4;">{{ $skChairman->initials }}</span>
                                </div>
                            @endif
                            <span class="badge bg-light text-dark" style="position:absolute; top:10px; right:10px; font-size:0.6rem; padding:4px 8px; border-radius:10px;">SK</span>
                        </div>
                        <div class="card-body text-center p-3 d-flex flex-column justify-content-center" style="background:#fff; border-radius:0 0 15px 15px; border:2px solid #2A7BC4; border-top:none; box-shadow: 0 4px 15px rgba(42, 123, 196, 0.2); height: 80px; min-height: 80px;">
                            <h6 class="fw-bold mb-1" style="color:#1e293b; font-size:1rem; line-height:1.2;">HON. {{ $skChairman->name }}</h6>
                            <p class="text-muted mb-0" style="font-size:0.85rem; font-weight:600;">SK Chairman</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="text-center mt-5 pt-5" style="border-top: 2px solid #f8f9fa;">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-4" style="background: linear-gradient(135deg, #2A7BC4, #1e5f8c); border-radius: 15px; color: white;">
                    <h5 class="fw-bold mb-2">🏛️ Barangay Hall</h5>
                    <p class="mb-0 small">Purok 1, Lumanglipa<br>Mataasnakahoy, Batangas</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4" style="background: linear-gradient(135deg, #2A7BC4, #1e5f8c); border-radius: 15px; color: white;">
                    <h5 class="fw-bold mb-2">🕐 Office Hours</h5>
                    <p class="mb-0 small">Monday - Friday: 8:00 AM - 5:00 PM<br>Saturday: 8:00 AM - 12:00 PM</p>
                </div>
            </div>
            <div class="col-md-4">
                               <div class="p-4" style="background: linear-gradient(135deg, #2A7BC4, #1e5f8c); border-radius: 15px; color: white;">
                    <h5 class="fw-bold mb-2">📞 Contact Info</h5>
                    <p class="mb-0 small">Email: barangaylumanglipa@gmail.com<br>Phone: (043) 123-4567</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
