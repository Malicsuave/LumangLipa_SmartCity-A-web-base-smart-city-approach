@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.announcements.index') }}">Announcements</a></li>
<li class="breadcrumb-item active" aria-current="page">Create</li>
@endsection

@section('title', 'Create Announcement')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h1 class="h3 mb-0 text-gray-800">Create Announcement</h1>
                    <p class="text-muted mb-0">Create a new announcement for the barangay</p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.announcements.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Back to List
                    </a>
                </div>
            </div>

            <div class="card shadow-lg border-0 mb-4 admin-card-shadow">
                <div class="card-body">
                    <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <label class="form-control-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label">Content <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              name="content" rows="6" required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Date</label>
                                            <input type="date" class="form-control @error('date') is-invalid @enderror" 
                                                   name="date" value="{{ old('date') }}">
                                            @error('date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-control-label">Time</label>
                                            <input type="time" class="form-control @error('time') is-invalid @enderror" 
                                                   name="time" value="{{ old('time') }}">
                                            @error('time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label">Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('type') is-invalid @enderror" name="type" id="announcementType" required>
                                        <option value="">Select Type</option>
                                        <option value="general" {{ old('type') === 'general' ? 'selected' : '' }}>General Announcement</option>
                                        <option value="health_related" {{ old('type') === 'health_related' ? 'selected' : '' }}>Health Related</option>
                                        <option value="event" {{ old('type') === 'event' ? 'selected' : '' }}>Event</option>
                                        <option value="service" {{ old('type') === 'service' ? 'selected' : '' }}>Service</option>
                                        <option value="program" {{ old('type') === 'program' ? 'selected' : '' }}>Program</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group" id="maxSlotsGroup" style="display: none;">
                                    <label class="form-control-label">Maximum Slots</label>
                                    <input type="number" class="form-control @error('max_slots') is-invalid @enderror" 
                                           name="max_slots" value="{{ old('max_slots') }}" min="1">
                                    @error('max_slots')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Number of available slots for registration (leave blank for unlimited)</small>
                                </div>

                                <div class="form-group">
                                    <label class="form-control-label">Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Optional image for the announcement</small>
                                </div>

                                <div class="form-group">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isActive">
                                            Active
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Inactive announcements won't be shown to the public</small>
                                </div>
                            </div>
                        </div>

                        <hr class="horizontal dark">
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn bg-gradient-primary">
                                <i class="fas fa-save"></i> Create Announcement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('announcementType');
    const maxSlotsGroup = document.getElementById('maxSlotsGroup');
    const maxSlotsInput = maxSlotsGroup.querySelector('input');

    function toggleFields() {
        if (typeSelect.value === 'health_related') {
            maxSlotsGroup.style.display = 'block';
            maxSlotsInput.required = false;
        } else {
            maxSlotsGroup.style.display = 'none';
            maxSlotsInput.required = false;
            maxSlotsInput.value = '';
        }
    }

    typeSelect.addEventListener('change', toggleFields);
    
    // Initial check
    if (typeSelect.value === 'health_related') {
        toggleFields();
    }
});
</script>

<style>
.admin-card-shadow {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
}
</style>
@endsection