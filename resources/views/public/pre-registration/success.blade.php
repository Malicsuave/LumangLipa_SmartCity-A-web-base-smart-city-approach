@extends('layouts.public.master')

@section('title', 'Registration Successful - Barangay Lumanglipa')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0">
                        <i class="fe fe-check-circle"></i> 
                        @if(session('is_senior'))
                            Senior Citizen Registration Submitted!
                        @else
                            Registration Submitted Successfully!
                        @endif
                    </h4>
                </div>
                
                <div class="card-body text-center">
                    <div class="mb-4">
                        @if(session('is_senior'))
                            <i class="fe fe-award text-warning" style="font-size: 4rem;"></i>
                        @else
                            <i class="fe fe-mail text-primary" style="font-size: 4rem;"></i>
                        @endif
                    </div>
                    
                    <h5 class="text-success mb-3">
                        @if(session('is_senior'))
                            Thank you for your Senior Citizen registration!
                        @else
                            Thank you for your pre-registration!
                        @endif
                    </h5>
                    
                    <p class="mb-4">
                        Your registration application has been successfully submitted and is now pending review by the Barangay Administration.
                    </p>

                    @if(session('is_senior'))
                        <div class="alert alert-warning">
                            <h6><i class="fe fe-award"></i> Senior Citizen Benefits</h6>
                            <p class="mb-2">Upon approval, you will receive your <strong>Senior Citizen ID</strong> which provides access to:</p>
                            <ul class="text-left mb-0">
                                <li>20% discount on medicines and medical services</li>
                                <li>Priority lanes in government offices</li>
                                <li>Transportation discounts</li>
                                <li>Access to senior citizen programs</li>
                            </ul>
                        </div>
                    @endif
                    
                    <div class="alert alert-info">
                        <h6><i class="fe fe-info"></i> What happens next?</h6>
                        <ul class="text-left mb-0">
                            <li>Your application will be reviewed by our admin team</li>
                            <li>You will receive an email notification once your application is processed</li>
                            <li>If approved, your @if(session('is_senior'))Senior Citizen ID @else digital ID @endif will be sent to your email automatically</li>
                            <li>You can visit the Barangay Hall to claim your physical ID card</li>
                        </ul>
                    </div>
                    
                    @if(session('registration_id'))
                    <div class="alert alert-secondary">
                        <small><strong>Reference ID:</strong> {{ session('registration_id') }}</small>
                    </div>
                    @endif
                    
                    <div class="mt-4">
                        <a href="{{ route('public.pre-registration.check-status') }}" class="btn btn-primary">
                            <i class="fe fe-search"></i> Check Status
                        </a>
                        <a href="{{ route('public.home') }}" class="btn btn-outline-secondary ml-2">
                            <i class="fe fe-home"></i> Back to Home
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-muted">
                    <i class="fe fe-phone"></i> For urgent concerns, contact us at the Barangay Hall<br>
                    <small>Office Hours: Monday-Friday 8AM-5PM, Saturday 8AM-12PM</small>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection