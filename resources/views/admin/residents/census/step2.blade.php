@extends('layouts.admin.master')

@section('page-header', 'New Census Record')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.residents.census-data') }}">Census Data</a></li>
    <li class="breadcrumb-item active">New Census Record</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/registration-form.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Form -->
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">
                    <i class="fas fa-users mr-2"></i>
                    Household Members
                </strong>
                <div class="card-tools">
                    <span class="badge badge-light">Step 2 of 3</span>
                </div>
            </div>
            <form action="{{ route('admin.residents.census.step2.store') }}" method="POST" id="step2Form">
                @csrf
                <div class="card-body registration-form">
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">Household Members List</h5>
                        <div>
                            <button type="button" class="btn btn-success btn-sm" id="addExistingResident" data-toggle="modal" data-target="#residentModal">
                                <i class="fas fa-user-plus mr-2"></i>Add Registered Resident
                            </button>
                            <button type="button" class="btn btn-primary btn-sm ml-2" id="addMember">
                                <i class="fas fa-plus mr-2"></i>Add New Member
                            </button>
                        </div>
                    </div>

                    <div id="membersContainer">
                        <!-- Members will be added here dynamically -->
                        @if(old('members') || session('census.step2.members'))
                            @foreach(old('members', session('census.step2.members') ?? []) as $index => $member)
                                <div class="member-card card border-info mb-3" data-index="{{ $index }}">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-info">Household Member #{{ $index + 1 }}</h6>
                                        <button type="button" class="btn btn-danger btn-sm remove-member">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Full Name<span class="text-danger">*</span></label>
                                                    <input type="text" name="members[{{ $index }}][fullname]" 
                                                           class="form-control @error('members.'.$index.'.fullname') is-invalid @enderror" 
                                                           value="{{ $member['fullname'] ?? '' }}" required>
                                                    @error('members.'.$index.'.fullname')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Relationship to Head<span class="text-danger">*</span></label>
                                                    <select name="members[{{ $index }}][relationship_to_head]" 
                                                            class="form-control @error('members.'.$index.'.relationship_to_head') is-invalid @enderror" required>
                                                        <option value="">Select relationship</option>
                                                        <option value="Head" {{ (isset($member['relationship_to_head']) && $member['relationship_to_head'] == 'Head') ? 'selected' : '' }}>Head</option>
                                                        <option value="Spouse" {{ (isset($member['relationship_to_head']) && $member['relationship_to_head'] == 'Spouse') ? 'selected' : '' }}>Spouse</option>
                                                        <option value="Son" {{ (isset($member['relationship_to_head']) && $member['relationship_to_head'] == 'Son') ? 'selected' : '' }}>Son</option>
                                                        <option value="Daughter" {{ (isset($member['relationship_to_head']) && $member['relationship_to_head'] == 'Daughter') ? 'selected' : '' }}>Daughter</option>
                                                        <option value="Father" {{ (isset($member['relationship_to_head']) && $member['relationship_to_head'] == 'Father') ? 'selected' : '' }}>Father</option>
                                                        <option value="Mother" {{ (isset($member['relationship_to_head']) && $member['relationship_to_head'] == 'Mother') ? 'selected' : '' }}>Mother</option>
                                                        <option value="Brother" {{ (isset($member['relationship_to_head']) && $member['relationship_to_head'] == 'Brother') ? 'selected' : '' }}>Brother</option>
                                                        <option value="Sister" {{ (isset($member['relationship_to_head']) && $member['relationship_to_head'] == 'Sister') ? 'selected' : '' }}>Sister</option>
                                                        <option value="Grandfather" {{ (isset($member['relationship_to_head']) && $member['relationship_to_head'] == 'Grandfather') ? 'selected' : '' }}>Grandfather</option>
                                                        <option value="Grandmother" {{ (isset($member['relationship_to_head']) && $member['relationship_to_head'] == 'Grandmother') ? 'selected' : '' }}>Grandmother</option>
                                                        <option value="Grandson" {{ (isset($member['relationship_to_head']) && $member['relationship_to_head'] == 'Grandson') ? 'selected' : '' }}>Grandson</option>
                                                        <option value="Granddaughter" {{ (isset($member['relationship_to_head']) && $member['relationship_to_head'] == 'Granddaughter') ? 'selected' : '' }}>Granddaughter</option>
                                                        <option value="In-Law" {{ (isset($member['relationship_to_head']) && $member['relationship_to_head'] == 'In-Law') ? 'selected' : '' }}>In-Law</option>
                                                        <option value="Other Relative" {{ (isset($member['relationship_to_head']) && $member['relationship_to_head'] == 'Other Relative') ? 'selected' : '' }}>Other Relative</option>
                                                        <option value="Non-Relative" {{ (isset($member['relationship_to_head']) && $member['relationship_to_head'] == 'Non-Relative') ? 'selected' : '' }}>Non-Relative</option>
                                                    </select>
                                                    @error('members.'.$index.'.relationship_to_head')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Date of Birth<span class="text-danger">*</span></label>
                                                    <input type="date" name="members[{{ $index }}][dob]" 
                                                           class="form-control @error('members.'.$index.'.dob') is-invalid @enderror" 
                                                           value="{{ $member['dob'] ?? '' }}" max="{{ date('Y-m-d') }}" required>
                                                    @error('members.'.$index.'.dob')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Gender<span class="text-danger">*</span></label>
                                                    <select name="members[{{ $index }}][gender]" 
                                                            class="form-control @error('members.'.$index.'.gender') is-invalid @enderror" required>
                                                        <option value="">Select gender</option>
                                                        <option value="Male" {{ ($member['gender'] ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ ($member['gender'] ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                                    </select>
                                                    @error('members.'.$index.'.gender')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Civil Status<span class="text-danger">*</span></label>
                                                    <select name="members[{{ $index }}][civil_status]" 
                                                            class="form-control @error('members.'.$index.'.civil_status') is-invalid @enderror" required>
                                                        <option value="">Select status</option>
                                                        <option value="Single" {{ ($member['civil_status'] ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
                                                        <option value="Married" {{ ($member['civil_status'] ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
                                                        <option value="Widowed" {{ ($member['civil_status'] ?? '') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                                        <option value="Separated" {{ ($member['civil_status'] ?? '') == 'Separated' ? 'selected' : '' }}>Separated</option>
                                                        <option value="Divorced" {{ ($member['civil_status'] ?? '') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                                    </select>
                                                    @error('members.'.$index.'.civil_status')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Education</label>
                                                    <select name="members[{{ $index }}][education]" 
                                                            class="form-control @error('members.'.$index.'.education') is-invalid @enderror">
                                                        <option value="">Select education</option>
                                                        <option value="No Formal Education" {{ ($member['education'] ?? '') == 'No Formal Education' ? 'selected' : '' }}>No Formal Education</option>
                                                        <option value="Elementary Undergraduate" {{ ($member['education'] ?? '') == 'Elementary Undergraduate' ? 'selected' : '' }}>Elementary Undergraduate</option>
                                                        <option value="Elementary Graduate" {{ ($member['education'] ?? '') == 'Elementary Graduate' ? 'selected' : '' }}>Elementary Graduate</option>
                                                        <option value="High School Undergraduate" {{ ($member['education'] ?? '') == 'High School Undergraduate' ? 'selected' : '' }}>High School Undergraduate</option>
                                                        <option value="High School Graduate" {{ ($member['education'] ?? '') == 'High School Graduate' ? 'selected' : '' }}>High School Graduate</option>
                                                        <option value="Vocational/Technical" {{ ($member['education'] ?? '') == 'Vocational/Technical' ? 'selected' : '' }}>Vocational/Technical</option>
                                                        <option value="College Undergraduate" {{ ($member['education'] ?? '') == 'College Undergraduate' ? 'selected' : '' }}>College Undergraduate</option>
                                                        <option value="College Graduate" {{ ($member['education'] ?? '') == 'College Graduate' ? 'selected' : '' }}>College Graduate</option>
                                                        <option value="Post Graduate" {{ ($member['education'] ?? '') == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                                                    </select>
                                                    @error('members.'.$index.'.education')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Occupation</label>
                                                    <input type="text" name="members[{{ $index }}][occupation]" 
                                                           class="form-control @error('members.'.$index.'.occupation') is-invalid @enderror" 
                                                           value="{{ $member['occupation'] ?? '' }}" placeholder="e.g., Teacher, Farmer, Student">
                                                    @error('members.'.$index.'.occupation')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Citizenship</label>
                                                    <select name="members[{{ $index }}][citizenship]" 
                                                            class="form-control @error('members.'.$index.'.citizenship') is-invalid @enderror">
                                                        <option value="">Select citizenship</option>
                                                        <option value="Filipino" {{ ($member['citizenship'] ?? '') == 'Filipino' ? 'selected' : '' }}>Filipino</option>
                                                        <option value="American" {{ ($member['citizenship'] ?? '') == 'American' ? 'selected' : '' }}>American</option>
                                                        <option value="Chinese" {{ ($member['citizenship'] ?? '') == 'Chinese' ? 'selected' : '' }}>Chinese</option>
                                                        <option value="Korean" {{ ($member['citizenship'] ?? '') == 'Korean' ? 'selected' : '' }}>Korean</option>
                                                        <option value="Japanese" {{ ($member['citizenship'] ?? '') == 'Japanese' ? 'selected' : '' }}>Japanese</option>
                                                        <option value="Other" {{ ($member['citizenship'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                    @error('members.'.$index.'.citizenship')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Religion</label>
                                                    <input type="text" name="members[{{ $index }}][religion]" 
                                                           class="form-control @error('members.'.$index.'.religion') is-invalid @enderror" 
                                                           value="{{ $member['religion'] ?? '' }}" placeholder="e.g., Roman Catholic, Islam, Protestant">
                                                    @error('members.'.$index.'.religion')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Special Category</label>
                                                    <select name="members[{{ $index }}][category]" 
                                                            class="form-control @error('members.'.$index.'.category') is-invalid @enderror">
                                                        <option value="">Select category (if applicable)</option>
                                                        <option value="Senior Citizen" {{ ($member['category'] ?? '') == 'Senior Citizen' ? 'selected' : '' }}>Senior Citizen (60+ years old)</option>
                                                        <option value="PWD" {{ ($member['category'] ?? '') == 'PWD' ? 'selected' : '' }}>Person with Disability (PWD)</option>
                                                        <option value="4Ps Beneficiary" {{ ($member['category'] ?? '') == '4Ps Beneficiary' ? 'selected' : '' }}>4Ps Beneficiary</option>
                                                        <option value="Solo Parent" {{ ($member['category'] ?? '') == 'Solo Parent' ? 'selected' : '' }}>Solo Parent</option>
                                                        <option value="Indigenous People" {{ ($member['category'] ?? '') == 'Indigenous People' ? 'selected' : '' }}>Indigenous People</option>
                                                        <option value="OFW" {{ ($member['category'] ?? '') == 'OFW' ? 'selected' : '' }}>Overseas Filipino Worker (OFW)</option>
                                                        <option value="Minor" {{ ($member['category'] ?? '') == 'Minor' ? 'selected' : '' }}>Minor (Below 18)</option>
                                                        <option value="Student" {{ ($member['category'] ?? '') == 'Student' ? 'selected' : '' }}>Student</option>
                                                        <option value="Other" {{ ($member['category'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                    @error('members.'.$index.'.category')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="alert alert-info" id="noMembers" style="{{ old('members') || session('census.step2.members') ? 'display: none;' : '' }}">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>No household members added yet.</strong> Click "Add Household Member" to start adding members to this household.
                    </div>
                </div>

            <div class="card-footer bg-light">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('admin.residents.census.step1') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Step 1
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-primary">
                            Continue to Review <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Resident Selection Modal -->
<div class="modal fade" id="residentModal" tabindex="-1" role="dialog" aria-labelledby="residentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="residentModalLabel">
                    <i class="fas fa-search mr-2"></i>Select Registered Resident
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="residentSearch">Search Resident</label>
                    <input type="text" class="form-control" id="residentSearch" placeholder="Type name to search...">
                </div>
                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Select</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Source</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody id="residentsTableBody">
                            <tr>
                                <td colspan="6" class="text-center">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>Loading residents...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="addSelectedResident" disabled>
                    <i class="fas fa-user-plus mr-2"></i>Add Selected Resident
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Member Template (Hidden) -->
<template id="memberTemplate">
    <div class="member-card card border-info mb-3" data-index="">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-info">Household Member #<span class="member-number"></span></h6>
            <button type="button" class="btn btn-danger btn-sm remove-member">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Full Name<span class="text-danger">*</span></label>
                        <input type="text" name="members[INDEX][fullname]" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Relationship to Head<span class="text-danger">*</span></label>
                        <select name="members[INDEX][relationship_to_head]" class="form-control" required>
                            <option value="">Select relationship</option>
                            <option value="Head">Head</option>
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
                        <label class="form-label">Date of Birth<span class="text-danger">*</span></label>
                        <input type="date" name="members[INDEX][dob]" class="form-control" max="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Gender<span class="text-danger">*</span></label>
                        <select name="members[INDEX][gender]" class="form-control" required>
                            <option value="">Select gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Civil Status<span class="text-danger">*</span></label>
                        <select name="members[INDEX][civil_status]" class="form-control" required>
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
                        <label class="form-label">Education</label>
                        <select name="members[INDEX][education]" class="form-control">
                            <option value="">Select education</option>
                            <option value="No Formal Education">No Formal Education</option>
                            <option value="Elementary Undergraduate">Elementary Undergraduate</option>
                            <option value="Elementary Graduate">Elementary Graduate</option>
                            <option value="High School Undergraduate">High School Undergraduate</option>
                            <option value="High School Graduate">High School Graduate</option>
                            <option value="Vocational/Technical">Vocational/Technical</option>
                            <option value="College Undergraduate">College Undergraduate</option>
                            <option value="College Graduate">College Graduate</option>
                            <option value="Post Graduate">Post Graduate</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Occupation</label>
                        <input type="text" name="members[INDEX][occupation]" class="form-control" placeholder="e.g., Teacher, Farmer, Student">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Citizenship</label>
                        <select name="members[INDEX][citizenship]" class="form-control">
                            <option value="">Select citizenship</option>
                            <option value="Filipino">Filipino</option>
                            <option value="American">American</option>
                            <option value="Chinese">Chinese</option>
                            <option value="Korean">Korean</option>
                            <option value="Japanese">Japanese</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Religion</label>
                        <input type="text" name="members[INDEX][religion]" class="form-control" placeholder="e.g., Roman Catholic, Islam, Protestant">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Special Category</label>
                        <select name="members[INDEX][category]" class="form-control">
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
</template>
@endsection

@push('scripts')
<script src="{{ asset('js/form-validation.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize with the count of existing members
    let memberIndex = {{ count(old('members', session('census.step2.members') ?? [])) }};
    let selectedResident = null;
    let allResidents = [];
    
    const container = document.getElementById('membersContainer');
    const template = document.getElementById('memberTemplate');
    const noMembersAlert = document.getElementById('noMembers');
    const addButton = document.getElementById('addMember');
    const addExistingButton = document.getElementById('addExistingResident');
    const addSelectedButton = document.getElementById('addSelectedResident');
    const residentSearch = document.getElementById('residentSearch');
    const residentsTableBody = document.getElementById('residentsTableBody');

    function updateMemberNumbers() {
        const cards = container.querySelectorAll('.member-card');
        cards.forEach((card, index) => {
            const numberSpan = card.querySelector('.member-number');
            if (numberSpan) {
                numberSpan.textContent = index + 1;
            }
        });
    }

    function toggleNoMembersAlert() {
        const hasMembers = container.children.length > 0;
        noMembersAlert.style.display = hasMembers ? 'none' : 'block';
    }

    addButton.addEventListener('click', function() {
        const templateContent = template.content.cloneNode(true);
        const newCard = templateContent.querySelector('.member-card');
        
        // Update all INDEX placeholders with actual index
        const inputs = newCard.querySelectorAll('[name*="INDEX"]');
        inputs.forEach(input => {
            input.name = input.name.replace('INDEX', memberIndex);
        });
        
        newCard.setAttribute('data-index', memberIndex);
        
        container.appendChild(newCard);
        
        // Initialize validation for new fields - enhanced from senior registration
        const newFields = newCard.querySelectorAll('input[required], select[required], textarea[required]');
        newFields.forEach(function(field) {
            $(field).on('blur', function() {
                validateField($(this));
            });
        });
        
        // Initialize date of birth handler for new card
        const dobField = newCard.querySelector('input[name*="[dob]"]');
        if (dobField) {
            $(dobField).on('change', function() {
                handleDateOfBirthChange($(this));
            });
        }
        
        memberIndex++;
        
        updateMemberNumbers();
        toggleNoMembersAlert();
        
        // Add remove functionality to the new card
        const removeButton = newCard.querySelector('.remove-member');
        removeButton.addEventListener('click', function() {
            newCard.remove();
            updateMemberNumbers();
            toggleNoMembersAlert();
        });
    });

    // Add remove functionality to existing cards
    container.addEventListener('click', function(e) {
        if (e.target.closest('.remove-member')) {
            const card = e.target.closest('.member-card');
            card.remove();
            updateMemberNumbers();
            toggleNoMembersAlert();
        }
    });

    // Load residents when modal is opened
    $('#residentModal').on('show.bs.modal', function() {
        loadResidents();
    });
    
    // Search functionality
    residentSearch.addEventListener('input', function() {
        filterResidents(this.value);
    });
    
    // Add selected resident
    addSelectedButton.addEventListener('click', function() {
        if (selectedResident) {
            addResidentToForm(selectedResident);
            $('#residentModal').modal('hide');
            selectedResident = null;
            addSelectedButton.disabled = true;
        }
    });
    
    // Load residents from database
    function loadResidents() {
        // Show loading state
        residentsTableBody.innerHTML = '<tr><td colspan="5" class="text-center"><i class="fas fa-spinner fa-spin mr-2"></i>Loading residents and senior citizens...</td></tr>';
        
        // Fetch both regular residents and senior citizens
        Promise.all([
            fetch('{{ route("admin.residents.api.all") }}').then(response => response.json()),
            fetch('{{ route("admin.senior-citizens.api.all") }}').then(response => response.json())
        ])
        .then(([residents, seniors]) => {
            // Combine and format both datasets
            const allPeople = [
                ...residents.map(resident => ({
                    id: `resident_${resident.id}`,
                    type: 'resident',
                    full_name: `${resident.first_name} ${resident.middle_name ? resident.middle_name + ' ' : ''}${resident.last_name}`,
                    age: resident.age,
                    gender: resident.gender,
                    address: resident.address,
                    birthdate: resident.birthdate,
                    civil_status: resident.civil_status,
                    educational_attainment: resident.educational_attainment,
                    profession_occupation: resident.profession_occupation,
                    source: 'Regular Resident'
                })),
                ...seniors.map(senior => ({
                    id: `senior_${senior.id}`,
                    type: 'senior',
                    full_name: `${senior.first_name} ${senior.middle_name ? senior.middle_name + ' ' : ''}${senior.last_name}`,
                    age: senior.age,
                    gender: senior.gender,
                    address: senior.address,
                    birthdate: senior.birthdate,
                    civil_status: senior.civil_status,
                    educational_attainment: senior.educational_attainment,
                    profession_occupation: senior.profession_occupation,
                    source: 'Senior Citizen'
                }))
            ];
            
            allResidents = allPeople;
            displayResidents(allResidents);
        })
        .catch(error => {
            console.error('Error loading residents:', error);
            residentsTableBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error loading residents and senior citizens</td></tr>';
        });
    }
    
    // Display residents in table
    function displayResidents(residents) {
        if (residents.length === 0) {
            residentsTableBody.innerHTML = '<tr><td colspan="6" class="text-center">No residents found</td></tr>';
            return;
        }
        
        const existingMembers = container.querySelectorAll('.member-card input[name*="[fullname]"]');
        const existingNames = Array.from(existingMembers).map(input => input.value.toLowerCase());
        
        residentsTableBody.innerHTML = residents.map(resident => {
            const isAlreadyAdded = existingNames.includes(resident.full_name.toLowerCase());
            const disabledClass = isAlreadyAdded ? 'text-muted' : '';
            const disabledAttr = isAlreadyAdded ? 'disabled' : '';
            
            // Get source badge color
            const sourceBadge = resident.source === 'Senior Citizen' 
                ? '<span class="badge badge-success">Senior Citizen</span>'
                : '<span class="badge badge-primary">Regular Resident</span>';
            
            return `
                <tr class="${disabledClass}">
                    <td>
                        <input type="radio" name="selectedResident" value="${resident.id}" 
                               ${disabledAttr} ${isAlreadyAdded ? '' : 'onclick="selectResident(\'' + resident.id + '\')"'}>
                    </td>
                    <td>${resident.full_name} ${isAlreadyAdded ? '<small class="text-muted">(Already added)</small>' : ''}</td>
                    <td>${resident.age || 'N/A'}</td>
                    <td>${resident.gender || 'N/A'}</td>
                    <td>${sourceBadge}</td>
                    <td>${resident.address || 'N/A'}</td>
                </tr>
            `;
        }).join('');
    }
    
    // Filter residents based on search
    function filterResidents(searchTerm) {
        const filtered = allResidents.filter(resident => 
            resident.full_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (resident.address && resident.address.toLowerCase().includes(searchTerm.toLowerCase()))
        );
        displayResidents(filtered);
    }
    
    // Select resident function (global scope needed for onclick)
    window.selectResident = function(residentId) {
        selectedResident = allResidents.find(r => r.id === residentId);
        addSelectedButton.disabled = false;
    };
    
    // Add resident to form
    function addResidentToForm(resident) {
        const templateContent = template.content.cloneNode(true);
        const newCard = templateContent.querySelector('.member-card');
        
        // Update all INDEX placeholders with actual index
        const inputs = newCard.querySelectorAll('[name*="INDEX"]');
        inputs.forEach(input => {
            input.name = input.name.replace('INDEX', memberIndex);
        });
        
        newCard.setAttribute('data-index', memberIndex);
        
        // Populate fields with resident data
        newCard.querySelector('input[name*="[fullname]"]').value = resident.full_name;
        newCard.querySelector('input[name*="[dob]"]').value = resident.birthdate || '';
        
        if (resident.gender) {
            const genderSelect = newCard.querySelector('select[name*="[gender]"]');
            genderSelect.value = resident.gender;
        }
        
        if (resident.civil_status) {
            const civilStatusSelect = newCard.querySelector('select[name*="[civil_status]"]');
            civilStatusSelect.value = resident.civil_status;
        }
        
        if (resident.educational_attainment) {
            const educationSelect = newCard.querySelector('select[name*="[education]"]');
            educationSelect.value = resident.educational_attainment;
        }
        
        if (resident.profession_occupation) {
            newCard.querySelector('input[name*="[occupation]"]').value = resident.profession_occupation;
        }
        
        // Set default citizenship to Filipino if not specified
        const citizenshipSelect = newCard.querySelector('select[name*="[citizenship]"]');
        if (citizenshipSelect) {
            citizenshipSelect.value = resident.citizenship || 'Filipino';
        }
        
        // Set religion if available
        if (resident.religion) {
            const religionInput = newCard.querySelector('input[name*="[religion]"]');
            if (religionInput) {
                religionInput.value = resident.religion;
            }
        }
        
        container.appendChild(newCard);
        
        // Initialize validation for new fields
        const newFields = newCard.querySelectorAll('input[required], select[required], textarea[required]');
        newFields.forEach(function(field) {
            $(field).on('blur', function() {
                validateField($(this));
            });
        });
        
        // Initialize date of birth handler for new card
        const dobField = newCard.querySelector('input[name*="[dob]"]');
        if (dobField && dobField.value) {
            $(dobField).on('change', function() {
                handleDateOfBirthChange($(this));
            });
            // Trigger age calculation immediately
            handleDateOfBirthChange($(dobField));
        }
        
        memberIndex++;
        updateMemberNumbers();
        toggleNoMembersAlert();
        
        // Add remove functionality to the new card
        const removeButton = newCard.querySelector('.remove-member');
        removeButton.addEventListener('click', function() {
            newCard.remove();
            updateMemberNumbers();
            toggleNoMembersAlert();
        });
    }
    
    // Initialize
    updateMemberNumbers();
    toggleNoMembersAlert();
    
    // Age calculation function - from senior registration
    function calculateAge(birthdate) {
        var birthDate = new Date(birthdate);
        var today = new Date();
        var age = today.getFullYear() - birthDate.getFullYear();
        var monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        return age;
    }
    
    // Date of birth change handler - from senior registration
    function handleDateOfBirthChange($dobField) {
        var birthdate = $dobField.val();
        if (!birthdate) return;
        
        var age = calculateAge(birthdate);
        var memberCard = $dobField.closest('.member-card');
        var categorySelect = memberCard.find('select[name*="[category]"]');
        
        // Show age info
        if (age >= 0) {
            var ageText = age + ' years old';
            var badges = [];
            
            if (age >= 60) {
                badges.push('<span class="badge badge-success">Senior Citizen</span>');
            }
            if (age < 18) {
                badges.push('<span class="badge badge-info">Minor</span>');
            }
            
            if (badges.length > 0) {
                ageText += ' ' + badges.join(' ');
            }
            
            // Add age display if it doesn't exist
            var ageDisplayId = 'age-display-' + memberCard.data('index');
            if ($('#' + ageDisplayId).length === 0) {
                $dobField.after('<small id="' + ageDisplayId + '" class="form-text text-muted mt-1"></small>');
            }
            $('#' + ageDisplayId).html('Age: ' + ageText);
            
            // Auto-select appropriate category
            if (age >= 60 && categorySelect.val() === '') {
                categorySelect.val('Senior Citizen');
                validateField(categorySelect);
            } else if (age < 18 && categorySelect.val() === '') {
                categorySelect.val('Minor');
                validateField(categorySelect);
            }
        }
    }
    
    // Helper functions for validation - enhanced from senior registration
    function validateField($field) {
        var value = $field.val().trim();
        var isRequired = $field.prop('required');
        var fieldName = $field.attr('name');
        
        if (isRequired && !value) {
            setFieldError($field, 'This field is required.');
            return false;
        }
        
        // Specific field validations
        if (fieldName && fieldName.includes('[fullname]')) {
            if (value && !isValidName(value)) {
                setFieldError($field, 'Please enter a valid name (letters, spaces, and common punctuation only).');
                return false;
            }
        }
        
        if (fieldName && fieldName.includes('[dob]')) {
            if (value && !isValidBirthdate(value)) {
                setFieldError($field, 'Please enter a valid birthdate.');
                return false;
            }
        }
        
        // If field has value or is not required, show success
        if (value || !isRequired) {
            setFieldSuccess($field);
        }
        return true;
    }
    
    // Name validation function - from senior registration
    function isValidName(name) {
        // Allow letters, spaces, apostrophes, hyphens, and periods
        var namePattern = /^[a-zA-Z\s\'\-\.]+$/;
        return namePattern.test(name) && name.length >= 2;
    }
    
    // Birthdate validation function - from senior registration
    function isValidBirthdate(birthdate) {
        var today = new Date();
        var birthDate = new Date(birthdate);
        
        // Check if birthdate is not in the future
        if (birthDate > today) {
            return false;
        }
        
        // Check if birthdate is reasonable (not more than 150 years ago)
        var minDate = new Date();
        minDate.setFullYear(today.getFullYear() - 150);
        
        return birthDate >= minDate;
    }
    
    function setFieldError($field, message) {
        $field.removeClass('is-valid').addClass('is-invalid');
        $field.closest('.form-group').addClass('has-error').removeClass('has-success');
        
        var $feedback = $field.siblings('.invalid-feedback');
        if ($feedback.length === 0) {
            $feedback = $('<div class="invalid-feedback"></div>');
            $field.after($feedback);
        }
        $feedback.text(message).show();
        $field.siblings('.valid-feedback').hide();
    }
    
    function setFieldSuccess($field) {
        $field.removeClass('is-invalid').addClass('is-valid');
        $field.closest('.form-group').addClass('has-success').removeClass('has-error');
        $field.siblings('.invalid-feedback').hide();
    }
    
    function clearFieldError($field) {
        $field.removeClass('is-invalid is-valid');
        $field.closest('.form-group').removeClass('has-error has-success');
        $field.siblings('.invalid-feedback, .valid-feedback').hide();
    }
    
    // Form validation - exactly like step 1
    $('#step2Form').on('submit', function(e) {
        const memberCards = document.querySelectorAll('.member-card');
        if (memberCards.length === 0) {
            e.preventDefault();
            alert('Please add at least one household member before continuing.');
            return false;
        }
        
        let isValid = true;
        let errorMessage = '';
        
        // Validate all required fields in member cards
        memberCards.forEach((card, index) => {
            const requiredInputs = card.querySelectorAll('input[required], select[required]');
            
            requiredInputs.forEach(function(field) {
                const fieldValue = field.value.trim();
                let fieldName = '';
                
                // Get field name from label
                const label = card.querySelector(`label[for="${field.id}"]`) || 
                             field.closest('.form-group').querySelector('label');
                if (label) {
                    fieldName = label.textContent.replace('*', '').trim();
                } else {
                    fieldName = field.name.split('[').pop().replace(']', '');
                }
                
                if (!fieldValue) {
                    isValid = false;
                    errorMessage += `Member ${index + 1} ${fieldName} is required.\n`;
                    $(field).addClass('is-invalid');
                } else {
                    $(field).removeClass('is-invalid');
                }
            });
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please correct the following errors:\n\n' + errorMessage);
            return false;
        }
        
        return true;
    });
    
    // Date of birth change handlers - from senior registration
    $(document).on('change', '#membersContainer input[name*="[dob]"]', function() {
        handleDateOfBirthChange($(this));
    });
    
    // Initialize age calculation for existing fields
    $('#membersContainer input[name*="[dob]"]').each(function() {
        if ($(this).val()) {
            handleDateOfBirthChange($(this));
        }
    });
    
    // Real-time validation on input/change - enhanced from senior registration
    $(document).on('input change', '#membersContainer input, #membersContainer select, #membersContainer textarea', function() {
        var $field = $(this);
        var value = $field.val().trim();
        
        // Clear previous validation state
        clearFieldError($field);
        
        // If field has value, validate it
        if (value) {
            validateField($field);
        }
    });
    
    // Real-time validation for existing fields
    $('#membersContainer input[required], #membersContainer select[required], #membersContainer textarea[required]').each(function() {
        $(this).on('blur', function() {
            validateField($(this));
        });
    });
    
    // Enhanced form interaction handling - from senior registration
    $(document).on('focus click', '#membersContainer .form-control', function() {
        $(this).removeClass('is-invalid');
        $(this).siblings('.invalid-feedback').hide();
    });
});
</script>
@endpush
