@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
<li class="breadcrumb-item active" aria-current="page">Register New Resident</li>
@endsection

@section('page-title', 'Register New Resident - Step 4')
@section('page-subtitle', 'Family Members')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Progress Bar -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Registration Progress</h5>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="row mt-2">
                    <div class="col text-center">
                        <small class="text-success">✓ Personal Info</small>
                    </div>
                    <div class="col text-center">
                        <small class="text-success">✓ Citizenship & Education</small>
                    </div>
                    <div class="col text-center">
                        <small class="text-success">✓ Household Info</small>
                    </div>
                    <div class="col text-center">
                        <small class="text-primary font-weight-bold">Step 4: Family Members</small>
                    </div>
                    <div class="col text-center">
                        <small class="text-muted">Step 5: Review</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="card shadow">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="fe fe-users fe-16 mr-2"></i>Family Members
                </h4>
                <p class="text-muted mb-0">Add all family members living in the household</p>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.residents.create.step4.store') }}" method="POST">
                    @csrf
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">Family Members List</h5>
                        <button type="button" class="btn btn-primary btn-sm" id="addFamilyMember">
                            <i class="fe fe-plus fe-16 mr-2"></i>Add Family Member
                        </button>
                    </div>

                    <div id="familyMembersContainer">
                        <!-- Family members will be added here dynamically -->
                        @if(old('family_members') || session('registration.step4.family_members'))
                            @foreach(old('family_members', session('registration.step4.family_members') ?? []) as $index => $member)
                                <div class="family-member-card card border-info mb-3" data-index="{{ $index }}">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 text-info">Family Member #{{ $index + 1 }}</h6>
                                        <button type="button" class="btn btn-danger btn-sm remove-member">
                                            <i class="fe fe-trash-2 fe-12"></i>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                                                    <input type="text" name="family_members[{{ $index }}][name]" 
                                                           class="form-control @error('family_members.'.$index.'.name') is-invalid @enderror" 
                                                           value="{{ $member['name'] ?? '' }}" required>
                                                    @error('family_members.'.$index.'.name')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Relationship <span class="text-danger">*</span></label>
                                                    <select name="family_members[{{ $index }}][relationship]" 
                                                            class="form-control @error('family_members.'.$index.'.relationship') is-invalid @enderror" required>
                                                        <option value="">Select</option>
                                                        <option value="Spouse" {{ (isset($member['relationship']) && $member['relationship'] == 'Spouse') ? 'selected' : '' }}>Spouse</option>
                                                        <option value="Child" {{ (isset($member['relationship']) && $member['relationship'] == 'Child') ? 'selected' : '' }}>Child</option>
                                                        <option value="Parent" {{ (isset($member['relationship']) && $member['relationship'] == 'Parent') ? 'selected' : '' }}>Parent</option>
                                                        <option value="Sibling" {{ (isset($member['relationship']) && $member['relationship'] == 'Sibling') ? 'selected' : '' }}>Sibling</option>
                                                        <option value="Grandparent" {{ (isset($member['relationship']) && $member['relationship'] == 'Grandparent') ? 'selected' : '' }}>Grandparent</option>
                                                        <option value="Grandchild" {{ (isset($member['relationship']) && $member['relationship'] == 'Grandchild') ? 'selected' : '' }}>Grandchild</option>
                                                        <option value="In-Law" {{ (isset($member['relationship']) && $member['relationship'] == 'In-Law') ? 'selected' : '' }}>In-Law</option>
                                                        <option value="Other" {{ (isset($member['relationship']) && $member['relationship'] == 'Other') ? 'selected' : '' }}>Other Relative</option>
                                                    </select>
                                                    @error('family_members.'.$index.'.relationship')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Birthday <span class="text-danger">*</span></label>
                                                    <input type="date" name="family_members[{{ $index }}][birthday]" 
                                                           class="form-control @error('family_members.'.$index.'.birthday') is-invalid @enderror" 
                                                           value="{{ $member['birthday'] ?? '' }}" required>
                                                    @error('family_members.'.$index.'.birthday')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                                                    <select name="family_members[{{ $index }}][gender]" 
                                                            class="form-control @error('family_members.'.$index.'.gender') is-invalid @enderror" required>
                                                        <option value="">Select</option>
                                                        <option value="Male" {{ ($member['gender'] ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                                        <option value="Female" {{ ($member['gender'] ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                                    </select>
                                                    @error('family_members.'.$index.'.gender')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label">Work/Occupation</label>
                                                    <input type="text" name="family_members[{{ $index }}][work]" 
                                                           class="form-control @error('family_members.'.$index.'.work') is-invalid @enderror" 
                                                           value="{{ $member['work'] ?? '' }}" placeholder="Student, Teacher, etc.">
                                                    @error('family_members.'.$index.'.work')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Medical Condition</label>
                                                    <input type="text" name="family_members[{{ $index }}][medical_condition]" 
                                                           class="form-control @error('family_members.'.$index.'.medical_condition') is-invalid @enderror" 
                                                           value="{{ $member['medical_condition'] ?? '' }}" placeholder="Any medical conditions">
                                                    @error('family_members.'.$index.'.medical_condition')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Allergies</label>
                                                    <input type="text" name="family_members[{{ $index }}][allergies]" 
                                                           class="form-control @error('family_members.'.$index.'.allergies') is-invalid @enderror" 
                                                           value="{{ $member['allergies'] ?? '' }}" placeholder="Food, medicine, etc.">
                                                    @error('family_members.'.$index.'.allergies')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label class="form-label">Related To</label>
                                                    <select name="family_members[{{ $index }}][related_to]" 
                                                            class="form-control @error('family_members.'.$index.'.related_to') is-invalid @enderror">
                                                        <option value="">Select</option>
                                                        <option value="primary" {{ (isset($member['related_to']) && $member['related_to'] == 'primary') ? 'selected' : '' }}>Primary Member</option>
                                                        <option value="secondary" {{ (isset($member['related_to']) && $member['related_to'] == 'secondary') ? 'selected' : '' }}>Secondary Member</option>
                                                        <option value="both" {{ (isset($member['related_to']) && $member['related_to'] == 'both') ? 'selected' : '' }}>Both</option>
                                                    </select>
                                                    @error('family_members.'.$index.'.related_to')
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

                    <div class="alert alert-info" id="noFamilyMembers" style="{{ old('family_members') || session('registration.step4.family_members') ? 'display: none;' : '' }}">
                        <i class="fe fe-info fe-16 mr-2"></i>
                        <strong>No family members added yet.</strong> Click "Add Family Member" to start adding family members to the household.
                    </div>

                    <!-- Form Navigation -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.residents.create.step3') }}" class="btn btn-secondary">
                                    <i class="fe fe-arrow-left fe-16 mr-2"></i>Back: Household Information
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Next: Review & Submit <i class="fe fe-arrow-right fe-16 ml-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Family Member Template (Hidden) -->
<template id="familyMemberTemplate">
    <div class="family-member-card card border-info mb-3" data-index="">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-info">Family Member #<span class="member-number"></span></h6>
            <button type="button" class="btn btn-danger btn-sm remove-member">
                <i class="fe fe-trash-2 fe-12"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="family_members[INDEX][name]" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Relationship <span class="text-danger">*</span></label>
                        <select name="family_members[INDEX][relationship]" class="form-control" required>
                            <option value="">Select</option>
                            <option value="Spouse">Spouse</option>
                            <option value="Child">Child</option>
                            <option value="Parent">Parent</option>
                            <option value="Sibling">Sibling</option>
                            <option value="Grandparent">Grandparent</option>
                            <option value="Grandchild">Grandchild</option>
                            <option value="In-Law">In-Law</option>
                            <option value="Other">Other Relative</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Birthday <span class="text-danger">*</span></label>
                        <input type="date" name="family_members[INDEX][birthday]" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select name="family_members[INDEX][gender]" class="form-control" required>
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Work/Occupation</label>
                        <input type="text" name="family_members[INDEX][work]" class="form-control" placeholder="Student, Teacher, etc.">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Medical Condition</label>
                        <input type="text" name="family_members[INDEX][medical_condition]" class="form-control" placeholder="Any medical conditions">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="form-label">Allergies</label>
                        <input type="text" name="family_members[INDEX][allergies]" class="form-control" placeholder="Food, medicine, etc.">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">Related To</label>
                        <select name="family_members[INDEX][related_to]" class="form-control">
                            <option value="">Select</option>
                            <option value="primary">Primary Member</option>
                            <option value="secondary">Secondary Member</option>
                            <option value="both">Both</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize with the count of existing family members (from old or session data)
    let memberIndex = {{ count(old('family_members', session('registration.step4.family_members') ?? [])) }};
    
    const container = document.getElementById('familyMembersContainer');
    const template = document.getElementById('familyMemberTemplate');
    const noMembersAlert = document.getElementById('noFamilyMembers');
    const addButton = document.getElementById('addFamilyMember');

    function updateMemberNumbers() {
        const cards = container.querySelectorAll('.family-member-card');
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
        const newCard = templateContent.querySelector('.family-member-card');
        
        // Update all INDEX placeholders with actual index
        const inputs = newCard.querySelectorAll('[name*="INDEX"]');
        inputs.forEach(input => {
            input.name = input.name.replace('INDEX', memberIndex);
        });
        
        newCard.setAttribute('data-index', memberIndex);
        
        container.appendChild(newCard);
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
            const card = e.target.closest('.family-member-card');
            card.remove();
            updateMemberNumbers();
            toggleNoMembersAlert();
        }
    });

    // Initialize
    updateMemberNumbers();
    toggleNoMembersAlert();
});
</script>

@if(session('error'))
<script>
    window.addEventListener('DOMContentLoaded', function() {
        alert('Error: {{ session('error') }}');
    });
</script>
@endif
@endsection