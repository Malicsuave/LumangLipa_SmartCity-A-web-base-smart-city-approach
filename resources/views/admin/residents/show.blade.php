@extends('layouts.admin.master')

@section('title', 'Resident Details')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h3 page-title">Resident Details</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.residents.index') }}" class="btn btn-primary mr-2">
                        <i class="fe fe-arrow-left mr-2"></i> Back to List
                    </a>
                    <a href="{{ route('admin.residents.edit', $resident) }}" class="btn btn-outline-secondary mr-2">
                        <i class="fe fe-edit mr-2"></i> Edit Details
                    </a>
                    <a href="{{ route('admin.residents.id.show', $resident) }}" class="btn btn-outline-secondary mr-2">
                        <i class="fe fe-credit-card mr-2"></i> ID Management
                    </a>
                    <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" type="button">
                        <i class="fe fe-trash mr-2 text-white"></i> Delete
                    </button>
                </div>
            </div>
            
            <!-- ID Card Status Banner -->
            @if($resident->photo)
                @if($resident->id_status == 'issued')
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <span class="fe fe-check-circle fe-16 mr-2"></span>
                        <div>
                            ID Card has been issued and is valid until {{ $resident->id_expires_at->format('F d, Y') }}. 
                            <a href="{{ route('admin.residents.id.preview', $resident) }}" class="alert-link">Preview ID Card</a> | 
                            <a href="{{ route('admin.residents.id.download', $resident) }}" class="alert-link">Download ID Card</a>
                        </div>
                    </div>
                @elseif($resident->id_status == 'needs_renewal')
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <span class="fe fe-alert-circle fe-16 mr-2"></span>
                        <div>
                            ID Card needs renewal. 
                            <a href="{{ route('admin.residents.id.show', $resident) }}" class="alert-link">Process Renewal</a>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <span class="fe fe-info fe-16 mr-2"></span>
                        <div>
                            Photo is uploaded but ID Card has not been issued. 
                            <a href="{{ route('admin.residents.id.show', $resident) }}" class="alert-link">Issue ID Card</a>
                        </div>
                    </div>
                @endif
            @else
                <div class="alert alert-secondary d-flex align-items-center" role="alert">
                    <span class="fe fe-camera fe-16 mr-2"></span>
                    <div>
                        No ID photo uploaded. 
                        <a href="{{ route('admin.residents.id.show', $resident) }}" class="alert-link">Upload photo and manage ID</a>
                    </div>
                </div>
            @endif
            
            <div class="row g-4 align-items-start">
                <!-- Left: Photo & Basic Info -->
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="avatar avatar-xl mb-4">
                                @if($resident->photo)
                                    <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}" class="avatar-img rounded-circle">
                                @else
                                    <div class="avatar-letter rounded-circle bg-primary">{{ substr($resident->first_name, 0, 1) }}</div>
                                @endif
                            </div>
                            <h5 class="mb-2 mt-2">{{ $resident->full_name }}</h5>
                            <p class="small text-muted mb-1">Barangay ID</p>
                            <h6 class="mb-3">{{ $resident->barangay_id }}</h6>
                            <div class="mb-3">
                                <span class="badge badge-info">{{ $resident->type_of_resident }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted">Age:</span> {{ $resident->age }}
                            </div>
                            <div class="mb-2">
                                <span class="text-muted">Gender:</span> {{ $resident->sex }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Middle: Personal & Additional Info -->
                <div class="col-md-5 mb-4 mb-md-0">
                    <div class="card h-100 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-3">Personal Information</h6>
                            <div class="row mb-4">
                                <div class="col-6 mb-3"><strong>Birthdate:</strong> <br>{{ $resident->birthdate ? $resident->birthdate->format('F d, Y') : 'N/A' }}</div>
                                <div class="col-6 mb-3"><strong>Birthplace:</strong> <br>{{ $resident->birthplace }}</div>
                                <div class="col-6 mb-3"><strong>Civil Status:</strong> <br>{{ $resident->civil_status }}</div>
                                <div class="col-6 mb-3"><strong>Address:</strong> <br>{{ $resident->address }}</div>
                                <div class="col-6 mb-3"><strong>Contact #:</strong> <br>{{ $resident->contact_number ?: 'Not provided' }}</div>
                                <div class="col-6 mb-3"><strong>Email:</strong> <br>{{ $resident->email_address ?: 'Not provided' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card h-100 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-3">Additional Information</h6>
                            <div class="row mb-4">
                                <div class="col-6 mb-3"><strong>Citizenship:</strong> <br>{{ $resident->citizenship_type }} {{ $resident->citizenship_country ? '- ' . $resident->citizenship_country : '' }}</div>
                                <div class="col-6 mb-3"><strong>Religion:</strong> <br>{{ $resident->religion }}</div>
                                <div class="col-6 mb-3"><strong>PhilSys ID:</strong> <br>{{ $resident->philsys_id ?: 'Not provided' }}</div>
                                <div class="col-6 mb-3"><strong>Occupation:</strong> <br>{{ $resident->profession_occupation }}</div>
                                <div class="col-6 mb-3"><strong>Monthly Income:</strong> <br>₱{{ number_format($resident->monthly_income, 2) }}</div>
                                <div class="col-6 mb-3"><strong>Education:</strong> <br>{{ $resident->educational_attainment }} ({{ $resident->education_status }})</div>
                            </div>
                        </div>
                    </div>
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-3">Mother's Information</h6>
                            <div class="mb-2">
                                <strong>Name:</strong> <br>{{ $resident->mother_full_name ?: 'Not provided' }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Right: Sectors, Household, Family -->
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-3">Population Sectors</h6>
                            @if(is_array($resident->population_sectors) && count($resident->population_sectors) > 0)
                                <ul class="list-group mb-4">
                                    @foreach($resident->population_sectors as $sector)
                                        <li class="list-group-item py-2">{{ $sector }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="alert alert-light mb-4">No population sectors selected.</div>
                            @endif
                        </div>
                    </div>
                    <div class="card h-100 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-3">Household Information</h6>
                            @if($resident->household)
                                <div class="list-group mb-4">
                                    @if($resident->household->primary_name)
                                    <div class="list-group-item py-2">
                                        <strong>Primary:</strong> {{ $resident->household->primary_name }}
                                        <div class="small text-muted">{{ $resident->household->primary_gender }}, {{ $resident->household->primary_birthday ? $resident->household->primary_birthday->age . ' yrs' : 'Age n/a' }}</div>
                                    </div>
                                    @endif
                                    @if($resident->household->secondary_name)
                                    <div class="list-group-item py-2">
                                        <strong>Secondary:</strong> {{ $resident->household->secondary_name }}
                                        <div class="small text-muted">{{ $resident->household->secondary_gender }}, {{ $resident->household->secondary_birthday ? $resident->household->secondary_birthday->age . ' yrs' : 'Age n/a' }}</div>
                                    </div>
                                    @endif
                                    @if($resident->household->emergency_contact_name)
                                    <div class="list-group-item py-2">
                                        <strong>Emergency:</strong> {{ $resident->household->emergency_contact_name }}
                                        <div class="small text-muted">{{ $resident->household->emergency_relationship }}, {{ $resident->household->emergency_phone }}</div>
                                    </div>
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-light mb-4">No household information available.</div>
                            @endif
                        </div>
                    </div>
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-3">Family Members</h6>
                            @if(isset($resident->familyMembers) && $resident->familyMembers->count() > 0)
                                <div class="table-responsive mb-0">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Relationship</th>
                                                <th>Age</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($resident->familyMembers as $member)
                                            <tr>
                                                <td>{{ $member->name }}</td>
                                                <td>{{ $member->relationship }}</td>
                                                <td>{{ $member->age }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-light mb-0">No family members recorded.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this resident record? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.residents.destroy', $resident) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Resident</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar {
        position: relative;
        width: 110px;
        height: 110px;
        margin: 0 auto;
    }
    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-letter {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        color: white;
        font-size: 38px;
        font-weight: bold;
    }
    .card-body .row > [class^='col-'] {
        margin-bottom: 0 !important;
    }
    @media (max-width: 991.98px) {
        .border-right, .pr-md-4, .pl-md-4, .px-md-5 {
            border-right: none !important;
            padding-right: 0 !important;
            padding-left: 0 !important;
        }
    }
</style>
@endpush