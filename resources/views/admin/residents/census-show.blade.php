@extends('layouts.admin.master')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css">
<!-- AdminLTE 3.0 Custom Styles -->
<style>
.content-header {
    padding: 15px 0.5rem;
}
.card-primary.card-outline {
    border-top: 3px solid #007bff;
}
.card-info.card-outline {
    border-top: 3px solid #17a2b8;
}
.card-success.card-outline {
    border-top: 3px solid #28a745;
}
.form-group label {
    font-weight: 600;
    color: #495057;
}
.btn-app {
    border-radius: 3px;
    position: relative;
    padding: 15px 5px;
    margin: 0 0 10px 10px;
    min-width: 80px;
    height: 60px;
    text-align: center;
    color: #444;
    border: 1px solid #ddd;
    background-color: #f4f4f4;
    font-size: 12px;
}
.btn-app:hover {
    background-color: #e9ecef;
    border-color: #adb5bd;
}
.btn-app > .fa, .btn-app > .fas, .btn-app > .far, .btn-app > .fab, .btn-app > .ion {
    font-size: 20px;
    display: block;
}
</style>
@endpush

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.residents.census-data') }}">Census Data</a></li>
<li class="breadcrumb-item active" aria-current="page">Household #{{ $household->household_id }}</li>
@endsection

