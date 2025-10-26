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
    .member-summary {
        border-left: 3px solid #007bff;
        background: #fff;
        margin-bottom: 15px;
    }
    .summary-label {
        font-weight: 600;
        color: #495057;
    }
    .summary-value {
        color: #6c757d;
    }
    .card-header a.btn-light {
        color: #333;
    }
    .card-header a.btn-light:hover {
        background-color: #e2e6ea;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">
                    <i class="fas fa-check-circle mr-2"></i>
                    Review Census Information
                </strong>
                <div class="card-tools">
                    <span class="badge badge-light">Step 3 of 3</span>
                </div>
            </div>
            <form action="{{ route('admin.residents.census.step3.store') }}" method="POST" id="finalForm">
                @csrf
                <div class="card-body registration-form">

                    {{-- Find the Head of Household from Step 2 data --}}
                    @php
                        $householdHeadMember = collect(session('census.step2.members', []))
                                                ->firstWhere('relationship_to_head', 'Head');
                    @endphp

                    <div class="card mb-4 border-primary">
                        <div class="card-header bg-primary text-white d-flex align-items-center">
                            <h5 class="mb-0 flex-grow-1">
                                <i class="fas fa-home mr-2"></i>Household Information
                            </h5>
                            <div class="ml-auto">
                                <a href="{{ route('admin.residents.census.step1') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-6">
                                    <div class="mb-3">
                                        <span class="summary-label">Household Head:</span><br>
                                        <span class="summary-value">{{ session('census.step1.head_name') }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <span class="summary-label">House Address:</span><br>
                                        <span class="summary-value">{{ session('census.step1.address') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="mb-3">
                                        <span class="summary-label">Contact Number:</span><br>
                                        <span class="summary-value">{{ session('census.step1.head_contact') ?: 'Not provided' }}</span>
                                    </div>
                                     <div class="mb-3">
                                        <span class="summary-label">Age:</span><br>
                                        <span class="summary-value">{{ session('census.step1.head_age') ?: 'N/A' }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="mb-3">
                                        <span class="summary-label">Civil Status:</span><br>
                                        <span class="summary-value">{{ session('census.step1.head_civil_status') ?: 'N/A' }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <span class="summary-label">Occupation:</span><br>
                                        <span class="summary-value">{{ session('census.step1.head_occupation') ?: 'Not specified' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4 border-primary">
                        <div class="card-header bg-primary text-white d-flex align-items-center">
                            <h5 class="mb-0 flex-grow-1">
                                <i class="fas fa-users mr-2"></i>Household Members ({{ count(session('census.step2.members', [])) }})
                            </h5>
                            <div class="ml-auto">
                                <a href="{{ route('admin.residents.census.step2') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(session('census.step2.members'))
                                @foreach(session('census.step2.members') as $index => $member)
                                    <div class="member-summary card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <h6 class="text-info mb-1">{{ $member['fullname'] }}</h6>
                                                    <small class="text-muted">{{ $member['relationship_to_head'] }}</small>
                                                </div>
                                                <div class="col-md-2 col-6">
                                                    <span class="summary-label">Age:</span><br>
                                                    <span class="summary-value">{{ isset($member['age']) ? $member['age'] . ' years old' : 'N/A' }}</span>
                                                </div>
                                                <div class="col-md-2 col-6">
                                                    <span class="summary-label">Gender:</span><br>
                                                    <span class="summary-value">{{ $member['gender'] }}</span>
                                                </div>
                                                <div class="col-md-2 col-6">
                                                    <span class="summary-label">Civil Status:</span><br>
                                                    <span class="summary-value">{{ $member['civil_status'] }}</span>
                                                </div>
                                                <div class="col-md-3 col-6">
                                                    <span class="summary-label">Education:</span><br>
                                                    <span class="summary-value">{{ $member['education'] ?: 'Not specified' }}</span>
                                                </div>
                                                @if(!empty($member['occupation']))
                                                    <div class="col-md-3 col-6 mt-2 mt-md-0">
                                                        <span class="summary-label">Occupation:</span><br>
                                                        <span class="summary-value">{{ $member['occupation'] }}</span>
                                                    </div>
                                                @endif
                                                @if(!empty($member['category']))
                                                    <div class="col-md-3 col-6 mt-2 mt-md-0">
                                                        <span class="summary-label">Special Category:</span><br>
                                                        <span class="badge badge-info">{{ $member['category'] }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert">
                                    <i class=""></i>
                                    No household members found.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 border border-primary bg-white p-3" style="border-radius: 0.25rem;">
                        <h5><i class="fas fa-info-circle mr-2"></i>Before you submit:</h5>
                        <ul class="mb-0">
                            <li>Please review all the information above carefully</li>
                            <li>Make sure all household members are included</li>
                            <li>Verify that the relationship and personal details are correct</li>
                            <li>Once submitted, this census record will be saved to the database</li>
                        </ul>
                    </div>

                    <div class="form-check mt-3">
                        <input type="checkbox" class="form-check-input" id="confirmSubmission" required>
                        <label class="form-check-label" for="confirmSubmission">
                            <strong>I confirm that all the information provided is accurate and complete</strong>
                        </label>
                    </div>
                </div>

                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('admin.residents.census.step2') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-2"></i>Back to Step 2
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                <i class="fas fa-save mr-2"></i>Submit Census Record
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('finalForm');
    const submitBtn = document.getElementById('submitBtn');
    const confirmCheck = document.getElementById('confirmSubmission');

    if (confirmCheck && submitBtn) {
        submitBtn.disabled = !confirmCheck.checked;
        confirmCheck.addEventListener('change', function() {
            submitBtn.disabled = !this.checked;
        });
    }

    if(form && confirmCheck && submitBtn) {
        form.addEventListener('submit', function(e) {
            if (!confirmCheck.checked) {
                e.preventDefault();
                alert('Please confirm that all information is accurate before submitting.');
                return false;
            }
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            return true;
        });
    }
});
</script>
@endpush