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
                           class="form-control" 
                           value="{{ $member['name'] ?? '' }}" required>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label">Relationship <span class="text-danger">*</span></label>
                    <select name="family_members[{{ $index }}][relationship]" class="form-control" required>
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
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label">Birthday <span class="text-danger">*</span></label>
                    <input type="date" name="family_members[{{ $index }}][birthday]" 
                           class="form-control" 
                           value="{{ $member['birthday'] ?? '' }}" max="{{ date('Y-m-d') }}" required>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                    <select name="family_members[{{ $index }}][gender]" class="form-control family-member-gender" required>
                        <option value="">Select</option>
                        <option value="Male" {{ ($member['gender'] ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ ($member['gender'] ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Non-binary" {{ ($member['gender'] ?? '') == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                        <option value="Transgender" {{ ($member['gender'] ?? '') == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                        <option value="Other" {{ ($member['gender'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="form-label">Work/Occupation</label>
                    <input type="text" name="family_members[{{ $index }}][work]" 
                           class="form-control" 
                           value="{{ $member['work'] ?? '' }}" placeholder="Student, Teacher, etc.">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Medical Condition</label>
                    <input type="text" name="family_members[{{ $index }}][medical_condition]" 
                           class="form-control" 
                           value="{{ $member['medical_condition'] ?? '' }}" placeholder="Any medical conditions">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Allergies</label>
                    <input type="text" name="family_members[{{ $index }}][allergies]" 
                           class="form-control" 
                           value="{{ $member['allergies'] ?? '' }}" placeholder="Food, medicine, etc.">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label">Related To</label>
                    <select name="family_members[{{ $index }}][related_to]" class="form-control">
                        <option value="">Select</option>
                        <option value="primary" {{ ($member['related_to'] ?? '') == 'primary' ? 'selected' : '' }}>Primary</option>
                        <option value="secondary" {{ ($member['related_to'] ?? '') == 'secondary' ? 'selected' : '' }}>Secondary</option>
                        <option value="both" {{ ($member['related_to'] ?? '') == 'both' ? 'selected' : '' }}>Both</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="form-label">Phone Number (optional)</label>
                    <input type="tel" name="family_members[{{ $index }}][phone]" 
                           class="form-control" 
                           value="{{ $member['phone'] ?? '' }}" placeholder="09123456789" maxlength="11" pattern="[0-9]{11}" 
                           title="Please enter exactly 11 digits (e.g., 09123456789)">
                </div>
            </div>
        </div>
    </div>
</div> 