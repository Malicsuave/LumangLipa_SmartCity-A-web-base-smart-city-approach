@extends('layouts.admin.master')

@section('page-header', 'New Census Record')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.residents.census-data') }}">Census Data</a></li>
    <li class="breadcrumb-item active">New Census Record</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/registration-form.css') }}">
<style>
/* Fix modal table overflow and responsiveness */
#residentModal .modal-body {
    overflow-x: auto;
    max-width: 100vw;
}
#residentModal .resident-modal-table-wrapper {
    max-height: 60vh;
    min-width: 700px;
    overflow-x: auto;
}
#residentModal .resident-modal-table {
    min-width: 700px;
}
@media (max-width: 900px) {
    #residentModal .resident-modal-table th, #residentModal .resident-modal-table td {
        padding: 0.5rem 0.25rem;
        font-size: 0.95rem;
        max-width: 120px;
    }
    #residentModal .resident-modal-table {
        font-size: 0.95rem;
    }
}
@media (max-width: 576px) {
    #residentModal .resident-modal-table th, #residentModal .resident-modal-table td {
        padding: 0.375rem 0.15rem;
        font-size: 0.85rem;
        max-width: 80px;
    }
    #residentModal .resident-modal-table {
        font-size: 0.85rem;
    }
}
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
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
                        @if(old('members') || session('census.step2.members'))
                            @foreach(old('members', session('census.step2.members') ?? []) as $index => $member)
                                <div class="member-card card border-info mb-3" data-index="{{ $index }}">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-info">Household Member #<span class="member-number">{{ $index + 1 }}</span></h6>
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
                                                           class="form-control custom-rounded-input @error('members.'.$index.'.fullname') is-invalid @enderror"
                                                           value="{{ $member['fullname'] ?? '' }}" required>
                                                    @error('members.'.$index.'.fullname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Relationship to Head<span class="text-danger">*</span></label>
                                                    <select name="members[{{ $index }}][relationship_to_head]"
                                                            class="form-control custom-rounded-input @error('members.'.$index.'.relationship_to_head') is-invalid @enderror" required>
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
                                                    @error('members.'.$index.'.relationship_to_head') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Birthdate</label>
                                                    <input type="date" name="members[{{ $index }}][birthdate]"
                                                           class="form-control custom-rounded-input birthdate-input @error('members.'.$index.'.birthdate') is-invalid @enderror"
                                                           value="{{ $member['birthdate'] ?? '' }}" {{ isset($member['is_fetched']) && $member['is_fetched'] ? 'disabled' : '' }}>
                                                    @error('members.'.$index.'.birthdate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Age</label>
                                                    <input type="number" name="members[{{ $index }}][age]" id="age_{{ $index }}" class="form-control custom-rounded-input"
                                                           value="{{ $member['age'] ?? '' }}" placeholder="Enter age">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Gender<span class="text-danger">*</span></label>
                                                    <select name="members[{{ $index }}][gender]"
                                                            class="form-control custom-rounded-input @error('members.'.$index.'.gender') is-invalid @enderror" required>
                                                        <option value="">Select gender</option>
                                                        <option value="Male" {{ ($member['gender'] ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ ($member['gender'] ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                                        <option value="Non-binary" {{ ($member['gender'] ?? '') == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                                        <option value="Transgender" {{ ($member['gender'] ?? '') == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                                        <option value="Other" {{ ($member['gender'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                                    </select>
                                                    @error('members.'.$index.'.birthdate') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Civil Status<span class="text-danger">*</span></label>
                                                    <select name="members[{{ $index }}][civil_status]"
                                                            class="form-control custom-rounded-input @error('members.'.$index.'.civil_status') is-invalid @enderror" required>
                                                        <option value="">Select status</option>
                                                        <option value="Single" {{ ($member['civil_status'] ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
                                                        <option value="Married" {{ ($member['civil_status'] ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
                                                        <option value="Widowed" {{ ($member['civil_status'] ?? '') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                                        <option value="Separated" {{ ($member['civil_status'] ?? '') == 'Separated' ? 'selected' : '' }}>Separated</option>
                                                        <option value="Divorced" {{ ($member['civil_status'] ?? '') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                                    </select>
                                                    @error('members.'.$index.'.civil_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Education</label>
                                                    <select name="members[{{ $index }}][education]"
                                                            class="form-control custom-rounded-input @error('members.'.$index.'.education') is-invalid @enderror">
                                                        <option value="">Select educational attainment</option>
                                                        <option value="No Formal Education" {{ ($member['education'] ?? '') == 'No Formal Education' ? 'selected' : '' }}>No Formal Education</option>
                                                        <option value="Elementary Undergraduate" {{ ($member['education'] ?? '') == 'Elementary Undergraduate' ? 'selected' : '' }}>Elementary Undergraduate</option>
                                                        <option value="Elementary Graduate" {{ ($member['education'] ?? '') == 'Elementary Graduate' ? 'selected' : '' }}>Elementary Graduate</option>
                                                        <option value="High School Undergraduate" {{ ($member['education'] ?? '') == 'High School Undergraduate' ? 'selected' : '' }}>High School Undergraduate</option>
                                                        <option value="High School Graduate" {{ ($member['education'] ?? '') == 'High School Graduate' ? 'selected' : '' }}>High School Graduate</option>
                                                        <option value="Vocational/Technical Graduate" {{ ($member['education'] ?? '') == 'Vocational/Technical Graduate' ? 'selected' : '' }}>Vocational/Technical Graduate</option>
                                                        <option value="College Undergraduate" {{ ($member['education'] ?? '') == 'College Undergraduate' ? 'selected' : '' }}>College Undergraduate</option>
                                                        <option value="College Graduate" {{ ($member['education'] ?? '') == 'College Graduate' ? 'selected' : '' }}>College Graduate</option>
                                                        <option value="Post Graduate" {{ ($member['education'] ?? '') == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                                                    </select>
                                                    @error('members.'.$index.'.education') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Occupation<span class="text-danger">*</span></label>
                                                    <input type="text" name="members[{{ $index }}][occupation]"
                                                           class="form-control custom-rounded-input @error('members.'.$index.'.occupation') is-invalid @enderror"
                                                           value="{{ $member['occupation'] ?? '' }}" placeholder="e.g., Teacher, Farmer, Student" required>
                                                    @error('members.'.$index.'.occupation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Special Category</label>
                                                    <select name="members[{{ $index }}][category]"
                                                            class="form-control custom-rounded-input @error('members.'.$index.'.category') is-invalid @enderror">
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
                                                    @error('members.'.$index.'.category') <div class="invalid-feedback">{{ $message }}</div> @enderror
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
                        <strong>No household members added yet.</strong> Click "Add New Member" to start adding members to this household.
                        <br><small>You can continue to the next step without adding members if this household only has one person (the head).</small>
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

<template id="memberTemplate">
    <div class="member-card card border-info mb-3" data-index="INDEX">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-info">Household Member #<span class="member-number">INDEX</span></h6>
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
                        <label class="form-label">Birthdate</label>
                        <input type="date" name="members[INDEX][birthdate]" class="form-control birthdate-input">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">Age</label>
                        <input type="number" name="members[INDEX][age]" id="age_INDEX" class="form-control" placeholder="Enter age">
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
                             <option value="Non-binary">Non-binary</option>
                             <option value="Transgender">Transgender</option>
                             <option value="Other">Other</option>
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
                        <label class="form-label">Occupation<span class="text-danger">*</span></label>
                        <input type="text" name="members[INDEX][occupation]" class="form-control" placeholder="e.g., Teacher, Farmer, Student" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
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
                <div class="table-responsive resident-modal-table-wrapper" style="max-height:60vh;">
                    <table class="table table-hover table-striped table-bordered resident-modal-table">
                        <thead class="thead-light sticky-top" style="background: #f8f9fa;">
                            <tr>
                                <th style="width:60px;">Select</th>
                                <th style="min-width:140px;">Name</th>
                                <th style="min-width:60px;">Age</th>
                                <th style="min-width:100px;">Gender</th>
                                <th style="min-width:120px;">Source</th>
                                <th style="min-width:200px;">Address</th>
                            </tr>
                        </thead>
                        <tbody id="residentsTableBody">
                            <tr>
                                <td colspan="6" class="text-center text-muted">Type a name to search for residents.</td>
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
@endsection

@push('scripts')
{{-- <script src="{{ asset('js/form-validation.js') }}"></script> --}} {{-- Include if needed --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    let memberIndex = {{ count(old('members', session('census.step2.members') ?? [])) }};
    let selectedResident = null;
    let allResidents = [];

    const container = document.getElementById('membersContainer');
    const template = document.getElementById('memberTemplate');
    const noMembersAlert = document.getElementById('noMembers');
    const addButton = document.getElementById('addMember');
    const addExistingButton = document.getElementById('addExistingResident');
    const residentSearch = document.getElementById('residentSearch');
    const residentsTableBody = document.getElementById('residentsTableBody');

    function updateMemberNumbers() {
        const cards = container.querySelectorAll('.member-card');
        cards.forEach((card, index) => {
            const numberSpan = card.querySelector('.member-number');
            if (numberSpan) numberSpan.textContent = index + 1;
            card.setAttribute('data-index', index);
            const inputs = card.querySelectorAll('[name*="members["]');
            inputs.forEach(input => {
                input.name = input.name.replace(/members\[\d+\]/g, `members[${index}]`);
            });
        });
        memberIndex = cards.length;
    }

    function toggleNoMembersAlert() {
        if (noMembersAlert) {
            noMembersAlert.style.display = container.children.length > 0 ? 'none' : 'block';
        }
    }

    function calculateAge(birthdate) {
        if (!birthdate) return '';
        const birth = new Date(birthdate);
        const today = new Date();
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        return age;
    }

    function updateAge(input) {
        const birthdate = input.value;
        const ageInput = input.closest('.member-card').querySelector('input[id^="age"]');
        if (ageInput) {
            ageInput.value = calculateAge(birthdate);
        }
    }

    addButton.addEventListener('click', function() {
        if (!template || !template.content) { console.error('Member template not found.'); alert('Error: Member template not found.'); return; }
        if (!container) { console.error('Members container not found.'); alert('Error: Members container not found.'); return; }

        const templateContent = document.importNode(template.content, true);
        const newCard = templateContent.querySelector('.member-card');
        if (!newCard) { console.error('Member card template is invalid.'); alert('Error: Member card template is invalid.'); return; }

        const currentIndex = memberIndex;
        const inputs = newCard.querySelectorAll('[name*="INDEX"]');
        inputs.forEach(input => { input.name = input.name.replace(/\[INDEX\]/g, `[${currentIndex}]`); });

        const numberSpan = newCard.querySelector('.member-number');
        if(numberSpan) numberSpan.textContent = currentIndex + 1;
        newCard.setAttribute('data-index', currentIndex);
        container.appendChild(newCard);

        const newFields = newCard.querySelectorAll('input[required], select[required], textarea[required]');
        newFields.forEach(function(field) { $(field).on('blur', function() { validateField($(this)); }); });

        memberIndex++;
        toggleNoMembersAlert();

        const removeButton = newCard.querySelector('.remove-member');
        if (removeButton) {
            removeButton.addEventListener('click', function() {
                newCard.remove();
                updateMemberNumbers();
                toggleNoMembersAlert();
            });
        }
    });

    container.addEventListener('change', function(e) {
        if (e.target.classList.contains('birthdate-input') && !e.target.disabled) {
            updateAge(e.target);
        }
    });

    $('#residentModal').on('show.bs.modal', function() {
        if(residentSearch) residentSearch.value = '';
        if (residentsTableBody) residentsTableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Type a name to search for residents.</td></tr>';
        $('#addSelectedResident').prop('disabled', true);
        loadResidents();
    });

    if (residentSearch) {
        residentSearch.addEventListener('input', function() { filterResidents(this.value); });
    }

    $('#addSelectedResident').on('click', function() {
        if (selectedResident) {
            addResidentToForm(selectedResident);
            $('#residentModal').modal('hide');
            selectedResident = null;
            $(this).prop('disabled', true);
        }
    });

    function loadResidents() {
        if(allResidents.length > 0) return;
        if (!residentsTableBody) return;

        Promise.all([
            fetch('{{ route("admin.residents.api.all") }}').then(res => res.ok ? res.json() : Promise.reject(res)),
            fetch('{{ route("admin.senior-citizens.api.all") }}').then(res => res.ok ? res.json() : Promise.reject(res))
        ])
        .then(([residents, seniors]) => {
            const allPeople = [
                ...residents.map(resident => ({
                    id: `resident_${resident.id}`,
                    full_name: resident.full_name || `${resident.first_name || ''} ${resident.middle_name || ''} ${resident.last_name || ''}`.replace(/ +/g, ' ').trim(),
                    age: resident.age,
                    gender: resident.gender,
                    address: resident.address,
                    civil_status: resident.civil_status,
                    educational_attainment: resident.educational_attainment,
                    profession_occupation: resident.profession_occupation,
                    contact_number: resident.contact_number,
                    birthdate: resident.birthdate,
                    source: 'Regular Resident'
                })),
                ...seniors.map(senior => ({
                    id: `senior_${senior.id}`,
                    full_name: senior.full_name || `${senior.first_name || ''} ${senior.middle_name || ''} ${senior.last_name || ''}`.replace(/ +/g, ' ').trim(),
                    age: senior.age,
                    gender: senior.gender,
                    address: senior.address,
                    civil_status: senior.civil_status,
                    educational_attainment: senior.educational_attainment,
                    profession_occupation: senior.profession_occupation,
                    contact_number: senior.contact_number,
                    birthdate: senior.birthdate,
                    source: 'Senior Citizen'
                }))
            ];
            allResidents = allPeople;
            console.log('Residents loaded:', allResidents.length);
        })
        .catch(error => {
            console.error('Error loading residents:', error);
            if (residentsTableBody) residentsTableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Error loading data. Check console and server logs.</td></tr>`;
        });
    }

    function displayResidents(residents) {
        if (!residentsTableBody) return;
        if (residents.length === 0) {
            residentsTableBody.innerHTML = '<tr><td colspan="6" class="text-center">No residents found matching your search.</td></tr>';
            return;
        }

        const existingMembers = container.querySelectorAll('.member-card input[name*="[fullname]"]');
        const existingNames = Array.from(existingMembers).map(input => input.value.toLowerCase().trim());
        const headId = '{{ session("census.step1.head_id") }}';

        residentsTableBody.innerHTML = residents.map(resident => {
            const isAlreadyAdded = existingNames.includes(resident.full_name.toLowerCase().trim());
            const isHead = resident.id === headId;
            const disabledClass = isAlreadyAdded || isHead ? 'text-muted' : '';
            const disabledAttr = isAlreadyAdded || isHead ? 'disabled' : '';
            const sourceBadge = resident.source === 'Senior Citizen'
                ? '<span class="badge badge-success">Senior Citizen</span>'
                : '<span class="badge badge-primary">Regular Resident</span>';
            const reasonText = isHead ? '<small class="text-danger ml-1">(Household Head)</small>' : (isAlreadyAdded ? '<small class="text-danger ml-1">(Added)</small>' : '');

            return `
                <tr class="${disabledClass}">
                    <td class="text-center">
                        <input type="radio" name="selectedResident" value="${resident.id}"
                               style="transform: scale(1.2);"
                               ${disabledAttr} ${isAlreadyAdded || isHead ? '' : 'onclick="selectResident(\'' + resident.id + '\')"'}>
                    </td>
                    <td>${resident.full_name} ${reasonText}</td>
                    <td>${resident.age || 'N/A'}</td>
                    <td>${resident.gender || 'N/A'}</td>
                    <td>${sourceBadge}</td>
                    <td>${resident.address || 'N/A'}</td>
                </tr>
            `;
        }).join('');
    }

    function filterResidents(searchTerm) {
        if (searchTerm.trim() === '') {
            residentsTableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Type a name to search for residents.</td></tr>';
            return;
        }
        const filtered = allResidents.filter(resident =>
            resident.full_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (resident.address && resident.address.toLowerCase().includes(searchTerm.toLowerCase()))
        );
        displayResidents(filtered);
    }

    window.selectResident = function(residentId) {
        selectedResident = allResidents.find(r => r.id === residentId);
        $('#addSelectedResident').prop('disabled', false);
    };

    function addResidentToForm(resident) {
        if (!template || !template.content) { console.error('Member template not found.'); return; }
        const templateContent = template.content.cloneNode(true);
        const newCard = templateContent.querySelector('.member-card');
        const currentIndex = memberIndex;

        const inputs = newCard.querySelectorAll('[name*="INDEX"]');
        inputs.forEach(input => { input.name = input.name.replace(/\[INDEX\]/g, `[${currentIndex}]`); });

        const numberSpan = newCard.querySelector('.member-number');
        if(numberSpan) numberSpan.textContent = currentIndex + 1;
        newCard.setAttribute('data-index', currentIndex);

        newCard.querySelector('input[name*="[fullname]"]').value = resident.full_name;
        // Add hidden field to mark as fetched
        const hiddenFetched = document.createElement('input');
        hiddenFetched.type = 'hidden';
        hiddenFetched.name = `members[${currentIndex}][is_fetched]`;
        hiddenFetched.value = '1';
        newCard.appendChild(hiddenFetched);
        // Add hidden age for fetched residents
        const hiddenAge = document.createElement('input');
        hiddenAge.type = 'hidden';
        hiddenAge.name = `members[${currentIndex}][age]`;
        hiddenAge.value = resident.age || '';
        newCard.appendChild(hiddenAge);
        // Use API birthdate if available, else calculate approximate birthdate
        let birthdate = resident.birthdate || '';
        
     
        
        // Format birthdate for HTML date input (YYYY-MM-DD)
        if (birthdate) {
            try {
                const date = new Date(birthdate);
                if (!isNaN(date.getTime())) {
                    birthdate = date.toISOString().split('T')[0]; // Convert to YYYY-MM-DD
                    console.log('Formatted birthdate for input:', birthdate);
                } else {
                    console.log('Invalid date format, will calculate from age');
                    birthdate = '';
                }
            } catch (error) {
                console.log('Error parsing birthdate:', error);
                birthdate = '';
            }
        } else {
            console.log('No birthdate from API, will calculate from age');
        }
        
        if (!birthdate && resident.age) {
            const currentYear = new Date().getFullYear();
            const birthYear = currentYear - parseInt(resident.age);
            birthdate = `${birthYear}-06-15`; // Approximate birthdate as June 15
            console.log('Calculated birthdate from age:', birthdate);
        }
        
        console.log('Final birthdate value:', birthdate);
        console.log('=== END DEBUG ===');
        
        // Set the birthdate in the form
        const birthdateField = newCard.querySelector('input[name*="[birthdate]"]');
        if (birthdateField) {
            birthdateField.value = birthdate;
            console.log('Set birthdate field value to:', birthdateField.value);
        } else {
            console.log('ERROR: Could not find birthdate input field!');
        }
        // Mark birthdate as readonly for fetched residents but keep it enabled so it's submitted
        const birthdateInput = newCard.querySelector('input[name*="[birthdate]"]');
        birthdateInput.readOnly = true;
        birthdateInput.style.backgroundColor = '#f8f9fa';
        birthdateInput.title = 'Birthdate calculated from age - click to edit manually';
        
        // Allow manual editing if user clicks on the field
        birthdateInput.addEventListener('click', function() {
            if (this.readOnly) {
                const userConfirm = confirm('Do you want to manually edit the birthdate? This will override the calculated date.');
                if (userConfirm) {
                    this.readOnly = false;
                    this.style.backgroundColor = '';
                    this.title = '';
                }
            }
        });
        const ageInput = newCard.querySelector('input[id^="age"]');
        if (ageInput) ageInput.value = resident.age || '';
        if (resident.gender) newCard.querySelector('select[name*="[gender]"]').value = resident.gender;
        if (resident.civil_status) newCard.querySelector('select[name*="[civil_status]"]').value = resident.civil_status;
        if (resident.educational_attainment) newCard.querySelector('select[name*="[education]"]').value = resident.educational_attainment;
        if (resident.profession_occupation) newCard.querySelector('input[name*="[occupation]"]').value = resident.profession_occupation;

        container.appendChild(newCard);

        const newFields = newCard.querySelectorAll('input[required], select[required], textarea[required]');
        newFields.forEach(function(field) { $(field).on('blur', function() { validateField($(this)); }); });

        memberIndex++;
        toggleNoMembersAlert();
    }

    updateMemberNumbers();
    toggleNoMembersAlert();

    // Calculate ages for existing members on load
    document.querySelectorAll('.birthdate-input:not([disabled])').forEach(input => updateAge(input));

    // Attach remove event to existing buttons
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-member')) {
            const card = e.target.closest('.member-card');
            if (card) {
                card.remove();
                updateMemberNumbers();
                toggleNoMembersAlert();
            }
        }
    });

    function validateField($field) {
        if (!$field || !$field.length) return true;
        if ($field.prop('disabled')) return true; // Skip validation for disabled fields
        const value = $field.val().trim();
        const isRequired = $field.prop('required');
        const fieldName = $field.attr('name');

        if (isRequired && !value) {
            setFieldError($field, 'This field is required.');
            return false;
        }

        if (fieldName && fieldName.includes('[fullname]') && value && !isValidName(value)) {
            setFieldError($field, 'Please enter a valid name.');
            return false;
        }
        if (fieldName && fieldName.includes('[age]')) {
             if (value && !isValidAge(value)) {
                 setFieldError($field, 'Please enter a valid age (0-150).');
                 return false;
             }
        }

        if (value || !isRequired) setFieldSuccess($field);
        return true;
    }

    function isValidName(name) {
        return /^[a-zA-Z\s\'\-\.]+$/.test(name) && name.length >= 2;
    }

    function isValidAge(age) {
        const numAge = parseInt(age, 10);
        return !isNaN(numAge) && numAge >= 0 && numAge <= 150;
    }

    function setFieldError($field, message) {
        $field.removeClass('is-valid').addClass('is-invalid');
        const $formGroup = $field.closest('.form-group');
        $formGroup.addClass('has-error').removeClass('has-success');
        let $feedback = $formGroup.find('.invalid-feedback');
        if ($feedback.length === 0) {
            $feedback = $('<div class="invalid-feedback"></div>');
            $field.after($feedback);
        }
        $feedback.text(message).show();
        $formGroup.find('.valid-feedback').hide();
    }

    function setFieldSuccess($field) {
        $field.removeClass('is-invalid').addClass('is-valid');
        const $formGroup = $field.closest('.form-group');
        $formGroup.addClass('has-success').removeClass('has-error');
        $formGroup.find('.invalid-feedback').hide();
    }

    function clearFieldError($field) {
        $field.removeClass('is-invalid is-valid');
        const $formGroup = $field.closest('.form-group');
        $formGroup.removeClass('has-error has-success');
        $formGroup.find('.invalid-feedback, .valid-feedback').hide();
    }

    $('#step2Form').on('submit', function(e) {
        // *** REMOVED CHECK: Allow submission even if members.length is 0 ***
        /*
        const memberCards = document.querySelectorAll('.member-card');
        if (memberCards.length === 0) {
            e.preventDefault();
            alert('Please add at least one household member.'); // Or remove alert
            return false;
        }
        */

        let isValid = true;
        let errorMessage = 'Please correct the following errors:\n\n';
        const memberCards = document.querySelectorAll('.member-card'); // Still need this for field validation

        // Validate fields *within* member cards, if any exist
        memberCards.forEach((card, index) => {
            const requiredInputs = card.querySelectorAll('input[required]:not([disabled]), select[required]:not([disabled])');
            requiredInputs.forEach(function(field) {
                if (!validateField($(field))) {
                    isValid = false;
                    const label = $(field).closest('.form-group').find('label').text().replace('*', '').trim();
                    errorMessage += `Member ${index + 1} - ${label}: required or invalid.\n`;
                }
            });
        });

        if (!isValid) {
            e.preventDefault();
            alert(errorMessage);
            return false;
        }

        $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...');
        return true;
    });

    $(document).on('input change', '#membersContainer input:not([disabled]), #membersContainer select:not([disabled]), #membersContainer textarea:not([disabled])', function() { validateField($(this)); });
    $('#membersContainer input[required]:not([disabled]), #membersContainer select[required]:not([disabled]), #membersContainer textarea[required]:not([disabled])').each(function() { $(this).on('blur', function() { validateField($(this)); }); });
    $(document).on('focus click', '#membersContainer .form-control', function() { clearFieldError($(this)); });
});
</script>
@endpush
