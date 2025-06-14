@extends('layouts.admin.master')

@section('title', 'Edit Resident')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h3 page-title">View & Update Resident</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.residents.index') }}" class="btn btn-primary mr-2">
                        <i class="fe fe-arrow-left mr-2"></i> Back to List
                    </a>
                    <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" type="button">
                        <i class="fe fe-trash mr-2 text-white"></i> Delete
                    </button>
                </div>
            </div>

            <!-- Display validation errors if any -->
            @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Error!</strong> Please check the form fields below.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.residents.update', $resident) }}" method="POST">
                @csrf
                @method('PUT')
                
                <!-- Resident Information Card -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Resident Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-4 text-center">
                                <div class="avatar avatar-xl mb-3">
                                    @if($resident->photo)
                                        <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}" class="avatar-img rounded-circle">
                                    @else
                                        <div class="avatar-letter rounded-circle bg-primary">{{ substr($resident->first_name, 0, 1) }}</div>
                                    @endif
                                </div>
                                <div class="mt-1">
                                    <p class="small text-muted mb-1">Barangay ID</p>
                                    <h5 class="mb-0">{{ $resident->barangay_id }}</h5>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="type_of_resident">Type of Resident</label>
                                        <select class="form-control" id="type_of_resident" name="type_of_resident">
                                            <option value="Non-Migrant" {{ $resident->type_of_resident == 'Non-Migrant' ? 'selected' : '' }}>Non-Migrant</option>
                                            <option value="Migrant" {{ $resident->type_of_resident == 'Migrant' ? 'selected' : '' }}>Migrant</option>
                                            <option value="Transient" {{ $resident->type_of_resident == 'Transient' ? 'selected' : '' }}>Transient</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="first_name">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $resident->first_name) }}" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="middle_name">Middle Name</label>
                                        <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ old('middle_name', $resident->middle_name) }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $resident->last_name) }}" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="suffix">Suffix</label>
                                        <input type="text" class="form-control" id="suffix" name="suffix" value="{{ old('suffix', $resident->suffix) }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="birthdate">Birthdate</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" value="{{ old('birthdate', $resident->birthdate ? $resident->birthdate->format('Y-m-d') : '') }}" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="birthplace">Birthplace</label>
                                        <input type="text" class="form-control" id="birthplace" name="birthplace" value="{{ old('birthplace', $resident->birthplace) }}" required>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="sex">Gender</label>
                                        <select class="form-control" id="sex" name="sex">
                                            <option value="Male" {{ $resident->sex == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ $resident->sex == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="civil_status">Civil Status</label>
                                        <select class="form-control" id="civil_status" name="civil_status">
                                            <option value="Single" {{ $resident->civil_status == 'Single' ? 'selected' : '' }}>Single</option>
                                            <option value="Married" {{ $resident->civil_status == 'Married' ? 'selected' : '' }}>Married</option>
                                            <option value="Widowed" {{ $resident->civil_status == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                            <option value="Separated" {{ $resident->civil_status == 'Separated' ? 'selected' : '' }}>Separated</option>
                                            <option value="Divorced" {{ $resident->civil_status == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="address">Address</label>
                                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address', $resident->address) }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="contact_number">Contact Number</label>
                                        <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ old('contact_number', $resident->contact_number) }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="email_address">Email Address</label>
                                        <input type="email" class="form-control" id="email_address" name="email_address" value="{{ old('email_address', $resident->email_address) }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Information Card -->
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Additional Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="citizenship_type">Citizenship Type</label>
                                <select class="form-control" id="citizenship_type" name="citizenship_type">
                                    <option value="FILIPINO" {{ $resident->citizenship_type == 'FILIPINO' ? 'selected' : '' }}>Filipino</option>
                                    <option value="Dual Citizen" {{ $resident->citizenship_type == 'Dual Citizen' ? 'selected' : '' }}>Dual Citizen</option>
                                    <option value="Foreigner" {{ $resident->citizenship_type == 'Foreigner' ? 'selected' : '' }}>Foreigner</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="citizenship_country">Country</label>
                                <input type="text" class="form-control" id="citizenship_country" name="citizenship_country" value="{{ old('citizenship_country', $resident->citizenship_country) }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="religion">Religion</label>
                                <input type="text" class="form-control" id="religion" name="religion" value="{{ old('religion', $resident->religion) }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="philsys_id">PhilSys ID</label>
                                <input type="text" class="form-control" id="philsys_id" name="philsys_id" value="{{ old('philsys_id', $resident->philsys_id) }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="profession_occupation">Occupation/Profession</label>
                                <input type="text" class="form-control" id="profession_occupation" name="profession_occupation" value="{{ old('profession_occupation', $resident->profession_occupation) }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="monthly_income">Monthly Income</label>
                                <input type="number" step="0.01" class="form-control" id="monthly_income" name="monthly_income" value="{{ old('monthly_income', $resident->monthly_income) }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="educational_attainment">Highest Educational Attainment</label>
                                <select class="form-control" id="educational_attainment" name="educational_attainment">
                                    <option value="">-- Select Attainment --</option>
                                    <option value="Elementary" {{ $resident->educational_attainment == 'Elementary' ? 'selected' : '' }}>Elementary</option>
                                    <option value="Highschool" {{ $resident->educational_attainment == 'Highschool' ? 'selected' : '' }}>Highschool</option>
                                    <option value="College" {{ $resident->educational_attainment == 'College' ? 'selected' : '' }}>College</option>
                                    <option value="Post Graduate" {{ $resident->educational_attainment == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                                    <option value="Vocational" {{ $resident->educational_attainment == 'Vocational' ? 'selected' : '' }}>Vocational</option>
                                    <option value="not applicable" {{ $resident->educational_attainment == 'not applicable' ? 'selected' : '' }}>Not Applicable</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="education_status">Education Status</label>
                                <select class="form-control" id="education_status" name="education_status">
                                    <option value="">-- Select Status --</option>
                                    <option value="Graduate" {{ $resident->education_status == 'Graduate' ? 'selected' : '' }}>Graduate</option>
                                    <option value="Undergraduate" {{ $resident->education_status == 'Undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                                    <option value="not applicable" {{ $resident->education_status == 'not applicable' ? 'selected' : '' }}>Not Applicable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <!-- Mother's Information -->
                    <div class="col-md-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Mother's Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="mother_first_name">First Name</label>
                                        <input type="text" class="form-control" id="mother_first_name" name="mother_first_name" value="{{ old('mother_first_name', $resident->mother_first_name) }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="mother_middle_name">Middle Name</label>
                                        <input type="text" class="form-control" id="mother_middle_name" name="mother_middle_name" value="{{ old('mother_middle_name', $resident->mother_middle_name) }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="mother_last_name">Last Name</label>
                                        <input type="text" class="form-control" id="mother_last_name" name="mother_last_name" value="{{ old('mother_last_name', $resident->mother_last_name) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Population Sectors -->
                    <div class="col-md-6">
                        <div class="card shadow">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Population Sectors</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach(App\Models\Resident::getPopulationSectors() as $sector)
                                        <div class="col-md-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input" 
                                                    id="sector_{{ Str::slug($sector) }}" 
                                                    name="population_sectors[]" 
                                                    value="{{ $sector }}" 
                                                    {{ is_array($resident->population_sectors) && in_array($sector, $resident->population_sectors) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="sector_{{ Str::slug($sector) }}">{{ $sector }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="text-right">
                                    <a href="{{ route('admin.residents.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this resident? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.residents.destroy', $resident) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
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
        width: 100px;
        height: 100px;
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
        font-size: 36px;
        font-weight: bold;
    }
</style>
@endpush