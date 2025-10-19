@extends('layouts.admin.master')

@section('page-header', 'New Census Record')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.residents.census-data') }}">Census Data</a></li>
    <li class="breadcrumb-item active">New Census Record</li>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/registration-form.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Form -->
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">
                    <i class="fas fa-home mr-2"></i>
                    Household Information
                </strong>
                <div class="card-tools">
                    <span class="badge badge-light">Step 1 of 3</span>
                </div>
            </div>
            <form action="{{ route('admin.residents.census.step1.store') }}" method="POST" id="step1Form">
                @csrf
                <div class="card-body registration-form">

                    <!-- Household Head Information -->
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <h6 class="text-primary border-bottom pb-2">
                                <i class="fas fa-user mr-2"></i>Household Head Information
                            </h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="head_name" class="form-label">Household Head Name <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('head_name') is-invalid @enderror" 
                                       id="head_name" name="head_name" 
                                       value="{{ old('head_name', session('census.step1.head_name')) }}" 
                                       placeholder="Enter household head full name" required>
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-success" id="selectHeadFromResidents" data-toggle="modal" data-target="#residentModal">
                                        <i class="fas fa-search mr-2"></i>Select from Residents
                                    </button>
                                </div>
                            </div>
                            @error('head_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle mr-1"></i>
                                You can type manually or click "Select from Residents" to auto-fill from registered residents.
                            </small>
                        </div>
                    </div>

                    <!-- Household Head Details -->
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <h6 class="text-primary border-bottom pb-2">
                                <i class="fas fa-user-edit mr-2"></i>Household Head Details
                                <span id="inputModeIndicator" class="badge badge-secondary ml-2">Manual Input Mode</span>
                            </h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Age</label>
                            <input type="number" class="form-control" id="head_age" name="head_age" 
                                   placeholder="Enter age" min="1" max="120">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Gender</label>
                            <select class="form-control" id="head_gender" name="head_gender">
                                <option value="">Select gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Civil Status</label>
                            <select class="form-control" id="head_civil_status" name="head_civil_status">
                                <option value="">Select civil status</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Widowed">Widowed</option>
                                <option value="Divorced">Divorced</option>
                                <option value="Separated">Separated</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Educational Attainment</label>
                            <select class="form-control" id="head_education" name="head_education">
                                <option value="">Select education level</option>
                                <option value="No Schooling">No Schooling</option>
                                <option value="Elementary">Elementary</option>
                                <option value="Elementary Graduate">Elementary Graduate</option>
                                <option value="High School">High School</option>
                                <option value="High School Graduate">High School Graduate</option>
                                <option value="Vocational">Vocational</option>
                                <option value="College">College</option>
                                <option value="College Graduate">College Graduate</option>
                                <option value="Post Graduate">Post Graduate</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Occupation</label>
                            <input type="text" class="form-control" id="head_occupation" name="head_occupation" 
                                   placeholder="Enter occupation">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Religion</label>
                            <input type="text" class="form-control" id="head_religion" name="head_religion" 
                                   placeholder="Enter religion">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="head_contact" name="head_contact" 
                                   placeholder="09123456789" maxlength="11" pattern="[0-9]{11}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data Source</label>
                            <div class="form-control" style="border: none; background: transparent; padding-top: 8px;">
                                <span id="dataSourceBadge" class="badge badge-secondary">Manual Entry</span>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <h6 class="text-primary border-bottom pb-2">
                                <i class="fas fa-map-marker-alt mr-2"></i>Address Information
                            </h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="address" class="form-label">Complete Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3" 
                                      placeholder="House No., Street, Purok, Barangay (e.g., House #123, Main Street, Purok 1, Barangay Lumang Lipa)" required>{{ old('address', session('census.step1.address')) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Please include complete address details: House No., Street, Purok, Barangay</small>
                        </div>
                    </div>

                    <!-- Housing Information -->
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <h6 class="text-primary border-bottom pb-2">
                                <i class="fas fa-building mr-2"></i>Housing Information
                            </h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="housing_type" class="form-label">Type of Housing <span class="text-danger">*</span></label>
                            <select class="form-control @error('housing_type') is-invalid @enderror" 
                                    id="housing_type" name="housing_type" required>
                                <option value="">Select housing type</option>
                                <option value="Concrete" {{ old('housing_type', session('census.step1.housing_type')) == 'Concrete' ? 'selected' : '' }}>Concrete</option>
                                <option value="Semi-concrete" {{ old('housing_type', session('census.step1.housing_type')) == 'Semi-concrete' ? 'selected' : '' }}>Semi-concrete</option>
                                <option value="Wood" {{ old('housing_type', session('census.step1.housing_type')) == 'Wood' ? 'selected' : '' }}>Wood</option>
                                <option value="Bamboo" {{ old('housing_type', session('census.step1.housing_type')) == 'Bamboo' ? 'selected' : '' }}>Bamboo</option>
                                <option value="Mixed Materials" {{ old('housing_type', session('census.step1.housing_type')) == 'Mixed Materials' ? 'selected' : '' }}>Mixed Materials</option>
                                <option value="Makeshift" {{ old('housing_type', session('census.step1.housing_type')) == 'Makeshift' ? 'selected' : '' }}>Makeshift</option>
                                <option value="Apartment/Condominium" {{ old('housing_type', session('census.step1.housing_type')) == 'Apartment/Condominium' ? 'selected' : '' }}>Apartment/Condominium</option>
                                <option value="Other" {{ old('housing_type', session('census.step1.housing_type')) == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('housing_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

            <div class="card-footer bg-light">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ route('admin.residents.census-data') }}" class="btn btn-secondary">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="submit" class="btn btn-primary">
                            Continue to Step 2 <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Resident Selection Modal -->
<div class="modal fade" id="residentModal" tabindex="-1" role="dialog" aria-labelledby="residentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="residentModalLabel">
                    <i class="fas fa-search mr-2"></i>Select Household Head from Registered Residents
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
                <div class="table-responsive" style="max-height: 400px;">
                    <table class="table table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Select</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Source</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody id="residentsTableBody">
                            <tr>
                                <td colspan="6" class="text-center">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>Loading residents...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="selectHeadResident" disabled>
                    <i class="fas fa-user-check mr-2"></i>Select as Household Head
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/form-validation.js') }}"></script>
<script>
$(document).ready(function() {
    let selectedHeadResident = null;
    let allResidents = [];
    let isAutoFillMode = false;
    
    // Enable manual input mode
    function enableManualMode() {
        isAutoFillMode = false;
        $('#inputModeIndicator').removeClass('badge-success').addClass('badge-secondary').text('Manual Input Mode');
        $('#dataSourceBadge').removeClass('badge-success badge-primary').addClass('badge-secondary').text('Manual Entry');
        
        // Enable all form fields for editing
        $('#head_age, #head_gender, #head_civil_status, #head_education, #head_occupation, #head_religion, #head_contact').prop('readonly', false).prop('disabled', false);
        
        // Clear auto-fill styling
        $('#head_name').removeClass('bg-light');
    }
    
    // Enable auto-fill mode
    function enableAutoFillMode() {
        isAutoFillMode = true;
        $('#inputModeIndicator').removeClass('badge-secondary').addClass('badge-success').text('Auto-fill Mode');
        
        // Make some fields readonly in auto-fill mode (but allow contact to be editable)
        $('#head_age, #head_gender, #head_civil_status, #head_education, #head_occupation, #head_religion').prop('readonly', true);
        $('#head_contact').prop('readonly', false); // Keep contact editable
        
        // Style the name field to show it's auto-filled
        $('#head_name').addClass('bg-light');
    }
    
    // Form validation
    $('#step1Form').on('submit', function(e) {
        let isValid = true;
        let errorMessage = '';
        
        // Validate required fields
        const requiredFields = [
            { field: '#head_name', name: 'Household Head Name' },
            { field: '#address', name: 'Address' },
            { field: '#housing_type', name: 'Housing Type' }
        ];
        
        requiredFields.forEach(function(item) {
            const fieldValue = $(item.field).val().trim();
            if (!fieldValue) {
                isValid = false;
                errorMessage += `${item.name} is required.\n`;
                $(item.field).addClass('is-invalid');
            } else {
                $(item.field).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please correct the following errors:\n\n' + errorMessage);
            return false;
        }
        
        return true;
    });
    
    // Remove validation errors on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
    
    // Auto-format contact number
    $('#head_contact').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 11) {
            value = value.substring(0, 11);
        }
        $(this).val(value);
    });
    
    // Resident selection functionality
    const residentSearch = document.getElementById('residentSearch');
    const residentsTableBody = document.getElementById('residentsTableBody');
    const selectHeadButton = document.getElementById('selectHeadResident');
    
    // Load residents when modal is opened
    $('#residentModal').on('show.bs.modal', function() {
        loadResidents();
    });
    
    // Search functionality
    if (residentSearch) {
        residentSearch.addEventListener('input', function() {
            filterResidents(this.value);
        });
    }
    
    // Select household head
    if (selectHeadButton) {
        selectHeadButton.addEventListener('click', function() {
            if (selectedHeadResident) {
                // Populate household head name
                $('#head_name').val(selectedHeadResident.full_name);
                
                // Populate address if available
                if (selectedHeadResident.address) {
                    $('#address').val(selectedHeadResident.address);
                }
                
                // Auto-fill all resident information
                autoFillResidentInfo(selectedHeadResident);
                
                // Switch to auto-fill mode automatically
                enableAutoFillMode();
                
                $('#residentModal').modal('hide');
                selectedHeadResident = null;
                selectHeadButton.disabled = true;
                
                // Remove validation errors
                $('#head_name').removeClass('is-invalid');
                $('#address').removeClass('is-invalid');
            }
        });
    }
    
    // Load residents from database
    function loadResidents() {
        if (!residentsTableBody) return;
        
        residentsTableBody.innerHTML = '<tr><td colspan="6" class="text-center"><i class="fas fa-spinner fa-spin mr-2"></i>Loading residents and senior citizens...</td></tr>';
        
        console.log('Loading residents from API endpoints...');
        
        // First, try to load residents
        fetch('{{ route("admin.residents.api.all") }}', { 
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            console.log('Residents API response:', response.status, response.statusText);
            if (!response.ok) {
                throw new Error(`Residents API error: ${response.status} ${response.statusText}`);
            }
            return response.json();
        })
        .then(residents => {
            console.log('Residents loaded:', residents.length);
            
            // Then try to load senior citizens
            return fetch('{{ route("admin.senior-citizens.api.all") }}', { 
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Senior citizens API response:', response.status, response.statusText);
                if (!response.ok) {
                    throw new Error(`Senior citizens API error: ${response.status} ${response.statusText}`);
                }
                return response.json();
            })
            .then(seniors => {
                console.log('Senior citizens loaded:', seniors.length);
                return [residents, seniors];
            });
        })
        .then(([residents, seniors]) => {
            const allPeople = [
                ...residents.map(resident => ({
                    id: `resident_${resident.id}`,
                    type: 'resident',
                    full_name: resident.full_name,
                    age: resident.age,
                    gender: resident.gender,
                    address: resident.address,
                    contact_number: resident.contact_number,
                    civil_status: resident.civil_status,
                    educational_attainment: resident.educational_attainment,
                    profession_occupation: resident.profession_occupation,
                    religion: resident.religion,
                    source: 'Regular Resident'
                })),
                ...seniors.map(senior => ({
                    id: `senior_${senior.id}`,
                    type: 'senior',
                    full_name: senior.full_name,
                    age: senior.age,
                    gender: senior.gender,
                    address: senior.address,
                    contact_number: senior.contact_number,
                    civil_status: senior.civil_status,
                    educational_attainment: senior.educational_attainment,
                    profession_occupation: senior.profession_occupation,
                    religion: senior.religion,
                    source: 'Senior Citizen'
                }))
            ];
            
            allResidents = allPeople;
            displayResidents(allResidents);
        })
        .catch(error => {
            console.error('Error loading residents:', error);
            console.error('Error stack:', error.stack);
            residentsTableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Error: ${error.message}<br><small>Check browser console for details</small></td></tr>`;
        });
    }
    
    // Display residents in table
    function displayResidents(residents) {
        if (!residentsTableBody) return;
        
        if (residents.length === 0) {
            residentsTableBody.innerHTML = '<tr><td colspan="6" class="text-center">No residents found</td></tr>';
            return;
        }
        
        residentsTableBody.innerHTML = residents.map(resident => {
            const sourceBadge = resident.source === 'Senior Citizen' 
                ? '<span class="badge badge-success">Senior Citizen</span>'
                : '<span class="badge badge-primary">Regular Resident</span>';
            
            return `
                <tr>
                    <td>
                        <input type="radio" name="selectedHeadResident" value="${resident.id}" 
                               onclick="selectHeadResident('${resident.id}')">
                    </td>
                    <td>${resident.full_name}</td>
                    <td>${resident.age || 'N/A'}</td>
                    <td>${resident.gender || 'N/A'}</td>
                    <td>${sourceBadge}</td>
                    <td>${resident.address || 'N/A'}</td>
                </tr>
            `;
        }).join('');
    }
    
    // Filter residents based on search
    function filterResidents(searchTerm) {
        const filtered = allResidents.filter(resident => 
            resident.full_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (resident.address && resident.address.toLowerCase().includes(searchTerm.toLowerCase()))
        );
        displayResidents(filtered);
    }
    
    // Auto-fill resident information
    function autoFillResidentInfo(resident) {
        $('#head_age').val(resident.age || '');
        $('#head_gender').val(resident.gender || '');
        $('#head_civil_status').val(resident.civil_status || '');
        $('#head_education').val(resident.educational_attainment || '');
        $('#head_occupation').val(resident.profession_occupation || '');
        $('#head_religion').val(resident.religion || '');
        $('#head_contact').val(resident.contact_number || '');
        
        // Update data source badge
        const sourceBadge = resident.source === 'Senior Citizen' 
            ? 'badge-success'
            : 'badge-primary';
        $('#dataSourceBadge')
            .removeClass('badge-secondary badge-success badge-primary')
            .addClass(sourceBadge)
            .text(resident.source);
    }
    
    // Select household head function (global scope needed for onclick)
    window.selectHeadResident = function(residentId) {
        selectedHeadResident = allResidents.find(r => r.id === residentId);
        if (selectHeadButton) {
            selectHeadButton.disabled = false;
        }
    };
});
</script>
@endpush

@section('scripts')
<script>
$(document).ready(function() {
    // Show success message if redirected with success
    @if(session('success'))
        showSuccess('{{ session('success') }}');
    @endif
    
    // Show error message if redirected with error
    @if(session('error'))
        showError('{{ session('error') }}');
    @endif
});
</script>
@endsection
