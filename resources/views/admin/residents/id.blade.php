@extends('layouts.admin.master')

@section('title', 'Manage Resident ID: ' . $resident->full_name)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Manage Resident ID: {{ $resident->full_name }}</h6>
                        <div>
                            <a href="{{ route('admin.residents.show', $resident) }}" class="btn btn-sm btn-secondary">
                                <i class="fe fe-arrow-left"></i> Back to Resident Profile
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Profile Photo</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        @if($resident->photo)
                                            <img src="{{ asset('storage/residents/photos/' . $resident->photo) }}" alt="{{ $resident->full_name }}" class="img-fluid img-profile rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                        @else
                                            <div class="no-photo-placeholder">
                                                <i class="fe fe-user" style="font-size: 80px;"></i>
                                                <p>No Photo</p>
                                            </div>
                                        @endif
                                    </div>

                                    <form action="{{ route('admin.residents.id.upload-photo', $resident) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="photo">Upload ID Photo</label>
                                            <input type="file" class="form-control-file @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*" required>
                                            <small class="form-text text-muted">Upload a square photo (1:1 ratio) for best results. Max size: 2MB.</small>
                                            @error('photo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary">Upload Photo</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Signature</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        @if($resident->signature)
                                            <img src="{{ asset('storage/residents/signatures/' . $resident->signature) }}" alt="{{ $resident->full_name }}'s Signature" class="img-fluid signature-image mb-2" style="max-height: 100px; border: 1px solid #ddd; padding: 10px;">
                                        @else
                                            <div class="no-signature-placeholder p-3 bg-light rounded text-center mb-3">
                                                <i class="fe fe-edit-3" style="font-size: 40px;"></i>
                                                <p>No Signature</p>
                                            </div>
                                        @endif
                                    </div>

                                    <form action="{{ route('admin.residents.id.upload-signature', $resident) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="signature">Upload Signature</label>
                                            <input type="file" class="form-control-file @error('signature') is-invalid @enderror" id="signature" name="signature" accept="image/*" required>
                                            <small class="form-text text-muted">Upload a clear image of the resident's signature. Max size: 1MB.</small>
                                            @error('signature')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-primary">Upload Signature</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">ID Information</h6>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.residents.id.update', $resident) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="barangay_id">Barangay ID Number</label>
                                                    <input type="text" class="form-control @error('barangay_id') is-invalid @enderror" id="barangay_id" name="barangay_id" value="{{ old('barangay_id', $resident->barangay_id) }}">
                                                    @error('barangay_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_expires_at">Valid Until</label>
                                                    <input type="date" class="form-control @error('id_expires_at') is-invalid @enderror" id="id_expires_at" name="id_expires_at" value="{{ old('id_expires_at', $resident->id_expires_at ? $resident->id_expires_at->format('Y-m-d') : '') }}">
                                                    @error('id_expires_at')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Update ID Information</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">ID Actions</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="id-status-card p-3 mb-3 text-center rounded {{ $resident->id_status == 'issued' ? 'bg-success' : 'bg-secondary' }}">
                                                <h5 class="text-white">ID Status: {{ ucfirst($resident->id_status ?: 'Not Issued') }}</h5>
                                                @if($resident->id_issued_at)
                                                    <p class="text-white mb-0">Issued on: {{ $resident->id_issued_at->format('M d, Y') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="action-buttons text-center">
                                                @if($resident->photo && $resident->signature)
                                                    <a href="{{ route('admin.residents.id.preview', $resident) }}" class="btn btn-info mb-2 mx-1">
                                                        <i class="fe fe-eye"></i> Preview ID Card
                                                    </a>
                                                    
                                                    @if($resident->id_status != 'issued')
                                                        <form action="{{ route('admin.residents.id.issue', $resident) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success mb-2 mx-1">
                                                                <i class="fe fe-check-circle"></i> Issue ID
                                                            </button>
                                                        </form>
                                                    @else
                                                        <form action="{{ route('admin.residents.id.revoke', $resident) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger mb-2 mx-1" onclick="return confirm('Are you sure you want to revoke this ID?')">
                                                                <i class="fe fe-x-circle"></i> Revoke ID
                                                            </button>
                                                        </form>
                                                        
                                                        <a href="{{ route('admin.residents.id.download', $resident) }}" class="btn btn-primary mb-2 mx-1">
                                                            <i class="fe fe-download"></i> Download ID
                                                        </a>
                                                    @endif
                                                @else
                                                    <div class="alert alert-warning">
                                                        <i class="fe fe-alert-triangle"></i> Both photo and signature are required to preview or issue an ID.
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Preview uploaded image before submission
        $('#photo').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('.img-profile').attr('src', e.target.result);
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Preview uploaded signature before submission
        $('#signature').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if ($('.signature-image').length) {
                        $('.signature-image').attr('src', e.target.result);
                    } else {
                        $('.no-signature-placeholder').replaceWith('<img src="' + e.target.result + '" class="img-fluid signature-image mb-2" style="max-height: 100px; border: 1px solid #ddd; padding: 10px;">');
                    }
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection