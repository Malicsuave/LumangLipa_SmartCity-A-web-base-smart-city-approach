@extends('layouts.public')

@section('title', 'Registration Status - Barangay Lumanglipa')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0"><i class="fe fe-user"></i> Registration Status</h4>
                </div>
                
                <div class="card-body">
                    <!-- Status Badge -->
                    <div class="text-center mb-4">
                        @if($registration->status === 'pending')
                            <span class="badge badge-warning badge-lg p-3">
                                <i class="fe fe-clock"></i> PENDING REVIEW
                            </span>
                            <p class="mt-2 text-muted">Your application is being reviewed by the admin team</p>
                        @elseif($registration->status === 'approved')
                            <span class="badge badge-success badge-lg p-3">
                                <i class="fe fe-check-circle"></i> APPROVED
                            </span>
                            <p class="mt-2 text-success">Congratulations! Your registration has been approved</p>
                        @else
                            <span class="badge badge-danger badge-lg p-3">
                                <i class="fe fe-x-circle"></i> REJECTED
                            </span>
                            <p class="mt-2 text-danger">Your registration was not approved</p>
                        @endif
                    </div>

                    <!-- Registration Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary border-bottom pb-2">Personal Information</h6>
                            <p><strong>Name:</strong> {{ $registration->full_name }}</p>
                            <p><strong>Email:</strong> {{ $registration->email_address }}</p>
                            <p><strong>Contact:</strong> {{ $registration->contact_number }}</p>
                            <p><strong>Age:</strong> {{ $registration->age }} years old</p>
                            @if($registration->is_senior_citizen)
                                <span class="badge badge-warning">Senior Citizen (60+)</span>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-primary border-bottom pb-2">Registration Info</h6>
                            <p><strong>Submitted:</strong> {{ $registration->created_at->format('F d, Y g:i A') }}</p>
                            <p><strong>Type:</strong> {{ $registration->type_of_resident }}</p>
                            <p><strong>Address:</strong> {{ $registration->address }}</p>
                            
                            @if($registration->status === 'approved')
                                <p><strong>Approved:</strong> {{ $registration->approved_at->format('F d, Y g:i A') }}</p>
                                @if($registration->resident)
                                    <p><strong>Barangay ID:</strong> {{ $registration->resident->barangay_id }}</p>
                                    @if($registration->resident->seniorCitizen)
                                        <p><strong>Senior ID:</strong> {{ $registration->resident->seniorCitizen->senior_id_number }}</p>
                                    @endif
                                @endif
                            @elseif($registration->status === 'rejected')
                                <p><strong>Rejected:</strong> {{ $registration->rejected_at->format('F d, Y g:i A') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Photo Preview -->
                    @if($registration->photo)
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <h6 class="text-primary border-bottom pb-2">Uploaded Photo</h6>
                                <img src="{{ asset('storage/pre-registrations/photos/' . $registration->photo) }}" 
                                     alt="Registration Photo" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            
                            @if($registration->signature)
                                <div class="col-md-6">
                                    <h6 class="text-primary border-bottom pb-2">Uploaded Signature</h6>
                                    <img src="{{ asset('storage/pre-registrations/signatures/' . $registration->signature) }}" 
                                         alt="Registration Signature" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Status-specific Messages -->
                    @if($registration->status === 'pending')
                        <div class="alert alert-info mt-4">
                            <h6><i class="fe fe-info"></i> What's Next?</h6>
                            <p class="mb-0">
                                Please wait for the admin team to review your application. You will receive an email notification 
                                once your application is processed. The review process typically takes 1-3 business days.
                            </p>
                        </div>
                    @elseif($registration->status === 'approved')
                        <div class="alert alert-success mt-4">
                            <h6><i class="fe fe-check-circle"></i> Registration Approved!</h6>
                            <p class="mb-2">
                                Your digital ID has been sent to your email address. You can use this digital copy for barangay transactions.
                            </p>
                            <p class="mb-0">
                                <strong>Next Step:</strong> Visit the Barangay Hall during office hours to claim your physical ID card.
                            </p>
                        </div>
                    @elseif($registration->status === 'rejected')
                        <div class="alert alert-danger mt-4">
                            <h6><i class="fe fe-x-circle"></i> Registration Not Approved</h6>
                            @if($registration->rejection_reason)
                                <p><strong>Reason:</strong> {{ $registration->rejection_reason }}</p>
                            @endif
                            <p class="mb-0">
                                You may submit a new application or contact the Barangay Hall for clarification.
                            </p>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="text-center mt-4">
                        @if($registration->status === 'rejected')
                            <a href="{{ route('public.pre-registration.create') }}" class="btn btn-primary">
                                <i class="fe fe-user-plus"></i> Submit New Registration
                            </a>
                        @endif
                        
                        <a href="{{ route('public.pre-registration.check-status') }}" class="btn btn-outline-secondary ml-2">
                            <i class="fe fe-search"></i> Check Another Registration
                        </a>
                        
                        <a href="{{ route('public.home') }}" class="btn btn-outline-primary ml-2">
                            <i class="fe fe-home"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Contact Information -->
            <div class="text-center mt-4">
                <div class="card">
                    <div class="card-body">
                        <h6><i class="fe fe-phone"></i> Need Help?</h6>
                        <p class="mb-0 text-muted">
                            Contact Barangay Lumanglipa<br>
                            Office Hours: Monday-Friday 8AM-5PM, Saturday 8AM-12PM
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection