@extends('layouts.public.master')

@section('title', 'Pre-Registration Step 4 - Family Members')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Progress Bar (Updated Design) -->
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
                            <small class="text-success">✓ Contact & Education</small>
                        </div>
                        <div class="col text-center">
                            <small class="text-success">✓ Household Info</small>
                        </div>
                        <div class="col text-center">
                            <small class="text-success">✓ Senior Info</small>
                        </div>
                        <div class="col text-center">
                            <small class="text-primary font-weight-bold">Step 5: Family Members</small>
                        </div>
                        <div class="col text-center">
                            <small class="text-muted">Step 6: Review</small>
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
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <h6><i class="fe fe-alert-circle"></i> Please correct the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('public.pre-registration.step4.store') }}" method="POST">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Family Members List</h5>
                            <button type="button" class="btn btn-primary btn-sm" id="addFamilyMember">
                                <i class="fe fe-plus fe-16 mr-2"></i>Add Family Member
                            </button>
                        </div>
                        <div id="familyMembersContainer">
                            @if(old('family_members') || session('pre_registration.step4.family_members'))
                                @foreach(old('family_members', session('pre_registration.step4.family_members') ?? []) as $index => $member)
                                    @include('public.pre-registration.partials.family-member-card', ['index' => $index, 'member' => $member])
                                @endforeach
                            @endif
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('public.pre-registration.step3') }}" class="btn btn-secondary">
                                <i class="fe fe-arrow-left"></i> Back: Household Info
                            </a>
                            <button type="submit" class="btn btn-primary">
                                Next: Photo & Documents <i class="fe fe-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                    <script>
                        // JS for adding/removing family members (simplified for public form)
                        document.addEventListener('DOMContentLoaded', function() {
                            let container = document.getElementById('familyMembersContainer');
                            let addBtn = document.getElementById('addFamilyMember');
                            let memberIndex = container.children.length;
                            addBtn.addEventListener('click', function() {
                                let html = `@include('public.pre-registration.partials.family-member-card', ['index' => '__INDEX__', 'member' => []])`;
                                html = html.replace(/__INDEX__/g, memberIndex);
                                let temp = document.createElement('div');
                                temp.innerHTML = html;
                                container.appendChild(temp.firstElementChild);
                                memberIndex++;
                            });
                            container.addEventListener('click', function(e) {
                                if (e.target.closest('.remove-member')) {
                                    e.target.closest('.family-member-card').remove();
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.progress-steps {
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.progress-steps::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    right: 0;
    height: 2px;
    background-color: #e9ecef;
    z-index: 1;
}

.step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 2;
    background: white;
    padding: 0 10px;
}

.step-number {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    margin-bottom: 8px;
}

.step.active .step-number {
    background-color: #007bff;
    color: white;
}

.step.completed .step-number {
    background-color: #28a745;
    color: white;
}

.step.senior.completed .step-number {
    background-color: #ffc107;
    color: #212529;
}

.step-title {
    font-size: 12px;
    text-align: center;
    color: #6c757d;
}

.step.active .step-title {
    color: #007bff;
    font-weight: 600;
}

.step.completed .step-title {
    color: #28a745;
    font-weight: 600;
}

.step.senior.completed .step-title {
    color: #ffc107;
    font-weight: 600;
}

#photoPreview, #signaturePreview {
    min-height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
}

#photoPreview img, #signaturePreview img {
    max-width: 100%;
    max-height: 180px;
    object-fit: contain;
}
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function(){
    // Debug info
    console.log("DOM ready - scripts initialized");
    console.log("Photo input element exists: ", $("#photoInput").length > 0);
    console.log("Photo preview element exists: ", $("#photoPreview").length > 0);
});
</script>
@endsection