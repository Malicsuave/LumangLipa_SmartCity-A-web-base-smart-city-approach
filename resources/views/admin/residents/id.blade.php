@extends('layouts.admin.master')

@section('title', 'Resident ID Management')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">ID Management: {{ $resident->full_name }}</h6>
                        <a href="{{ route('admin.residents.show', $resident) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fe fe-arrow-left"></i> Back to Resident Details
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Left column - Photo upload and display -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0">ID Photo</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <div class="id-photo-container" style="width: 240px; height: 240px; margin: 0 auto; border: 1px solid #ddd; overflow: hidden;">
                                            @if($resident->photo)
                                                <img src="{{ $resident->photo_url }}" alt="Resident Photo" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center h-100 bg-light text-muted">
                                                    <span>No photo uploaded</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <form action="{{ route('admin.residents.id.upload-photo', $resident) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="photo" class="d-block">Upload Photo</label>
                                            <input type="file" id="photo" name="photo" class="form-control-file @error('photo') is-invalid @enderror" accept="image/*">
                                            <small class="form-text text-muted">Upload a 2x2 photo. Maximum file size: 5MB.</small>
                                            
                                            @error('photo')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">
                                            @if($resident->photo)
                                                Replace Photo
                                            @else
                                                Upload Photo
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right column - Signature upload and display -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0">Signature</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <div class="signature-container" style="width: 300px; height: 100px; margin: 0 auto; border: 1px solid #ddd; overflow: hidden; background-color: #f9f9f9;">
                                            @if($resident->signature)
                                                <img src="{{ $resident->signature_url }}" alt="Resident Signature" class="img-fluid" style="width: 100%; height: 100%; object-fit: contain;">
                                            @else
                                                <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                                    <span>No signature uploaded</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <form action="{{ route('admin.residents.id.upload-signature', $resident) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="signature" class="d-block">Upload Signature</label>
                                            <input type="file" id="signature" name="signature" class="form-control-file @error('signature') is-invalid @enderror" accept="image/*">
                                            <small class="form-text text-muted">Upload a clear image of the signature. Maximum file size: 2MB.</small>
                                            
                                            @error('signature')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">
                                            @if($resident->signature)
                                                Replace Signature
                                            @else
                                                Upload Signature
                                            @endif
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- ID Card Management -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0">ID Card Management</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <h6>ID Details</h6>
                                            <table class="table table-bordered table-sm">
                                                <tr>
                                                    <th>Status</th>
                                                    <td>
                                                        @if($resident->id_status == 'issued')
                                                            <span class="badge badge-success">{{ $resident->id_status_label }}</span>
                                                        @elseif($resident->id_status == 'pending')
                                                            <span class="badge badge-warning">{{ $resident->id_status_label }}</span>
                                                        @elseif($resident->id_status == 'needs_renewal')
                                                            <span class="badge badge-danger">{{ $resident->id_status_label }}</span>
                                                        @else
                                                            <span class="badge badge-secondary">{{ $resident->id_status_label }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @if($resident->id_issued_at)
                                                <tr>
                                                    <th>Issued Date</th>
                                                    <td>{{ $resident->id_issued_at->format('F d, Y') }}</td>
                                                </tr>
                                                @endif
                                                @if($resident->id_expires_at)
                                                <tr>
                                                    <th>Expiry Date</th>
                                                    <td>
                                                        {{ $resident->id_expires_at->format('F d, Y') }}
                                                        @if($resident->id_expires_at->isPast())
                                                            <span class="badge badge-danger ml-2">Expired</span>
                                                        @elseif($resident->id_expires_at->diffInMonths(now()) <= 3)
                                                            <span class="badge badge-warning ml-2">Expiring Soon</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endif
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>Actions</h6>
                                            <div class="d-flex flex-wrap">
                                                @if($resident->photo)
                                                    @if($resident->id_status != 'issued')
                                                        <form action="{{ route('admin.residents.id.issue', $resident) }}" method="POST" class="mr-2 mb-2">
                                                            @csrf
                                                            <button type="submit" class="btn btn-success">
                                                                <i class="fe fe-check-circle"></i> Issue ID Card
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    @if($resident->id_status != 'needs_renewal' && $resident->id_status != 'not_issued')
                                                        <form action="{{ route('admin.residents.id.mark-renewal', $resident) }}" method="POST" class="mr-2 mb-2">
                                                            @csrf
                                                            <button type="submit" class="btn btn-warning">
                                                                <i class="fe fe-refresh-cw"></i> Mark for Renewal
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    <a href="{{ route('admin.residents.id.preview', $resident) }}" class="btn btn-info mr-2 mb-2" target="_blank">
                                                        <i class="fe fe-eye"></i> Preview ID
                                                    </a>
                                                    
                                                    @if($resident->id_status == 'issued')
                                                        <a href="{{ route('admin.residents.id.download', $resident) }}" class="btn btn-primary mb-2">
                                                            <i class="fe fe-download"></i> Download ID
                                                        </a>
                                                    @endif
                                                @else
                                                    <div class="alert alert-warning">
                                                        <i class="fe fe-alert-circle"></i> Upload a photo to manage ID card
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

@push('styles')
<style>
    .id-photo-container img {
        object-fit: cover;
    }
    .signature-container {
        background: repeating-linear-gradient(#f9f9f9, #f9f9f9 24px, #ccc 25px);
    }
</style>
@endpush