@extends('layouts.public.master')

@section('title', 'Announcements')

@section('content')
<div class="card card-body blur shadow-blur mx-3 mx-md-4 mt-4">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold mb-3" style="color:#2A7BC4; font-size: 2.5rem;">Barangay Announcements</h1>
            <p class="text-muted" style="font-size:1.1rem;">Stay updated with the latest barangay news, events, and programs</p>
            <div style="width: 80px; height: 4px; background: linear-gradient(90deg, #2A7BC4, #FFD700); margin: 20px auto; border-radius: 2px;"></div>
        </div>

        @if($announcements->count() > 0)
            <div class="row g-4">
                @foreach($announcements as $announcement)
                    <div class="col-lg-6 col-xl-4">
                        <div class="card h-100" style="border: none; border-radius: 20px; overflow: hidden; box-shadow: 0 5px 20px rgba(42, 123, 196, 0.1);">
                            <div class="position-relative" style="height: 220px; overflow: hidden;">
                                @if($announcement->image)
                                    <img src="{{ asset('storage/' . $announcement->image) }}" 
                                         alt="{{ $announcement->title }}" 
                                         class="card-img-top w-100 h-100" 
                                         style="object-fit: cover;">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center" 
                                         style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%);">
                                        <i class="fas fa-bullhorn text-white" style="font-size: 3rem; opacity: 0.8;"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="card-body d-flex flex-column p-4">
                                <h5 class="card-title fw-bold mb-3" style="color: #1e293b;">
                                    {{ $announcement->title }}
                                </h5>
                                
                                <p class="card-text text-muted mb-3" style="font-size: 0.9rem;">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($announcement->content), 120) }}
                                </p>
                                
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-2" style="color: #2A7BC4;"></i>
                                        @if($announcement->start_date)
                                            {{ $announcement->start_date->format('M d, Y') }}
                                        @else
                                            {{ $announcement->created_at->format('M d, Y') }}
                                        @endif
                                    </small>
                                </div>
                                
                                <div class="mt-auto">
                                    <a href="{{ route('announcements.show', $announcement) }}" 
                                       class="btn w-100 text-white fw-bold" 
                                       style="background: linear-gradient(135deg, #2A7BC4 0%, #1e5f8c 100%); border: none; border-radius: 12px; padding: 12px;">
                                        <i class="fas fa-eye me-2"></i>View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="d-flex justify-content-center mt-5">
                {{ $announcements->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 8px 25px rgba(0,0,0,0.1);">
                        <i class="fas fa-bullhorn" style="font-size: 3rem; color: #6c757d;"></i>
                    </div>
                </div>
                <h4 class="fw-bold mb-3" style="color: #1e293b;">No Announcements Available</h4>
                <p class="text-muted" style="font-size: 1.1rem;">
                    There are currently no active announcements. Please check back later for updates.
                </p>
            </div>
        @endif
    </div>
</div>
@endsection