@section('page-title', 'Household Details')
@section('page-subtitle', 'Household #' . $household->household_id)

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-home"></i> Household #{{ $household->household_id }}
                    <small class="text-muted">{{ $household->head_name }}</small>
                </h1>
            </div>
            <div class="col-sm-6">
                <div class="float-sm-right">
                    <a href="{{ route('admin.residents.census-data') }}" class="btn btn-app">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                    <button class="btn btn-app bg-danger" data-toggle="modal" data-target="#deleteModal">
                        <i class="fas fa-trash"></i>
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        <form action="{{ route('admin.residents.census-data.update', $household->household_id) }}" method="POST" id="householdEditForm">
            @csrf
            @method('PUT')
            @method('PUT')

            <div class="row">
                <!-- Household Head Information -->
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-user"></i> Household Head Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="head_name">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="head_name" name="head_name" 
                                       value="{{ $household->head_name ?? '' }}" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="head_gender">Gender</label>
                                        <select class="form-control" id="head_gender" name="head_gender">
                                            <option value="">Select gender</option>
                                            <option value="Male" {{ $household->head_gender == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ $household->head_gender == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Non-binary" {{ $household->head_gender == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                            <option value="Transgender" {{ $household->head_gender == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                            <option value="Other" {{ $household->head_gender == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="head_age">Age</label>
                                        <input type="number" class="form-control" id="head_age" name="head_age" 
                                               value="{{ $household->head_age }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="head_civil_status">Civil Status</label>
                                <select class="form-control" id="head_civil_status" name="head_civil_status">
                                    <option value="">Select status</option>
                                    <option value="Single" {{ $household->head_civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                    <option value="Married" {{ $household->head_civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                    <option value="Widowed" {{ $household->head_civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                    <option value="Separated" {{ $household->head_civil_status == 'Separated' ? 'selected' : '' }}>Separated</option>
                                    <option value="Divorced" {{ $household->head_civil_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="head_occupation">Occupation</label>
                                <input type="text" class="form-control" id="head_occupation" name="head_occupation" 
                                       value="{{ $household->head_occupation ?? '' }}">
                            </div>

                            <div class="form-group">
                                <label for="head_education">Education</label>
                                <select class="form-control" id="head_education" name="head_education">
                                    <option value="">Select educational attainment</option>
                                    <option value="No Formal Education" {{ $household->head_education == 'No Formal Education' ? 'selected' : '' }}>No Formal Education</option>
                                    <option value="Elementary Undergraduate" {{ $household->head_education == 'Elementary Undergraduate' ? 'selected' : '' }}>Elementary Undergraduate</option>
                                    <option value="Elementary Graduate" {{ $household->head_education == 'Elementary Graduate' ? 'selected' : '' }}>Elementary Graduate</option>
                                    <option value="High School Undergraduate" {{ $household->head_education == 'High School Undergraduate' ? 'selected' : '' }}>High School Undergraduate</option>
                                    <option value="High School Graduate" {{ $household->head_education == 'High School Graduate' ? 'selected' : '' }}>High School Graduate</option>
                                    <option value="Vocational/Technical Graduate" {{ $household->head_education == 'Vocational/Technical Graduate' ? 'selected' : '' }}>Vocational/Technical Graduate</option>
                                    <option value="College Undergraduate" {{ $household->head_education == 'College Undergraduate' ? 'selected' : '' }}>College Undergraduate</option>
                                    <option value="College Graduate" {{ $household->head_education == 'College Graduate' ? 'selected' : '' }}>College Graduate</option>
                                    <option value="Post Graduate" {{ $household->head_education == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Household Information -->
                <div class="col-md-6">
                    <div class="card card-info card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-home"></i> Household Information
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="address">Address <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="address" name="address" rows="3" required>{{ $household->address }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="contact_number">Contact Number</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" 
                                       value="{{ $household->contact_number ?? '' }}">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date Recorded</label>
                                        <input type="text" class="form-control" 
                                               value="{{ $household->created_at->format('M d, Y') }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Total Members</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" 
                                                   value="{{ $household->total_members }}" readonly>
                                            <div class="input-group-append">
                                                <span class="input-group-text">
                                                    <i class="fas fa-users"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Household Members -->
        <div class="row">
            <div class="col-12">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users"></i> Household Members ({{ $household->members->count() }})
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-success">{{ $household->members->count() }} Members</span>
                            <button type="button" class="btn btn-sm btn-primary ml-2" id="addMemberBtn">
                                <i class="fas fa-plus"></i> Add Member
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($household->members->count() > 0)
                            <div id="membersContainer">
                                @foreach($household->members as $index => $member)
                                <div class="member-card card card-outline mb-3" data-member-id="{{ $member->member_id }}">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">
                                            <i class="fas fa-user"></i> Member #{{ $index + 1 }}
                                        </h6>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-sm btn-danger remove-member">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Full Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="members[{{ $index }}][fullname]" 
                                                           value="{{ $member->fullname ?? '' }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Relationship to Head <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="members[{{ $index }}][relationship_to_head]" required>
                                                        <option value="">Select relationship</option>
                                                        <option value="Head" {{ $member->relationship_to_head == 'Head' ? 'selected' : '' }}>Head</option>
                                                        <option value="Spouse" {{ $member->relationship_to_head == 'Spouse' ? 'selected' : '' }}>Spouse</option>
                                                        <option value="Son" {{ $member->relationship_to_head == 'Son' ? 'selected' : '' }}>Son</option>
                                                        <option value="Daughter" {{ $member->relationship_to_head == 'Daughter' ? 'selected' : '' }}>Daughter</option>
                                                        <option value="Father" {{ $member->relationship_to_head == 'Father' ? 'selected' : '' }}>Father</option>
                                                        <option value="Mother" {{ $member->relationship_to_head == 'Mother' ? 'selected' : '' }}>Mother</option>
                                                        <option value="Brother" {{ $member->relationship_to_head == 'Brother' ? 'selected' : '' }}>Brother</option>
                                                        <option value="Sister" {{ $member->relationship_to_head == 'Sister' ? 'selected' : '' }}>Sister</option>
                                                        <option value="Grandfather" {{ $member->relationship_to_head == 'Grandfather' ? 'selected' : '' }}>Grandfather</option>
                                                        <option value="Grandmother" {{ $member->relationship_to_head == 'Grandmother' ? 'selected' : '' }}>Grandmother</option>
                                                        <option value="Grandson" {{ $member->relationship_to_head == 'Grandson' ? 'selected' : '' }}>Grandson</option>
                                                        <option value="Granddaughter" {{ $member->relationship_to_head == 'Granddaughter' ? 'selected' : '' }}>Granddaughter</option>
                                                        <option value="In-Law" {{ $member->relationship_to_head == 'In-Law' ? 'selected' : '' }}>In-Law</option>
                                                        <option value="Other Relative" {{ $member->relationship_to_head == 'Other Relative' ? 'selected' : '' }}>Other Relative</option>
                                                        <option value="Non-Relative" {{ $member->relationship_to_head == 'Non-Relative' ? 'selected' : '' }}>Non-Relative</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Date of Birth</label>
                                                    <input type="date" class="form-control member-dob" name="members[{{ $index }}][dob]" 
                                                           value="{{ $member->dob ? $member->dob->format('Y-m-d') : '' }}"
                                                           placeholder="Select date of birth" data-member-index="{{ $index }}">
                                                    @if(empty($member->dob))
                                                        <small class="text-muted">No date of birth recorded</small>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Age</label>
                                                    <input type="number" class="form-control member-age" name="members[{{ $index }}][age]" 
                                                           value="{{ $member->dob ? $member->age : '' }}" 
                                                           readonly style="background-color: #f8f9fa;"
                                                           placeholder="Calculated from birthdate">
                                                    <small class="text-muted">Auto-calculated from date of birth</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Gender <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="members[{{ $index }}][gender]" required>
                                                        <option value="">Select gender</option>
                                                        <option value="Male" {{ $member->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ $member->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                                        <option value="Non-binary" {{ $member->gender == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                                        <option value="Transgender" {{ $member->gender == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                                        <option value="Other" {{ $member->gender == 'Other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Civil Status <span class="text-danger">*</span></label>
                                                    <select class="form-control" name="members[{{ $index }}][civil_status]" required>
                                                        <option value="">Select status</option>
                                                        <option value="Single" {{ $member->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                                        <option value="Married" {{ $member->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                                        <option value="Widowed" {{ $member->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                                        <option value="Separated" {{ $member->civil_status == 'Separated' ? 'selected' : '' }}>Separated</option>
                                                        <option value="Divorced" {{ $member->civil_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Education</label>
                                                    <select class="form-control" name="members[{{ $index }}][education]">
                                                        <option value="">Select educational attainment</option>
                                                        <option value="No Formal Education" {{ $member->education == 'No Formal Education' ? 'selected' : '' }}>No Formal Education</option>
                                                        <option value="Elementary Undergraduate" {{ $member->education == 'Elementary Undergraduate' ? 'selected' : '' }}>Elementary Undergraduate</option>
                                                        <option value="Elementary Graduate" {{ $member->education == 'Elementary Graduate' ? 'selected' : '' }}>Elementary Graduate</option>
                                                        <option value="High School Undergraduate" {{ $member->education == 'High School Undergraduate' ? 'selected' : '' }}>High School Undergraduate</option>
                                                        <option value="High School Graduate" {{ $member->education == 'High School Graduate' ? 'selected' : '' }}>High School Graduate</option>
                                                        <option value="Vocational/Technical Graduate" {{ $member->education == 'Vocational/Technical Graduate' ? 'selected' : '' }}>Vocational/Technical Graduate</option>
                                                        <option value="College Undergraduate" {{ $member->education == 'College Undergraduate' ? 'selected' : '' }}>College Undergraduate</option>
                                                        <option value="College Graduate" {{ $member->education == 'College Graduate' ? 'selected' : '' }}>College Graduate</option>
                                                        <option value="Post Graduate" {{ $member->education == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Occupation <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="members[{{ $index }}][occupation]" 
                                                           value="{{ $member->occupation ?? '' }}" placeholder="e.g., Teacher, Farmer, Student" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Special Category</label>
                                                    <select class="form-control" name="members[{{ $index }}][category]">
                                                        <option value="">Select category (if applicable)</option>
                                                        <option value="Senior Citizen" {{ $member->category == 'Senior Citizen' ? 'selected' : '' }}>Senior Citizen (60+ years old)</option>
                                                        <option value="PWD" {{ $member->category == 'PWD' ? 'selected' : '' }}>Person with Disability (PWD)</option>
                                                        <option value="4Ps Beneficiary" {{ $member->category == '4Ps Beneficiary' ? 'selected' : '' }}>4Ps Beneficiary</option>
                                                        <option value="Solo Parent" {{ $member->category == 'Solo Parent' ? 'selected' : '' }}>Solo Parent</option>
                                                        <option value="Indigenous People" {{ $member->category == 'Indigenous People' ? 'selected' : '' }}>Indigenous People</option>
                                                        <option value="OFW" {{ $member->category == 'OFW' ? 'selected' : '' }}>Overseas Filipino Worker (OFW)</option>
                                                        <option value="Minor" {{ $member->category == 'Minor' ? 'selected' : '' }}>Minor (Below 18)</option>
                                                        <option value="Student" {{ $member->category == 'Student' ? 'selected' : '' }}>Student</option>
                                                        <option value="Other" {{ $member->category == 'Other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="callout callout-info" id="noMembersCallout">
                                <h5><i class="fas fa-info"></i> No Additional Members</h5>
                                <p>No household members recorded yet. Click "Add Member" to add family members to this household.</p>
                            </div>
                            <div id="membersContainer" style="display: none;"></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-footer bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('admin.residents.census-data') }}" class="btn btn-default">
                                    <i class="fas fa-arrow-left"></i> Back to Census Data
                                </a>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save"></i> Save All Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        </form>
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title text-white">
                    <i class="fas fa-exclamation-triangle"></i> Confirm Deletion
                </h4>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="callout callout-warning">
                    <h5><i class="fas fa-exclamation-triangle"></i> Warning!</h5>
                    <p>Are you sure you want to delete this household census record?</p>
                </div>
                <p>This action will permanently remove:</p>
                <ul>
                    <li>Household information for <strong>{{ $household->head_name }}</strong></li>
                    <li>All {{ $household->members->count() }} household member records</li>
                    <li>All associated data and cannot be undone</li>
                </ul>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    <i class="fas fa-times"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash-alt"></i> Delete Household
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div class="modal fade" id="viewDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info py-2">
                <h5 class="modal-title text-white mb-0">
                    <i class="fas fa-home"></i> Household #{{ $household->household_id }} - {{ $household->head_name }}
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-3">
                <!-- Compact Household Head Information -->
                <div class="card card-primary card-outline mb-3">
                    <div class="card-header py-2">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-crown"></i> Household Head
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div><strong><i class="fas fa-user mr-1"></i> Name:</strong> {{ $household->head_name }}</div>
                                <div><strong><i class="fas fa-birthday-cake mr-1"></i> Age:</strong> {{ $household->head_age ?? 'Not specified' }}</div>
                                <div><strong><i class="fas fa-briefcase mr-1"></i> Occupation:</strong> {{ $household->head_occupation ?? 'Not specified' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div><strong><i class="fas fa-venus-mars mr-1"></i> Gender:</strong> {{ $household->head_gender ?? 'Not specified' }}</div>
                                <div><strong><i class="fas fa-heart mr-1"></i> Civil Status:</strong> {{ $household->head_civil_status ?? 'Not specified' }}</div>
                                <div><strong><i class="fas fa-phone mr-1"></i> Contact:</strong> {{ $household->contact_number ?? 'Not provided' }}</div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div><strong><i class="fas fa-map-marker-alt mr-1"></i> Address:</strong> {{ $household->address ?? 'Not specified' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Compact Household Members -->
                <div class="card card-success card-outline mb-3">
                    <div class="card-header py-2">
                        <h6 class="card-title mb-0">
                            <i class="fas fa-users"></i> Household Members ({{ $household->members->count() }})
                        </h6>
                    </div>
                    <div class="card-body p-2">
                        @if($household->members->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm table-striped mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="5%">#</th>
                                            <th width="25%">Name</th>
                                            <th width="20%">Relationship</th>
                                            <th width="10%">Age</th>
                                            <th width="15%">Gender</th>
                                            <th width="25%">Occupation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($household->members as $index => $member)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <strong>{{ $member->fullname }}</strong>
                                                    @if($member->dob)
                                                        <br><em class="text-muted">{{ $member->dob->format('M d, Y') }}</em>
                                                    @endif
                                                </td>
                                                <td>{{ $member->relationship_to_head }}</td>
                                                <td>
                                                    @if($member->dob)
                                                        {{ $member->age }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>{{ $member->gender ?? 'N/A' }}</td>
                                                <td>{{ $member->occupation ?? 'N/A' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info py-2 mb-0">
                                <i class="fas fa-info-circle"></i> No members registered for this household.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>
<script>
let currentHouseholdId = null;
let memberIndex = {{ $household->members->count() }};

$(document).ready(function() {
    // Initialize member numbers and form field names
    updateMemberNumbers();
    
    // Calculate initial ages for existing members
    calculateAllAges();
    
    // Check if view_details parameter is in URL and show modal
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('view_details') === '1') {
        $('#viewDetailsModal').modal('show');
        // Clean up URL without reloading page
        history.replaceState({}, '', window.location.pathname);
    }
    
    // Remove DataTable initialization since we're now using forms
    
    // Handle adding new member
    $('#addMemberBtn').on('click', function() {
        addNewMember();
        updateMemberNumbers();
        
        // Hide no members callout if visible
        $('#noMembersCallout').hide();
        $('#membersContainer').show();
    });
    
    // Handle removing member
    $(document).on('click', '.remove-member', function() {
        $(this).closest('.member-card').remove();
        updateMemberNumbers();
        
        // Show no members callout if no members left
        if ($('.member-card').length === 0) {
            $('#noMembersCallout').show();
            $('#membersContainer').hide();
        }
    });
    
    // Handle date of birth changes to calculate age
    $(document).on('change', '.member-dob', function() {
        calculateAge($(this));
    });
});

function addNewMember() {
    const memberHtml = `
        <div class="member-card card card-outline mb-3" data-member-id="new">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="fas fa-user"></i> Member #<span class="member-number">${memberIndex + 1}</span>
                </h6>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-danger remove-member">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="members[${memberIndex}][fullname]" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Relationship to Head <span class="text-danger">*</span></label>
                            <select class="form-control" name="members[${memberIndex}][relationship_to_head]" required>
                                <option value="">Select relationship</option>
                                <option value="Spouse">Spouse</option>
                                <option value="Son">Son</option>
                                <option value="Daughter">Daughter</option>
                                <option value="Father">Father</option>
                                <option value="Mother">Mother</option>
                                <option value="Brother">Brother</option>
                                <option value="Sister">Sister</option>
                                <option value="Grandfather">Grandfather</option>
                                <option value="Grandmother">Grandmother</option>
                                <option value="Grandson">Grandson</option>
                                <option value="Granddaughter">Granddaughter</option>
                                <option value="In-Law">In-Law</option>
                                <option value="Other Relative">Other Relative</option>
                                <option value="Non-Relative">Non-Relative</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Date of Birth</label>
                            <input type="date" class="form-control member-dob" name="members[${memberIndex}][dob]" 
                                   data-member-index="${memberIndex}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Age</label>
                            <input type="number" class="form-control member-age" name="members[${memberIndex}][age]" 
                                   readonly style="background-color: #f8f9fa;"
                                   placeholder="Calculated from birthdate">
                            <small class="text-muted">Auto-calculated from date of birth</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Gender <span class="text-danger">*</span></label>
                            <select class="form-control" name="members[${memberIndex}][gender]" required>
                                <option value="">Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Non-binary">Non-binary</option>
                                <option value="Transgender">Transgender</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Civil Status <span class="text-danger">*</span></label>
                            <select class="form-control" name="members[${memberIndex}][civil_status]" required>
                                <option value="">Select status</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Widowed">Widowed</option>
                                <option value="Separated">Separated</option>
                                <option value="Divorced">Divorced</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Education</label>
                            <select class="form-control" name="members[${memberIndex}][education]">
                                <option value="">Select educational attainment</option>
                                <option value="No Formal Education">No Formal Education</option>
                                <option value="Elementary Undergraduate">Elementary Undergraduate</option>
                                <option value="Elementary Graduate">Elementary Graduate</option>
                                <option value="High School Undergraduate">High School Undergraduate</option>
                                <option value="High School Graduate">High School Graduate</option>
                                <option value="Vocational/Technical Graduate">Vocational/Technical Graduate</option>
                                <option value="College Undergraduate">College Undergraduate</option>
                                <option value="College Graduate">College Graduate</option>
                                <option value="Post Graduate">Post Graduate</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Occupation <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="members[${memberIndex}][occupation]" 
                                   placeholder="e.g., Teacher, Farmer, Student" required>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Special Category</label>
                            <select class="form-control" name="members[${memberIndex}][category]">
                                <option value="">Select category (if applicable)</option>
                                <option value="Senior Citizen">Senior Citizen (60+ years old)</option>
                                <option value="PWD">Person with Disability (PWD)</option>
                                <option value="4Ps Beneficiary">4Ps Beneficiary</option>
                                <option value="Solo Parent">Solo Parent</option>
                                <option value="Indigenous People">Indigenous People</option>
                                <option value="OFW">Overseas Filipino Worker (OFW)</option>
                                <option value="Minor">Minor (Below 18)</option>
                                <option value="Student">Student</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    $('#membersContainer').append(memberHtml);
    memberIndex++;
}

function updateMemberNumbers() {
    $('.member-card').each(function(index) {
        // Update visual member number
        $(this).find('.member-number').text(index + 1);
        
        // Update all form field names to have sequential indices
        $(this).find('input, select').each(function() {
            const name = $(this).attr('name');
            if (name && name.includes('members[')) {
                const newName = name.replace(/members\[\d+\]/, `members[${index}]`);
                $(this).attr('name', newName);
                
                // Update data-member-index for DOB fields
                if ($(this).hasClass('member-dob')) {
                    $(this).attr('data-member-index', index);
                }
            }
        });
    });
    
    // Update global memberIndex to be the next available index
    memberIndex = $('.member-card').length;
}

function calculateAge(dobInput) {
    const dobValue = dobInput.val();
    const memberCard = dobInput.closest('.member-card');
    const ageInput = memberCard.find('.member-age');
    
    if (dobValue) {
        const birthDate = new Date(dobValue);
        const today = new Date();
        
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        // Adjust age if birthday hasn't occurred this year
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        // Ensure age is not negative
        age = Math.max(0, age);
        
        ageInput.val(age);
    } else {
        ageInput.val('');
    }
}

function calculateAllAges() {
    $('.member-dob').each(function() {
        calculateAge($(this));
    });
}

function deleteHousehold(householdId) {
    currentHouseholdId = householdId;
    $('#deleteModal').modal('show');
}

$('#confirmDeleteBtn').on('click', function() {
    if (!currentHouseholdId) return;
    
    $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
    
    $.ajax({
        url: `/admin/residents/census-data/${currentHouseholdId}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            $('#deleteModal').modal('hide');
            
            // AdminLTE Toast Notification
            $(document).Toasts('create', {
                class: 'bg-success',
                title: 'Success',
                subtitle: 'Household Deleted',
                body: 'Household census record deleted successfully.',
                autohide: true,
                delay: 3000
            });
            
            setTimeout(() => {
                window.location.href = '{{ route("admin.residents.census-data") }}';
            }, 1500);
        },
        error: function(xhr, status, error) {
            console.error('Error deleting household:', error);
            
            // AdminLTE Toast Notification for error
            $(document).Toasts('create', {
                class: 'bg-danger',
                title: 'Error',
                subtitle: 'Deletion Failed',
                body: 'Failed to delete household census record.',
                autohide: true,
                delay: 5000
            });
            
            $('#confirmDeleteBtn').prop('disabled', false).html('<i class="fas fa-trash-alt"></i> Delete Household');
        }
    });
});

// Form submission with loading state
$('#householdEditForm').on('submit', function(e) {
    // Debug: Log form data before submission
    const formData = new FormData(this);
    console.log('Form submission data:');
    for (let [key, value] of formData.entries()) {
        console.log(key, ':', value);
    }
    
    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');
    
    // Re-enable button after 3 seconds as fallback
    setTimeout(() => {
        submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Save Changes');
    }, 3000);
});
</script>
@endpush
