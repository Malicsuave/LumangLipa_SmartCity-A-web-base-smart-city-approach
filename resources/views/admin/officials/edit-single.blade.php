@extends('layouts.admin.master')

@section('content')
<div class="container-fluid mt-4">
    <h4>Edit Barangay Officials</h4>

    <form action="{{ route('admin.officials.update-single') }}" method="POST" enctype="multipart/form-data" class="card card-body shadow-sm">
        @csrf
        
        <!-- Barangay Captain -->
        <div class="form-group border-bottom pb-4 mb-4">
            <h5 class="text-primary">Barangay Captain</h5>
            <div class="row">
                <div class="col-md-8">
                    <label for="captain_name">Name</label>
                    <input type="text" name="captain_name" id="captain_name" class="form-control @error('captain_name') is-invalid @enderror" value="{{ old('captain_name', $officials->captain_name) }}">
                    @error('captain_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="mt-1 text-muted"><strong>Current:</strong> {{ $officials->captain_name }}</div>
                </div>
                <div class="col-md-4">
                    <label for="captain_photo">Photo</label>
                    <input type="file" name="captain_photo" id="captain_photo" class="form-control @error('captain_photo') is-invalid @enderror" accept="image/*">
                    @error('captain_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @if($officials->captain_photo)
                        <div class="mt-2">
                            <img src="{{ asset('storage/officials/' . $officials->captain_photo) }}" alt="Captain Photo" class="img-thumbnail" style="max-height: 100px;">
                            <div class="text-muted small">Current photo</div>
                            <button type="button" class="btn btn-sm btn-danger mt-1" onclick="deletePhoto('captain_photo')">
                                <i class="fas fa-trash"></i> Delete Photo
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Councilors -->
        <div class="form-group border-bottom pb-4 mb-4">
            <h5 class="text-primary">Barangay Councilors</h5>
            @for($i = 1; $i <= 7; $i++)
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="councilor{{ $i }}_name">Councilor {{ $i }} Name</label>
                    <input type="text" name="councilor{{ $i }}_name" id="councilor{{ $i }}_name" class="form-control @error('councilor'.$i.'_name') is-invalid @enderror" value="{{ old('councilor'.$i.'_name', $officials->{'councilor'.$i.'_name'}) }}">
                    @error('councilor'.$i.'_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="mt-1 text-muted"><strong>Current:</strong> {{ $officials->{'councilor'.$i.'_name'} }}</div>
                </div>
                <div class="col-md-4">
                    <label for="councilor{{ $i }}_photo">Photo</label>
                    <input type="file" name="councilor{{ $i }}_photo" id="councilor{{ $i }}_photo" class="form-control @error('councilor'.$i.'_photo') is-invalid @enderror" accept="image/*">
                    @error('councilor'.$i.'_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @if($officials->{'councilor'.$i.'_photo'})
                        <div class="mt-2">
                            <img src="{{ asset('storage/officials/' . $officials->{'councilor'.$i.'_photo'}) }}" alt="Councilor {{ $i }} Photo" class="img-thumbnail" style="max-height: 80px;">
                            <div class="text-muted small">Current photo</div>
                            <button type="button" class="btn btn-sm btn-danger mt-1" onclick="deletePhoto('councilor{{ $i }}_photo')">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            @endfor
        </div>

        <!-- SK Chairperson -->
        <div class="form-group border-bottom pb-4 mb-4">
            <h5 class="text-primary">SK Chairperson</h5>
            <div class="row">
                <div class="col-md-8">
                    <label for="sk_chairperson_name">Name</label>
                    <input type="text" name="sk_chairperson_name" id="sk_chairperson_name" class="form-control @error('sk_chairperson_name') is-invalid @enderror" value="{{ old('sk_chairperson_name', $officials->sk_chairperson_name) }}">
                    @error('sk_chairperson_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="mt-1 text-muted"><strong>Current:</strong> {{ $officials->sk_chairperson_name }}</div>
                </div>
                <div class="col-md-4">
                    <label for="sk_chairperson_photo">Photo</label>
                    <input type="file" name="sk_chairperson_photo" id="sk_chairperson_photo" class="form-control @error('sk_chairperson_photo') is-invalid @enderror" accept="image/*">
                    @error('sk_chairperson_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @if($officials->sk_chairperson_photo)
                        <div class="mt-2">
                            <img src="{{ asset('storage/officials/' . $officials->sk_chairperson_photo) }}" alt="SK Chairperson Photo" class="img-thumbnail" style="max-height: 100px;">
                            <div class="text-muted small">Current photo</div>
                            <button type="button" class="btn btn-sm btn-danger mt-1" onclick="deletePhoto('sk_chairperson_photo')">
                                <i class="fas fa-trash"></i> Delete Photo
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Secretary and Treasurer -->
        <div class="form-group pb-4 mb-4">
            <h5 class="text-primary">Barangay Staff</h5>
            <div class="row mb-3">
                <div class="col-md-8">
                    <label for="secretary_name">Barangay Secretary Name</label>
                    <input type="text" name="secretary_name" id="secretary_name" class="form-control @error('secretary_name') is-invalid @enderror" value="{{ old('secretary_name', $officials->secretary_name) }}">
                    @error('secretary_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="mt-1 text-muted"><strong>Current:</strong> {{ $officials->secretary_name }}</div>
                </div>
                <div class="col-md-4">
                    <label for="secretary_photo">Photo</label>
                    <input type="file" name="secretary_photo" id="secretary_photo" class="form-control @error('secretary_photo') is-invalid @enderror" accept="image/*">
                    @error('secretary_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @if($officials->secretary_photo)
                        <div class="mt-2">
                            <img src="{{ asset('storage/officials/' . $officials->secretary_photo) }}" alt="Secretary Photo" class="img-thumbnail" style="max-height: 80px;">
                            <div class="text-muted small">Current photo</div>
                            <button type="button" class="btn btn-sm btn-danger mt-1" onclick="deletePhoto('secretary_photo')">
                                <i class="fas fa-trash"></i> Delete Photo
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <label for="treasurer_name">Barangay Treasurer Name</label>
                    <input type="text" name="treasurer_name" id="treasurer_name" class="form-control @error('treasurer_name') is-invalid @enderror" value="{{ old('treasurer_name', $officials->treasurer_name) }}">
                    @error('treasurer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="mt-1 text-muted"><strong>Current:</strong> {{ $officials->treasurer_name }}</div>
                </div>
                <div class="col-md-4">
                    <label for="treasurer_photo">Photo</label>
                    <input type="file" name="treasurer_photo" id="treasurer_photo" class="form-control @error('treasurer_photo') is-invalid @enderror" accept="image/*">
                    @error('treasurer_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @if($officials->treasurer_photo)
                        <div class="mt-2">
                            <img src="{{ asset('storage/officials/' . $officials->treasurer_photo) }}" alt="Treasurer Photo" class="img-thumbnail" style="max-height: 80px;">
                            <div class="text-muted small">Current photo</div>
                            <button type="button" class="btn btn-sm btn-danger mt-1" onclick="deletePhoto('treasurer_photo')">
                                <i class="fas fa-trash"></i> Delete Photo
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-save"></i> Save Officials Information
            </button>
        </div>
    </form>
</div>

<script>
function deletePhoto(field) {
    if (confirm('Are you sure you want to delete this photo?')) {
        fetch(`{{ url('/admin/officials/photo') }}/${field}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page to reflect changes
                window.location.reload();
            } else {
                alert('Error deleting photo: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while deleting the photo.');
        });
    }
}
</script>
@endsection 