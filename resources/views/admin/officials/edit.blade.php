@extends('layouts.admin.master')

@section('content')
<div class="container-fluid mt-4">
    <h4>Edit Official</h4>
    
    <form action="{{ route('admin.officials.update', $official) }}" method="POST" enctype="multipart/form-data" class="card card-body shadow-sm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $official->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="position">Position <span class="text-danger">*</span></label>
                    <select name="position" id="position" class="form-control @error('position') is-invalid @enderror" required>
                        <option value="">Select Position</option>
                        <option value="Captain" {{ old('position', $official->position) == 'Captain' ? 'selected' : '' }}>Barangay Captain</option>
                        <option value="Councilor" {{ old('position', $official->position) == 'Councilor' ? 'selected' : '' }}>Councilor</option>
                        <option value="SK Chairman" {{ old('position', $official->position) == 'SK Chairman' ? 'selected' : '' }}>SK Chairman</option>
                        <option value="Secretary" {{ old('position', $official->position) == 'Secretary' ? 'selected' : '' }}>Secretary</option>
                        <option value="Treasurer" {{ old('position', $official->position) == 'Treasurer' ? 'selected' : '' }}>Treasurer</option>
                    </select>
                    @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="committee">Committee (For Councilors only)</label>
                    <input type="text" name="committee" id="committee" class="form-control @error('committee') is-invalid @enderror" value="{{ old('committee', $official->committee) }}" placeholder="e.g., Health Committee, Peace and Order">
                    @error('committee')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Leave empty for non-councilor positions</small>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="profile_pic">Profile Picture</label>
                    <input type="file" name="profile_pic" id="profile_pic" class="form-control @error('profile_pic') is-invalid @enderror" accept="image/*">
                    @error('profile_pic')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <small class="text-muted">Max size: 2MB. Formats: JPG, PNG, GIF</small>
                    
                    @if($official->profile_pic)
                        <div class="mt-3">
                            <div class="current-photo">
                                <img src="{{ asset('storage/officials/' . $official->profile_pic) }}" 
                                     alt="{{ $official->name }}" 
                                     class="img-thumbnail" 
                                     style="max-height: 150px;">
                                <div class="mt-2">
                                    <small class="text-muted">Current photo</small>
                                    <button type="button" class="btn btn-sm btn-danger ml-2" onclick="deletePhoto({{ $official->id }})">
                                        <i class="fas fa-trash"></i> Delete Photo
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.officials.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Officials
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Official
            </button>
        </div>
    </form>
</div>

<script>
// Show/hide committee field based on position
document.getElementById('position').addEventListener('change', function() {
    const committeeField = document.getElementById('committee');
    const committeeGroup = committeeField.closest('.form-group');
    
    if (this.value === 'Councilor') {
        committeeGroup.style.display = 'block';
        committeeField.removeAttribute('disabled');
    } else {
        committeeGroup.style.display = 'none';
        committeeField.setAttribute('disabled', 'disabled');
        committeeField.value = '';
    }
});

// Initialize on page load
document.getElementById('position').dispatchEvent(new Event('change'));

function deletePhoto(officialId) {
    if (confirm('Are you sure you want to delete this photo?')) {
        fetch(`{{ url('/admin/officials') }}/${officialId}/delete-photo`, {
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
                alert('Error deleting photo: ' + (data.message || 'Unknown error'));
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
