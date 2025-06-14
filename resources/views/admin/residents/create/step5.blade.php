@extends('layouts.admin.master')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.residents.index') }}">Residents</a></li>
<li class="breadcrumb-item active" aria-current="page">Register New Resident</li>
@endsection

@section('page-title', 'Register New Resident - Step 6')
@section('page-subtitle', 'Review & Submit')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Progress Bar -->   
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Registration Progress</h5>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
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
                        <small class="text-success">✓ Senior Info</small>
                    </div>
                    <div class="col text-center">
                        <small class="text-success">✓ Family Members</small>
                    </div>
                    <div class="col text-center">
                        <small class="text-success font-weight-bold">Step 6: Review & Submit</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Form -->
        <div class="card shadow">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="fe fe-check-circle fe-16 mr-2"></i>Review Registration Information
                </h4>
                <p class="text-muted mb-0">Please review all information before submitting the registration</p>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.residents.store') }}" method="POST">
                    @csrf
                    
                    <!-- Personal Information -->
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">
                                <i class="fe fe-user fe-16 mr-2"></i>Personal Information
                            </h5>
                            <a href="{{ route('admin.residents.create.step1') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fe fe-edit fe-12 mr-1"></i>Edit
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">First Name:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step1.first_name') ?: 'Not provided' }}</dd>
                                        
                                        <dt class="col-sm-4">Middle Name:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step1.middle_name') ?: 'Not provided' }}</dd>
                                        
                                        <dt class="col-sm-4">Last Name:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step1.last_name') ?: 'Not provided' }}</dd>
                                        
                                        <dt class="col-sm-4">Suffix:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step1.suffix') ?: 'Not provided' }}</dd>
                                        
                                        <dt class="col-sm-4">Gender:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step1.sex') ?: 'Not provided' }}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Birthday:</dt>
                                        <dd class="col-sm-8">
                                            @if(session('registration.step1.birthdate'))
                                                {{ \Carbon\Carbon::parse(session('registration.step1.birthdate'))->format('F d, Y') }}
                                                ({{ \Carbon\Carbon::parse(session('registration.step1.birthdate'))->age }} years old)
                                            @else
                                                Not provided
                                            @endif
                                        </dd>
                                        
                                        <dt class="col-sm-4">Civil Status:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step1.civil_status') ?: 'Not provided' }}</dd>
                                        
                                        <dt class="col-sm-4">Contact Number:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step2.contact_number') ?: 'Not provided' }}</dd>
                                        
                                        <dt class="col-sm-4">Email:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step2.email_address') ?: 'Not provided' }}</dd>
                                        
                                        <dt class="col-sm-4">Address:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step2.address') ?: 'Not provided' }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Citizenship & Education -->
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-primary">
                                <i class="fe fe-book fe-16 mr-2"></i>Citizenship & Education
                            </h5>
                            <a href="{{ route('admin.residents.create.step2') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fe fe-edit fe-12 mr-1"></i>Edit
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Citizenship:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step2.citizenship_type') ?: 'Not provided' }}</dd>

                                        <dt class="col-sm-4">Education:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step2.educational_attainment') ?: 'Not provided' }}</dd>

                                        <dt class="col-sm-4">Occupation:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step2.profession_occupation') ?: 'Not provided' }}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Religion:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step2.religion') ?: 'Not provided' }}</dd>

                                        <dt class="col-sm-4">Monthly Income:</dt>
                                        <dd class="col-sm-8">
                                            @if(session('registration.step2.monthly_income'))
                                                ₱{{ number_format(session('registration.step2.monthly_income'), 2) }}
                                            @else
                                                Not provided
                                            @endif
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            
                            @if(session('registration.step2.population_sectors'))
                            <hr>
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="font-weight-bold">Population by Sector:</h6>
                                    <div class="d-flex flex-wrap">
                                        @foreach(session('registration.step2.population_sectors') as $sector)
                                            <span class="badge badge-info mr-2 mb-2 p-2">{{ $sector }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Household Information -->
                    <div class="card border-warning mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-warning">
                                <i class="fe fe-home fe-16 mr-2"></i>Household Information
                            </h5>
                            <a href="{{ route('admin.residents.create.step3') }}" class="btn btn-outline-warning btn-sm">
                                <i class="fe fe-edit fe-12 mr-1"></i>Edit
                            </a>
                        </div>
                        <div class="card-body">
                            <h6 class="text-primary mb-3">Primary Person in Household</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Name:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step3.primary_name') }}</dd>
                                        
                                        <dt class="col-sm-4">Birthday:</dt>
                                        <dd class="col-sm-8">{{ \Carbon\Carbon::parse(session('registration.step3.primary_birthday'))->format('F j, Y') }}</dd>
                                        
                                        <dt class="col-sm-4">Gender:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step3.primary_gender') }}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Phone:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step3.primary_phone') ?: 'Not provided' }}</dd>
                                        
                                        <dt class="col-sm-4">Work:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step3.primary_work') ?: 'Not provided' }}</dd>
                                        
                                        <dt class="col-sm-4">Allergies:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step3.primary_allergies') ?: 'None' }}</dd>
                                    </dl>
                                </div>
                            </div>
                            @if(session('registration.step3.primary_medical_condition'))
                            <div class="row">
                                <div class="col-12">
                                    <dl class="row">
                                        <dt class="col-sm-2">Medical Condition:</dt>
                                        <dd class="col-sm-10">{{ session('registration.step3.primary_medical_condition') }}</dd>
                                    </dl>
                                </div>
                            </div>
                            @endif

                            @if(session('registration.step3.secondary_name'))
                            <hr>
                            <h6 class="text-info mb-3">Secondary Person in Household</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Name:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step3.secondary_name') }}</dd>
                                        
                                        <dt class="col-sm-4">Birthday:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step3.secondary_birthday') ? \Carbon\Carbon::parse(session('registration.step3.secondary_birthday'))->format('F j, Y') : 'Not provided' }}</dd>
                                        
                                        <dt class="col-sm-4">Gender:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step3.secondary_gender') ?: 'Not provided' }}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Phone:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step3.secondary_phone') ?: 'Not provided' }}</dd>
                                        
                                        <dt class="col-sm-4">Work:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step3.secondary_work') ?: 'Not provided' }}</dd>
                                        
                                        <dt class="col-sm-4">Allergies:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step3.secondary_allergies') ?: 'None' }}</dd>
                                    </dl>
                                </div>
                            </div>
                            @if(session('registration.step3.secondary_medical_condition'))
                            <div class="row">
                                <div class="col-12">
                                    <dl class="row">
                                        <dt class="col-sm-2">Medical Condition:</dt>
                                        <dd class="col-sm-10">{{ session('registration.step3.secondary_medical_condition') }}</dd>
                                    </dl>
                                </div>
                            </div>
                            @endif
                            @endif

                            @if(session('registration.step3.emergency_contact_name'))
                            <hr>
                            <h6 class="text-danger mb-3">Emergency Contact</h6>
                            <div class="row">
                                <div class="col-md-12">
                                    <dl class="row">
                                        <dt class="col-sm-2">Name:</dt>
                                        <dd class="col-sm-4">{{ session('registration.step3.emergency_contact_name') }}</dd>
                                        
                                        <dt class="col-sm-2">Relationship:</dt>
                                        <dd class="col-sm-4">{{ session('registration.step3.emergency_relationship') ?: 'Not provided' }}</dd>
                                    </dl>
                                    <dl class="row">
                                        <dt class="col-sm-2">Phone:</dt>
                                        <dd class="col-sm-4">{{ session('registration.step3.emergency_phone') ?: 'Not provided' }}</dd>
                                        
                                        <dt class="col-sm-2">Work:</dt>
                                        <dd class="col-sm-4">{{ session('registration.step3.emergency_work') ?: 'Not provided' }}</dd>
                                    </dl>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Family Members -->
                    <div class="card border-success mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-success">
                                <i class="fe fe-users fe-16 mr-2"></i>Family Members
                            </h5>
                            <a href="{{ route('admin.residents.create.step4') }}" class="btn btn-outline-success btn-sm">
                                <i class="fe fe-edit fe-12 mr-1"></i>Edit
                            </a>
                        </div>
                        <div class="card-body">
                            @if(session('registration.step4.family_members') && count(session('registration.step4.family_members')) > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Relationship</th>
                                                <th>Age</th>
                                                <th>Gender</th>
                                                <th>Related To</th>
                                                <th>Work</th>
                                                <th>Medical Info</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(session('registration.step4.family_members') as $member)
                                            <tr>
                                                <td>{{ $member['name'] }}</td>
                                                <td>{{ $member['relationship'] }}</td>
                                                <td>{{ \Carbon\Carbon::parse($member['birthday'])->age }}</td>
                                                <td>{{ $member['gender'] }}</td>
                                                <td>
                                                    @if(isset($member['related_to']))
                                                        @if($member['related_to'] == 'primary')
                                                            <span class="badge badge-primary">Primary Member</span>
                                                        @elseif($member['related_to'] == 'secondary')
                                                            <span class="badge badge-info">Secondary Member</span>
                                                        @elseif($member['related_to'] == 'both')
                                                            <span class="badge badge-success">Both Members</span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-light">Not specified</span>
                                                    @endif
                                                </td>
                                                <td>{{ $member['work'] ?: 'Not provided' }}</td>
                                                <td>
                                                    @if($member['medical_condition'] || $member['allergies'])
                                                        @if($member['medical_condition'])
                                                            <small class="text-danger">Medical: {{ $member['medical_condition'] }}</small><br>
                                                        @endif
                                                        @if($member['allergies'])
                                                            <small class="text-warning">Allergies: {{ $member['allergies'] }}</small>
                                                        @endif
                                                    @else
                                                        <small class="text-muted">None</small>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fe fe-info fe-16 mr-2"></i>
                                    No family members registered for this household.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Senior Citizen Section -->
                    @php
                        $birthdate = session('registration.step1.birthdate');
                        $age = \Carbon\Carbon::parse($birthdate)->age;
                    @endphp
                    
                    @if($age >= 60)
                    <div class="card border-info mb-4">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 text-info">
                                <i class="fe fe-award fe-16 mr-2"></i>Senior Citizen Information
                            </h5>
                            <a href="{{ route('admin.residents.create.step4-senior') }}" class="btn btn-outline-info btn-sm">
                                <i class="fe fe-edit fe-12 mr-1"></i>Edit
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- Health Information -->
                            <h6 class="text-primary mb-3">Health Information</h6>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Health Conditions:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step4-senior.health_conditions') ?: 'None specified' }}</dd>
                                        
                                        <dt class="col-sm-4">Blood Type:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step4-senior.blood_type') ?: 'Not provided' }}</dd>
                                        
                                        <dt class="col-sm-4">Special Needs:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step4-senior.special_needs') ?: 'None specified' }}</dd>
                                    </dl>
                                </div>
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-4">Medications:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step4-senior.medications') ?: 'None specified' }}</dd>
                                        
                                        <dt class="col-sm-4">Allergies:</dt>
                                        <dd class="col-sm-8">{{ session('registration.step4-senior.allergies') ?: 'None specified' }}</dd>
                                        
                                        <dt class="col-sm-4">Last Medical Checkup:</dt>
                                        <dd class="col-sm-8">
                                            @if(session('registration.step4-senior.last_medical_checkup'))
                                                {{ \Carbon\Carbon::parse(session('registration.step4-senior.last_medical_checkup'))->format('F j, Y') }}
                                            @else
                                                Not provided
                                            @endif
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                            
                            <!-- Emergency Contact -->
                            @if(session('registration.step4-senior.emergency_contact_name'))
                            <h6 class="text-warning mb-3">Emergency Contact</h6>
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <dl class="row">
                                        <dt class="col-sm-2">Name:</dt>
                                        <dd class="col-sm-4">{{ session('registration.step4-senior.emergency_contact_name') }}</dd>
                                        
                                        <dt class="col-sm-2">Relationship:</dt>
                                        <dd class="col-sm-4">{{ session('registration.step4-senior.emergency_contact_relationship') ?: 'Not specified' }}</dd>
                                    </dl>
                                    <dl class="row">
                                        <dt class="col-sm-2">Phone Number:</dt>
                                        <dd class="col-sm-4">{{ session('registration.step4-senior.emergency_contact_number') ?: 'Not provided' }}</dd>
                                    </dl>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Benefits & Pension -->
                            <h6 class="text-info mb-3">Benefits & Pension</h6>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-5">Receiving Pension:</dt>
                                        <dd class="col-sm-7">{{ session('registration.step4-senior.receiving_pension') ? 'Yes' : 'No' }}</dd>
                                        
                                        @if(session('registration.step4-senior.receiving_pension'))
                                        <dt class="col-sm-5">Pension Type:</dt>
                                        <dd class="col-sm-7">{{ session('registration.step4-senior.pension_type') ?: 'Not specified' }}</dd>
                                        
                                        <dt class="col-sm-5">Pension Amount:</dt>
                                        <dd class="col-sm-7">
                                            @if(session('registration.step4-senior.pension_amount'))
                                                ₱{{ number_format(session('registration.step4-senior.pension_amount'), 2) }}
                                            @else
                                                Not specified
                                            @endif
                                        </dd>
                                        @endif
                                    </dl>
                                </div>
                                <div class="col-md-6">
                                    <dl class="row">
                                        <dt class="col-sm-5">Has Senior Discount Card:</dt>
                                        <dd class="col-sm-7">{{ session('registration.step4-senior.has_senior_discount_card') ? 'Yes' : 'No' }}</dd>
                                        
                                        <dt class="col-sm-5">Has PhilHealth:</dt>
                                        <dd class="col-sm-7">{{ session('registration.step4-senior.has_philhealth') ? 'Yes' : 'No' }}</dd>
                                        
                                        @if(session('registration.step4-senior.has_philhealth'))
                                        <dt class="col-sm-5">PhilHealth Number:</dt>
                                        <dd class="col-sm-7">{{ session('registration.step4-senior.philhealth_number') ?: 'Not provided' }}</dd>
                                        @endif
                                    </dl>
                                </div>
                            </div>
                            
                            <!-- Programs Enrolled -->
                            @if(session('registration.step4-senior.programs_enrolled') && count(session('registration.step4-senior.programs_enrolled')) > 0)
                            <h6 class="text-success mb-3">Programs Enrolled</h6>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="d-flex flex-wrap">
                                        @foreach(session('registration.step4-senior.programs_enrolled') as $program)
                                            <span class="badge badge-success mr-2 mb-2 p-2">{{ $program }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Additional Notes -->
                            @if(session('registration.step4-senior.notes'))
                            <h6 class="text-secondary mb-3">Additional Notes</h6>
                            <div class="row">
                                <div class="col-12">
                                    <p class="text-muted">{{ session('registration.step4-senior.notes') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Confirmation -->
                    <div class="card border-dark mb-4">
                        <div class="card-body text-center">
                            <h5 class="card-title">Confirmation</h5>
                            <p class="card-text">
                                By submitting this registration, I confirm that all the information provided is true and accurate to the best of my knowledge.
                            </p>
                            <div class="custom-control custom-checkbox d-inline-block">
                                <input type="checkbox" class="custom-control-input" id="confirmation" name="confirmation" required>
                                <label class="custom-control-label" for="confirmation">
                                    I confirm that all information is accurate and true
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Form Navigation -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.residents.create.step4') }}" class="btn btn-secondary d-flex align-items-center justify-content-center">
                                    <i class="fe fe-arrow-left fe-16 mr-2"></i>
                                    <span>Back: Family Members</span>
                                </a>
                                <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center">
                                    <i class="fe fe-check-circle fe-16 mr-2"></i>
                                    <span>Submit Registration</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(session('error'))
<script>
    window.addEventListener('DOMContentLoaded', function() {
        alert('Error: {{ session('error') }}');
    });
</script>
@endif
@endsection