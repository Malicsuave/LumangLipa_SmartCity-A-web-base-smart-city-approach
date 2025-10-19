@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.announcements.index') }}">Announcements</a></li>
<li class="breadcrumb-item active" aria-current="page">View Details</li>
@endsection

@section('title', 'View Announcement')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h1 class="h3 mb-0 text-gray-800">Announcement Details</h1>
                    <p class="text-muted mb-0">View announcement information and statistics</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary mr-2">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </a>
                    <a href="{{ route('admin.announcements.edit', $announcement) }}" class="btn btn-primary">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                </div>
            </div>

            <div class="card shadow-lg border-0 mb-4 admin-card-shadow">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="mb-4">
                                <h4 class="mb-2">{{ $announcement->title }}</h4>
                                <div class="mb-3">
                                    <span class="badge badge-sm bg-gradient-{{ $announcement->is_active ? 'success' : 'secondary' }}">
                                        {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                    <span class="badge badge-sm bg-gradient-info ms-2">
                                        {{ ucfirst(str_replace('_', ' ', $announcement->type)) }}
                                    </span>
                                    @if($announcement->status)
                                        <span class="badge badge-sm bg-gradient-{{ $announcement->status === 'available' ? 'success' : ($announcement->status === 'full' ? 'danger' : 'warning') }} ms-2">
                                            {{ ucfirst($announcement->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="mb-4">
                                <h6 class="text-dark font-weight-bold">Content</h6>
                                <div class="text-sm">
                                    {!! nl2br(e($announcement->content)) !!}
                                </div>
                            </div>

                            @if($announcement->start_date || $announcement->end_date)
                            <div class="mb-4">
                                <h6 class="text-dark font-weight-bold">Schedule</h6>
                                <div class="text-sm">
                                    @if($announcement->start_date)
                                        <div><i class="fas fa-calendar-start text-success"></i> Start: {{ $announcement->start_date->format('F j, Y') }}</div>
                                    @endif
                                    @if($announcement->end_date)
                                        <div><i class="fas fa-calendar-times text-danger"></i> End: {{ $announcement->end_date->format('F j, Y') }}</div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($announcement->image)
                            <div class="mb-4">
                                <h6 class="text-dark font-weight-bold">Image</h6>
                                <img src="{{ asset('storage/' . $announcement->image) }}" alt="{{ $announcement->title }}" 
                                     class="img-fluid rounded" style="max-height: 300px;">
                            </div>
                            @endif
                        </div>

                        <div class="col-lg-4">
                            <div class="card bg-gradient-light">
                                <div class="card-body">
                                    <h6 class="text-dark font-weight-bold mb-3">Statistics</h6>
                                    
                                    @if($announcement->type === 'limited_slots')
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-sm">Slot Usage</span>
                                            <span class="text-sm font-weight-bold">
                                                {{ $announcement->current_slots }}/{{ $announcement->max_slots }}
                                            </span>
                                        </div>
                                        <div class="progress progress-sm">
                                            <div class="progress-bar bg-gradient-{{ $announcement->progress_color }}" 
                                                 style="width: {{ $announcement->progress_percentage }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $announcement->progress_percentage }}% filled</small>
                                    </div>
                                    @endif

                                    <div class="mb-3">
                                        <div class="text-sm text-muted">Total Registrations</div>
                                        <div class="h5 font-weight-bold">{{ $announcement->current_slots }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="text-sm text-muted">Created</div>
                                        <div class="text-sm">{{ $announcement->created_at->format('M j, Y g:i A') }}</div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="text-sm text-muted">Last Updated</div>
                                        <div class="text-sm">{{ $announcement->updated_at->format('M j, Y g:i A') }}</div>
                                    </div>

                                    @if($announcement->current_slots > 0)
                                    <div class="mt-4">
                                        <a href="{{ route('admin.announcements.registrations', $announcement) }}" 
                                           class="btn bg-gradient-info btn-sm w-100">
                                            <i class="fas fa-users"></i> View All Registrations
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            @if($announcement->type === 'limited_slots' && $announcement->current_slots > 0)
                            <div class="card mt-4">
                                <div class="card-body">
                                    <h6 class="text-dark font-weight-bold mb-3">Recent Registrations</h6>
                                    @foreach($announcement->registrations()->latest()->limit(5)->get() as $registration)
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar avatar-xs bg-gradient-secondary rounded-circle me-2">
                                            <i class="fas fa-user text-white text-xs"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="text-sm font-weight-bold">{{ $registration->first_name }} {{ $registration->last_name }}</div>
                                            <div class="text-xs text-muted">{{ $registration->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    @endforeach
                                    
                                    @if($announcement->current_slots > 5)
                                    <div class="text-center mt-3">
                                        <a href="{{ route('admin.announcements.registrations', $announcement) }}" 
                                           class="text-sm text-primary">
                                            View all {{ $announcement->current_slots }} registrations →
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.admin-card-shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}
</style>
@endsection