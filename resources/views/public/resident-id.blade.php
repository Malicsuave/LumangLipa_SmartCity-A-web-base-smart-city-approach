@extends('layouts.public.master')

@section('title', 'Barangay Resident Identification Module - Barangay Lumanglipa')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="text-center mb-4">Barangay Resident Identification Module</h1>
            
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4 text-primary">Purpose</h2>
                    <p>The Identification (ID) Module serves as the central registry for all residents in the barangay. It is the core system that other modules—such as health, certificate requests, clearances, business permits, and incident reporting—depend on for accurate, consistent population data.</p>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4 text-primary">Key Features</h2>
                    
                    <div class="mb-3">
                        <h3 class="h5 fw-bold">Resident Registration</h3>
                        <ul>
                            <li>Collects comprehensive personal, demographic, and household information.</li>
                            <li>Assigns a unique Barangay ID number per resident.</li>
                            <li>Optionally captures a photo and/or biometrics.</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h3 class="h5 fw-bold">Profile Management</h3>
                        <ul>
                            <li>Allows for updating resident details.</li>
                            <li>Tracks residency status (active, moved, deceased, etc.).</li>
                            <li>Manages household and family relationships.</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h3 class="h5 fw-bold">ID Issuance & Verification</h3>
                        <ul>
                            <li>Generates and prints digital/physical ID cards.</li>
                            <li>Enables quick verification via search, barcode, or QR code.</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h3 class="h5 fw-bold">Integration with Other Modules</h3>
                        <ul>
                            <li>Serves as the primary data source for health monitoring (malnutrition, pregnancy, etc.), certificate/clearance issuance, business permits, and social services.</li>
                            <li>All transactions reference the resident's unique ID.</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h3 class="h5 fw-bold">Audit & History Tracking</h3>
                        <ul>
                            <li>Logs updates and module transactions per resident for accountability and analytics.</li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4 text-primary">Typical Data Fields</h2>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Unique Barangay ID Number</li>
                        <li class="list-group-item">Full Name (Last, First, Middle)</li>
                        <li class="list-group-item">Date of Birth, Place of Birth</li>
                        <li class="list-group-item">Sex, Civil Status, Citizenship, Religion</li>
                        <li class="list-group-item">Contact Information (Mobile, Email)</li>
                        <li class="list-group-item">Address (House No., Street/Purok, Barangay, City, Province)</li>
                        <li class="list-group-item">Occupation, Education, Household/Family Details</li>
                        <li class="list-group-item">Health-related Data (Height, Weight, Pregnancy, PWD/Senior status)</li>
                        <li class="list-group-item">Photo, Signature (optional)</li>
                        <li class="list-group-item">Status (Active, Inactive, Deceased, Moved Out)</li>
                    </ul>
                </div>
            </div>
            
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h2 class="h4 text-primary">Importance</h2>
                    <ul>
                        <li><strong>Single Source of Truth:</strong> Powers all barangay operations and reporting.</li>
                        <li><strong>Security & Compliance:</strong> Ensures only legitimate residents access services.</li>
                        <li><strong>Health & Social Programs:</strong> Enables the health module to detect malnutrition, pregnancy, and other needs.</li>
                        <li><strong>Efficiency:</strong> Reduces duplicate data entry, speeds up services, and enables evidence-based planning.</li>
                    </ul>
                </div>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h4 text-primary">Next Steps</h2>
                    <ul>
                        <li>Design and build the resident registration form (census-style).</li>
                        <li>Define the database schema to support all required data fields.</li>
                        <li>Set up user roles, access controls, and data privacy protocols.</li>
                        <li>Integrate the ID module with health, certificate, and other barangay modules.</li>
                    </ul>
                </div>
            </div>
            
            <div class="alert alert-info mt-4">
                <p class="mb-0"><strong>Note:</strong> The accuracy and completeness of the ID module are critical, as all other barangay services and modules depend on it.</p>
            </div>
            
            <div class="text-center mt-5">
                <a href="{{ route('public.services') }}" class="btn btn-primary">Go to eServices</a>
                <a href="{{ route('public.home') }}" class="btn btn-outline-secondary ms-2">Back to Home</a>
            </div>
        </div>
    </div>
</div>
@endsection