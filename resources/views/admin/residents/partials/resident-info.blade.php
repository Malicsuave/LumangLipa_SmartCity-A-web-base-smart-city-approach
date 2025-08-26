<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fe fe-user fe-16 mr-2"></i>Resident Information</h5>
    </div>
    <div class="card-body text-center">
        <div class="avatar avatar-lg mb-3">
            @if($resident->photo)
                <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}" class="avatar-img rounded-circle">
            @else
                <div class="avatar-letter rounded-circle bg-primary">{{ substr($resident->first_name, 0, 1) }}</div>
            @endif
        </div>
        <h5 class="mb-1">{{ $resident->full_name }}</h5>
        <p class="text-muted mb-2">{{ $resident->barangay_id }}</p>
        <div class="mb-2"><strong>Age:</strong> {{ $resident->age }}</div>
        <div class="mb-2"><strong>Gender:</strong> {{ $resident->sex }}</div>
        <div class="mb-2"><strong>Contact:</strong> {{ $resident->contact_number ?: 'N/A' }}</div>
        <div class="mb-2"><strong>Address:</strong> {{ $resident->address }}</div>
        <div class="mb-2"><strong>Civil Status:</strong> {{ $resident->civil_status }}</div>
        <div class="mb-2"><strong>Type:</strong> {{ $resident->type_of_resident }}</div>
    </div>
</div>
