@extends('layouts.admin.master')

@section('title', 'Resident Details')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row align-items-center mb-4">
                <div class="col">
                    <h2 class="h3 page-title">Resident Details</h2>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.residents.index') }}" class="btn btn-primary mr-2">
                        <i class="fe fe-arrow-left mr-2"></i> Back to List
                    </a>
                    <a href="{{ route('admin.residents.edit', $resident) }}" class="btn btn-outline-secondary mr-2">
                        <i class="fe fe-edit mr-2"></i> Edit Details
                    </a>
                    <a href="{{ route('admin.residents.id.show', $resident) }}" class="btn btn-outline-secondary mr-2">
                        <i class="fe fe-credit-card mr-2"></i> ID Management
                    </a>
                    <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" type="button">
                        <i class="fe fe-trash mr-2 text-white"></i> Delete
                    </button>
                </div>
            </div>
            
            <!-- ID Card Status Banner -->
            @if($resident->photo)
                @if($resident->id_status == 'issued')
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <span class="fe fe-check-circle fe-16 mr-2"></span>
                        <div>
                            ID Card has been issued and is valid until 
                            @if($resident->id_expires_at)
                                {{ \Carbon\Carbon::parse($resident->id_expires_at)->format('F d, Y') }}
                            @elseif($resident->expiry_date)
                                {{ \Carbon\Carbon::parse($resident->expiry_date)->format('F d, Y') }}
                            @else
                                {{ \Carbon\Carbon::now()->format('F d, Y') }} (expiry date not set)
                            @endif. 
                            <a href="javascript:void(0);" class="alert-link" id="previewIdLink">Preview ID Card</a> | 
                            <a href="{{ route('admin.residents.id.download', $resident) }}" class="alert-link">Download ID Card</a>
                        </div>
                    </div>
                @elseif($resident->id_status == 'needs_renewal')
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <span class="fe fe-alert-circle fe-16 mr-2"></span>
                        <div>
                            ID Card needs renewal. 
                            <a href="{{ route('admin.residents.id.show', $resident) }}" class="alert-link">Process Renewal</a>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <span class="fe fe-info fe-16 mr-2"></span>
                        <div>
                            Photo is uploaded but ID Card has not been issued. 
                            <a href="{{ route('admin.residents.id.show', $resident) }}" class="alert-link">Issue ID Card</a>
                        </div>
                    </div>
                @endif
            @else
                <div class="alert alert-secondary d-flex align-items-center" role="alert">
                    <span class="fe fe-camera fe-16 mr-2"></span>
                    <div>
                        No ID photo uploaded. 
                        <a href="{{ route('admin.residents.id.show', $resident) }}" class="alert-link">Upload photo and manage ID</a>
                    </div>
                </div>
            @endif

            <div class="row g-4 align-items-start">
                <!-- Left: Photo & Basic Info -->
                <div class="col-md-3 mb-4 mb-md-0">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body text-center">
                            <div class="avatar avatar-xl mb-4">
                                @if($resident->photo)
                                    <img src="{{ $resident->photo_url }}" alt="{{ $resident->full_name }}" class="avatar-img rounded-circle">
                                @else
                                    <div class="avatar-letter rounded-circle bg-primary">{{ substr($resident->first_name, 0, 1) }}</div>
                                @endif
                            </div>
                            <h5 class="mb-2 mt-2">{{ $resident->full_name }}</h5>
                            <p class="small text-muted mb-1">Barangay ID</p>
                            <h6 class="mb-3">{{ $resident->barangay_id }}</h6>
                            <div class="mb-3">
                                <span class="badge badge-info">{{ $resident->type_of_resident }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted">Age:</span> {{ $resident->age }}
                            </div>
                            <div class="mb-2">
                                <span class="text-muted">Gender:</span> {{ $resident->sex }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Middle: Personal & Additional Info -->
                <div class="col-md-5 mb-4 mb-md-0">
                    <div class="card h-100 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-3">Personal Information</h6>
                            <div class="row mb-4">
                                <div class="col-6 mb-3"><strong>Birthdate:</strong> <br>{{ $resident->birthdate ? $resident->birthdate->format('F d, Y') : 'N/A' }}</div>
                                <div class="col-6 mb-3"><strong>Birthplace:</strong> <br>{{ $resident->birthplace }}</div>
                                <div class="col-6 mb-3"><strong>Civil Status:</strong> <br>{{ $resident->civil_status }}</div>
                                <div class="col-6 mb-3"><strong>Contact #:</strong> <br>{{ $resident->contact_number ?: 'Not provided' }}</div>
                                <div class="col-6 mb-3"><strong>Email:</strong> <br>{{ $resident->email_address ?: 'Not provided' }}</div>
                                <div class="col-6 mb-3"><strong>Address:</strong> <br>{{ $resident->address }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card h-100 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-3">Additional Information</h6>
                            <div class="row mb-4">
                                <div class="col-6 mb-3"><strong>Citizenship:</strong> <br>{{ $resident->citizenship_type }} {{ $resident->citizenship_country ? '- ' . $resident->citizenship_country : '' }}</div>
                                <div class="col-6 mb-3"><strong>Religion:</strong> <br>{{ $resident->religion }}</div>
                                <div class="col-6 mb-3"><strong>PhilSys ID:</strong> <br>{{ $resident->philsys_id ?: 'Not provided' }}</div>
                                <div class="col-6 mb-3"><strong>Occupation:</strong> <br>{{ $resident->profession_occupation }}</div>
                                <div class="col-6 mb-3"><strong>Monthly Income:</strong> <br>₱{{ number_format($resident->monthly_income, 2) }}</div>
                                <div class="col-6 mb-3"><strong>Education:</strong> <br>{{ $resident->educational_attainment }} ({{ $resident->education_status }})</div>
                            </div>
                        </div>
                    </div>
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-3">Mother's Information</h6>
                            <div class="mb-2">
                                <strong>Name:</strong> <br>{{ $resident->mother_full_name ?: 'Not provided' }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Right: Sectors, Household, Family -->
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-3">Population Sectors</h6>
                            @if(is_array($resident->population_sectors) && count($resident->population_sectors) > 0)
                                <ul class="list-group mb-4">
                                    @foreach($resident->population_sectors as $sector)
                                        <li class="list-group-item py-2">{{ $sector }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="alert alert-light mb-4">No population sectors selected.</div>
                            @endif
                        </div>
                    </div>
                    <div class="card h-100 shadow-sm mb-4">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-3">Household Information</h6>
                            @if($resident->household)
                                <div class="list-group mb-4">
                                    @if($resident->household->primary_name)
                                    <div class="list-group-item py-2">
                                        <strong>Primary:</strong> {{ $resident->household->primary_name }}
                                        <div class="small text-muted">{{ $resident->household->primary_gender }}, {{ $resident->household->primary_birthday ? $resident->household->primary_birthday->age . ' yrs' : 'Age n/a' }}</div>
                                    </div>
                                    @endif
                                    @if($resident->household->secondary_name)
                                    <div class="list-group-item py-2">
                                        <strong>Secondary:</strong> {{ $resident->household->secondary_name }}
                                        <div class="small text-muted">{{ $resident->household->secondary_gender }}, {{ $resident->household->secondary_birthday ? $resident->household->secondary_birthday->age . ' yrs' : 'Age n/a' }}</div>
                                    </div>
                                    @endif
                                    @if($resident->household->emergency_contact_name)
                                    <div class="list-group-item py-2">
                                        <strong>Emergency:</strong> {{ $resident->household->emergency_contact_name }}
                                        <div class="small text-muted">{{ $resident->household->emergency_relationship }}, {{ $resident->household->emergency_phone }}</div>
                                    </div>
                                    @endif
                                </div>
                            @else
                                <div class="alert alert-light mb-4">No household information available.</div>
                            @endif
                        </div>
                    </div>
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-uppercase text-muted mb-3">Family Members</h6>
                            @if(isset($resident->familyMembers) && $resident->familyMembers->count() > 0)
                                <div class="table-responsive mb-0">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Relationship</th>
                                                <th>Age</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($resident->familyMembers as $member)
                                            <tr>
                                                <td>{{ $member->name }}</td>
                                                <td>{{ $member->relationship }}</td>
                                                <td>{{ $member->age }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-light mb-0">No family members recorded.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this resident record? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.residents.destroy', $resident) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Resident</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ID Card Preview Modal -->
<div class="modal fade" id="idCardPreviewModal" tabindex="-1" role="dialog" aria-labelledby="idCardPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="idCardPreviewModalLabel">ID Card Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div id="idCardLoadingOverlay" class="text-center p-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Loading ID card preview...</p>
                </div>
                <div id="idCardContainer" class="id-card-container d-none">
                    <!-- Front Side -->
                    <div id="idCardFront" class="id-card">
                        <div class="id-card-header">
                            <div class="d-flex align-items-center">
                                <img src="/images/logo.png" alt="Barangay Logo" class="barangay-logo-left" id="idBrgyLogo">
                                <div class="id-card-title text-primary">
                                    <h6 class="mb-0">Barangay Lumanglipa</h6>
                                    <h6 class="small mb-0">Matasnakahoy, Lipa City Batangas</h6>
                                    <h6 class="mb-0">Residence Card</h6>
                                </div>
                                <img src="/images/citylogo.png" alt="City Logo" class="barangay-logo-right ml-auto" id="idCityLogo">
                            </div>
                        </div>
                        <div class="id-card-body">
                            <div class="row no-gutters">
                                <div class="col-md-8">
                                    <div class="id-card-details">
                                        <div class="mb-2">
                                            <strong>Pangalan/Name</strong><br>
                                            <span id="idName" class="text-uppercase font-weight-bold"></span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Petsa ng Kapanganakan/Date of birth</strong><br>
                                            <span id="idBirthdate"></span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Telepono/Phone</strong><br>
                                            <span id="idContactNumber"></span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Tirahan/Address</strong><br>
                                            <span id="idAddress"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="id-card-photo-container">
                                        <img id="idCardPhoto" src="" alt="Resident Photo">
                                    </div>
                                    <div class="text-center mt-2">
                                        <span id="idNumber" class="idno"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Back Side -->
                    <div id="idCardBack" class="id-card mt-4 bg-light">
                        <div class="id-card-back-body">
                            <div class="row">
                                <div class="col-7">
                                    <div class="id-card-back-details">
                                        <div class="mb-2">
                                            <strong>Kasarian/Sex</strong><br>
                                            <span id="idSex"></span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Katayuang Sibil/Civil Status</strong><br>
                                            <span id="idCivilStatus"></span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Lugar ng Kapanganakan/Place of birth</strong><br>
                                            <span id="idBirthplace"></span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Emergency Contact</strong><br>
                                            <span id="idEmergencyContact"></span><br>
                                            <span id="idEmergencyPhone"></span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Date Issued</strong><br>
                                            <span id="idIssueDate"></span>
                                        </div>
                                        <div class="mb-2">
                                            <strong>Valid Until</strong><br>
                                            <span id="idExpiryDate"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="qr-code-container">
                                        <img id="idQrCode" src="" alt="QR Code" class="img-fluid">
                                    </div>
                                    <div class="id-signature mt-2 text-center">
                                        <img id="idSignatureImg" src="" alt="Signature">
                                        <div class="signature-line"></div>
                                        <div class="small">May-ari/Card Holder</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="{{ route('admin.residents.id.download', $resident) }}" class="btn btn-primary">Download ID</a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar {
        position: relative;
        width: 110px;
        height: 110px;
        margin: 0 auto;
    }
    .avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-letter {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        color: white;
        font-size: 38px;
        font-weight: bold;
    }
    .card-body .row > [class^='col-'] {
        margin-bottom: 0 !important;
    }
    @media (max-width: 991.98px) {
        .border-right, .pr-md-4, .pl-md-4, .px-md-5 {
            border-right: none !important;
            padding-right: 0 !important;
            padding-left: 0 !important;
        }
    }

    /* ID Card Preview Styles */
    .id-card-container {
        max-width: 450px;
        margin: 0 auto;
        position: relative;
    }
    .id-card {
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 8px;
        overflow: hidden;
        position: relative;
        background: white;
        height: 250px;
    }
    .id-card-header {
        background: linear-gradient(to right, #e3f2fd, #bbdefb);
        padding: 5px;
        border-bottom: 1px solid #ccc;
    }
    .barangay-logo-left, .barangay-logo {
        width: 45px;
        height: 45px;
        object-fit: cover;
        margin-left: 5px;
        margin-top: 2px;
        margin-bottom: 2px;
    }
    
    .barangay-logo-right {
        width: 45px;
        height: 45px;
        object-fit: cover;
        margin-right: 5px;
        margin-top: 2px;
        margin-bottom: 2px;
    }
    
    .id-card-title {
        text-align: center;
        flex: 1;
        color: #1565c0;
    }
    .id-card-title h6 {
        margin: 0;
        font-weight: bold;
        font-size: 12px;
    }
    .id-card-title h6.small {
        font-size: 10px;
    }
    .id-card-body {
        padding: 15px;
        text-align: left;
    }
    .id-card-photo-container {
        width: 100px;
        height: 100px;
        overflow: hidden;
        border: 1px solid #ddd;
        border-radius: 10%;
        margin: 0 auto;
    }
    .id-card-photo-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .no-photo {
        width: 100%;
        height: 100%;
        background-color: #f9f9f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #aaa;
        font-size: 48px;
    }
    .id-card-details {
        font-size: 11px;
        padding-left: 20px;
    }
    .id-card-back-details {
        font-size: 11px;
        padding-left: 10px;
    }
    .idno {
        font-weight: bold;
        color: #333;
    }
    .id-signature {
        margin-top: 5px;
        text-align: center;
    }
    .signature-line {
        width: 100px;
        height: 1px;
        background: #333;
        margin: 5px auto;
    }
    .id-card-back {
        background: #f5f5f5;
    }
    .id-card-back-body {
        padding: 15px;
        text-align: left;
    }
    .qr-code-container {
        text-align: center;
        margin: 10px auto;
    }
    .qr-code-container img {
        width: 150px;
        height: 150px;
    }
    .no-signature {
        width: 100px;
        height: 30px;
        border-bottom: 1px dashed #aaa;
        margin: 0 auto;
    }
    
    /* Fix for signature visibility */
    .id-signature img {
        display: block;
        max-height: 30px;
        max-width: 100px;
        margin: 0 auto;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Open modal and preview ID when clicking "Preview ID Card"
    document.getElementById('previewIdLink')?.addEventListener('click', function (e) {
        e.preventDefault();

        // Hide card content, show loading
        document.getElementById('idCardLoadingOverlay').style.display = '';
        document.getElementById('idCardContainer').classList.add('d-none');

        $('#idCardPreviewModal').modal('show');

        // Fetch ID card preview data from server including QR code
        fetch(`{{ route('admin.residents.id.preview-data', $resident) }}`)
            .then(response => response.json())
            .then(data => {
                // Populate card fields from response
                document.getElementById('idName').innerText = data.resident.full_name;
                document.getElementById('idBirthdate').innerText = data.resident.birthdate || 'N/A';
                document.getElementById('idContactNumber').innerText = data.resident.contact_number || 'N/A';
                document.getElementById('idAddress').innerText = data.resident.address || 'N/A';
                document.getElementById('idCardPhoto').src = data.resident.photo_url || '';
                document.getElementById('idNumber').innerText = data.resident.barangay_id || '';
                document.getElementById('idSex').innerText = data.resident.sex || 'N/A';
                document.getElementById('idCivilStatus').innerText = data.resident.civil_status || 'N/A';
                document.getElementById('idBirthplace').innerText = data.resident.birthplace || 'N/A';
                
                // Set emergency contact info - FIXED to not default to "None"
                let emergencyContact = '';
                let emergencyPhone = '';
                
                if (data.household && data.household.emergency_contact_name) {
                    emergencyContact = data.household.emergency_contact_name;
                    // Include relationship if available
                    if (data.household.emergency_relationship) {
                        emergencyContact += ' (' + data.household.emergency_relationship + ')';
                    }
                    emergencyPhone = data.household.emergency_phone || '';
                }
                
                document.getElementById('idEmergencyContact').innerText = emergencyContact || 'Not provided';
                document.getElementById('idEmergencyPhone').innerText = emergencyPhone || '';
                
                // Format dates properly
                document.getElementById('idIssueDate').innerText = data.resident.issue_date ? 
                    new Date(data.resident.issue_date).toLocaleDateString('en-PH', {year: 'numeric', month: 'long', day: 'numeric'}) : 'Not issued';
                
                // Use the actual expiry date from database if available
                if (data.resident.id_expires_at) {
                    document.getElementById('idExpiryDate').innerText = new Date(data.resident.id_expires_at).toLocaleDateString('en-PH', {year: 'numeric', month: 'long', day: 'numeric'});
                } else if (data.resident.expiry_date) {
                    document.getElementById('idExpiryDate').innerText = new Date(data.resident.expiry_date).toLocaleDateString('en-PH', {year: 'numeric', month: 'long', day: 'numeric'});
                } else {
                    // Only calculate as fallback if no expiry date is set in database
                    let expiryDate;
                    if (data.resident.issue_date) {
                        expiryDate = new Date(data.resident.issue_date);
                    } else {
                        expiryDate = new Date();
                    }
                    expiryDate.setFullYear(expiryDate.getFullYear() + 15);
                    document.getElementById('idExpiryDate').innerText = expiryDate.toLocaleDateString('en-PH', {year: 'numeric', month: 'long', day: 'numeric'}) + ' (calculated)';
                }
                
                // Set signature if available
                if (data.resident.signature_url) {
                    document.getElementById('idSignatureImg').src = data.resident.signature_url;
                    document.getElementById('idSignatureImg').style.display = 'block';
                } else {
                    document.getElementById('idSignatureImg').style.display = 'none';
                }
                
                // Set QR code
                if (data.qr_code) {
                    document.getElementById('idQrCode').src = data.qr_code;
                }

                // Hide loading, show card
                document.getElementById('idCardLoadingOverlay').style.display = 'none';
                document.getElementById('idCardContainer').classList.remove('d-none');
            })
            .catch(error => {
                console.error('Error loading ID preview:', error);
                document.getElementById('idCardLoadingOverlay').innerHTML = 
                    '<div class="alert alert-danger">Error loading ID preview. Please try again.</div>';
            });
    });

    // When modal is closed, reset content
    $('#idCardPreviewModal').on('hidden.bs.modal', function () {
        document.getElementById('idCardLoadingOverlay').style.display = '';
        document.getElementById('idCardContainer').classList.add('d-none');
    });
});
</script>
@endpush