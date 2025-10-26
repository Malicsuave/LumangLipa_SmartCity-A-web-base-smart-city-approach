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
/* Modal Table Improvements */
.resident-modal-table th, .resident-modal-table td {
    vertical-align: middle; padding: 0.75rem 0.5rem; word-break: break-word; white-space: pre-line; max-width: 220px;
}
.resident-modal-table thead th {
    position: sticky; top: 0; background: #f8f9fa; z-index: 2;
}
.resident-modal-table tbody tr:hover { background: #eaf4ff; }
.resident-modal-table input[type="radio"] { accent-color: #007bff; }
@media (max-width: 900px) {
    .resident-modal-table th, .resident-modal-table td { padding: 0.5rem 0.25rem; font-size: 0.95rem; max-width: 120px; }
}
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-lg border-0 admin-card-shadow">
            <div class="card-header">
                <strong class="card-title">
                    <i class="fas fa-home mr-2"></i> Household Information
                </strong>
                <div class="card-tools">
                    <span class="badge badge-light">Step 1 of 3</span>
                </div>
            </div>
            {{-- Pass $step1Data if it exists from the controller --}}
            @php $step1Data = $step1Data ?? session('census.step1', []); @endphp
            <form action="{{ route('admin.residents.census.step1.store') }}" method="POST" id="step1Form">
                @csrf
                <div class="card-body registration-form">

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <h6 class="text-primary border-bottom pb-2"><i class="fas fa-user mr-2"></i>Household Head Information</h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="head_name" class="form-label">Household Head Name <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-start" style="gap: 0.5rem;">
                                <div style="flex:1;">
                                    {{-- Correctly uses old() and session/step1Data --}}
                                    <input type="text" class="form-control @error('head_name') is-invalid @enderror"
                                           id="head_name" name="head_name"
                                           value="{{ old('head_name', $step1Data['head_name'] ?? '') }}"
                                           placeholder="Enter household head full name" required>
                                    @error('head_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <input type="hidden" id="head_id" name="head_id" value="{{ old('head_id', $step1Data['head_id'] ?? '') }}">
                                </div>
                                <div>
                                    <button type="button" class="btn btn-success" id="selectHeadFromResidents" data-toggle="modal" data-target="#residentModal">
                                        <i class="fas fa-search mr-2"></i>Select from Residents
                                    </button>
                                </div>
                            </div>
                            <small class="form-text text-muted"><i class="fas fa-info-circle mr-1"></i>You can type manually or click "Select from Residents".</small>
                        </div>
                    </div>

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
                            <label class="form-label" for="head_age">Age</label>
                            <input type="number" class="form-control @error('head_age') is-invalid @enderror"
                                   id="head_age" name="head_age"
                                   value="{{ old('head_age', $step1Data['head_age'] ?? '') }}"
                                   placeholder="Enter age" min="0" max="150">
                            @error('head_age') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="head_gender">Gender</label>
                            <select class="form-control @error('head_gender') is-invalid @enderror"
                                    id="head_gender" name="head_gender">
                                <option value="">Select gender</option>
                                <option value="Male" {{ old('head_gender', $step1Data['head_gender'] ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('head_gender', $step1Data['head_gender'] ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Non-binary" {{ old('head_gender', $step1Data['head_gender'] ?? '') == 'Non-binary' ? 'selected' : '' }}>Non-binary</option>
                                <option value="Transgender" {{ old('head_gender', $step1Data['head_gender'] ?? '') == 'Transgender' ? 'selected' : '' }}>Transgender</option>
                                <option value="Other" {{ old('head_gender', $step1Data['head_gender'] ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                             @error('head_gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="head_civil_status">Civil Status</label>
                            <select class="form-control @error('head_civil_status') is-invalid @enderror"
                                    id="head_civil_status" name="head_civil_status">
                                <option value="">Select civil status</option>
                                <option value="Single" {{ old('head_civil_status', $step1Data['head_civil_status'] ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
                                <option value="Married" {{ old('head_civil_status', $step1Data['head_civil_status'] ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
                                <option value="Widowed" {{ old('head_civil_status', $step1Data['head_civil_status'] ?? '') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                                <option value="Divorced" {{ old('head_civil_status', $step1Data['head_civil_status'] ?? '') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                                <option value="Separated" {{ old('head_civil_status', $step1Data['head_civil_status'] ?? '') == 'Separated' ? 'selected' : '' }}>Separated</option>
                            </select>
                             @error('head_civil_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="head_education">Educational Attainment</label>
                            <select class="form-control @error('head_education') is-invalid @enderror"
                                    id="head_education" name="head_education">
                                 <option value="">Select educational attainment</option>
                                 <option value="No Formal Education" {{ old('head_education', $step1Data['head_education'] ?? '') == 'No Formal Education' ? 'selected' : '' }}>No Formal Education</option>
                                 <option value="Elementary Undergraduate" {{ old('head_education', $step1Data['head_education'] ?? '') == 'Elementary Undergraduate' ? 'selected' : '' }}>Elementary Undergraduate</option>
                                 <option value="Elementary Graduate" {{ old('head_education', $step1Data['head_education'] ?? '') == 'Elementary Graduate' ? 'selected' : '' }}>Elementary Graduate</option>
                                 <option value="High School Undergraduate" {{ old('head_education', $step1Data['head_education'] ?? '') == 'High School Undergraduate' ? 'selected' : '' }}>High School Undergraduate</option>
                                 <option value="High School Graduate" {{ old('head_education', $step1Data['head_education'] ?? '') == 'High School Graduate' ? 'selected' : '' }}>High School Graduate</option>
                                 <option value="Vocational/Technical Graduate" {{ old('head_education', $step1Data['head_education'] ?? '') == 'Vocational/Technical Graduate' ? 'selected' : '' }}>Vocational/Technical Graduate</option>
                                 <option value="College Undergraduate" {{ old('head_education', $step1Data['head_education'] ?? '') == 'College Undergraduate' ? 'selected' : '' }}>College Undergraduate</option>
                                 <option value="College Graduate" {{ old('head_education', $step1Data['head_education'] ?? '') == 'College Graduate' ? 'selected' : '' }}>College Graduate</option>
                                 <option value="Post Graduate" {{ old('head_education', $step1Data['head_education'] ?? '') == 'Post Graduate' ? 'selected' : '' }}>Post Graduate</option>
                            </select>
                             @error('head_education') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="head_occupation">Occupation</label>
                            <input type="text" class="form-control @error('head_occupation') is-invalid @enderror"
                                   id="head_occupation" name="head_occupation"
                                   value="{{ old('head_occupation', $step1Data['head_occupation'] ?? '') }}"
                                   placeholder="Enter occupation">
                             @error('head_occupation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="head_contact">Contact Number</label>
                            <input type="tel" class="form-control @error('head_contact') is-invalid @enderror"
                                   id="head_contact" name="head_contact"
                                   value="{{ old('head_contact', $step1Data['head_contact'] ?? '') }}"
                                   placeholder="09123456789" maxlength="11" pattern="[0-9]{11}">
                             @error('head_contact') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Data Source</label>
                            <div class="form-control" style="border: none; background: transparent; padding-top: 8px;">
                                <span id="dataSourceBadge" class="badge badge-secondary">Manual Entry</span> {{-- JS updates this --}}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <h6 class="text-primary border-bottom pb-2"><i class="fas fa-map-marker-alt mr-2"></i>Address Information</h6>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="address" class="form-label">Complete Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      id="address" name="address" rows="3"
                                      placeholder="House No., Street, Purok, Barangay..." required>{{ old('address', $step1Data['address'] ?? '') }}</textarea>
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="form-text text-muted">Please include complete address details.</small>
                        </div>
                    </div>

                </div>

                <div class="card-footer bg-light">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('admin.residents.census-data') }}" class="btn btn-secondary"><i class="fas fa-times mr-2"></i>Cancel</a>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="submit" class="btn btn-primary">Continue to Step 2 <i class="fas fa-arrow-right ml-2"></i></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="residentModal" tabindex="-1" role="dialog" aria-labelledby="residentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="residentModalLabel"><i class="fas fa-search mr-2"></i>Select Household Head</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body" style="overflow-x:auto; max-height:60vh;">
                <div class="form-group mb-3">
                    <label for="residentSearch">Search Resident</label>
                    <input type="text" class="form-control" id="residentSearch" placeholder="Type name to search...">
                </div>
                <div class="table-responsive" style="max-height: 400px; min-width: 700px;">
                    <table class="table table-hover table-striped table-bordered resident-modal-table" style="min-width: 700px;">
                        <thead class="thead-light sticky-top" style="background: #f8f9fa;">
                            <tr>
                                <th style="width: 60px;">Select</th>
                                <th style="min-width: 140px;">Name</th>
                                <th style="min-width: 60px;">Age</th>
                                <th style="min-width: 100px;">Gender</th>
                                <th style="min-width: 120px;">Source</th>
                                <th style="min-width: 200px;">Address</th>
                            </tr>
                        </thead>
                        <tbody id="residentsTableBody">
                            <tr><td colspan="6" class="text-center text-muted">Type a name or address above to search residents.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="selectHeadResident" disabled><i class="fas fa-user-check mr-2"></i>Select as Household Head</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- <script src="{{ asset('js/form-validation.js') }}"></script> --}} {{-- Include if needed --}}
<script>
$(document).ready(function() {
    let selectedHeadResident = null;
    let allResidents = [];
    let isAutoFillMode = false;
    const selectButton = $('#selectHeadFromResidents'); // Cache the button selector
    const headNameInput = $('#head_name'); // Cache the input selector


    // --- AutoFill Mode Functions ---
    function enableManualMode() {
        isAutoFillMode = false;
        $('#inputModeIndicator').removeClass('badge-success').addClass('badge-secondary').text('Manual Input Mode');
        $('#dataSourceBadge').removeClass('badge-success badge-primary').addClass('badge-secondary').text('Manual Entry');
        $('#head_name, #head_age, #head_gender, #head_civil_status, #head_education, #head_occupation, #head_contact, #address')
            .prop('readonly', false).prop('disabled', false).removeClass('bg-light');
        selectButton.prop('disabled', false).removeAttr('title'); // Always enable Select button in manual mode
    }

    function enableAutoFillMode() {
        isAutoFillMode = true;
        $('#inputModeIndicator').removeClass('badge-secondary').addClass('badge-success').text('Auto-Fill Mode');
        $('#head_name, #head_age, #head_gender, #head_civil_status, #head_education, #head_occupation, #head_contact, #address')
            .prop('readonly', false).prop('disabled', false).addClass('bg-light');
        selectButton.prop('disabled', true).attr('title', 'Clear head name manually to select another resident.'); // Disable Select button only in auto-fill mode
    }

    // Switch back to manual if user types in ANY field
    $(document).on('input', '#step1Form input, #step1Form select, #step1Form textarea', function() {
        if(isAutoFillMode && $(this).hasClass('bg-light')) {
            console.log("Switching to manual mode due to input.");
            enableManualMode();
        }
        // Always re-enable select button if head name is cleared manually
        if ($(this).is('#head_name') && $(this).val().trim() === '') {
            enableManualMode();
        }
    });

    // --- Basic Form Handling ---
    $('input, select, textarea').on('input change', function() { $(this).removeClass('is-invalid'); });
    $('#head_contact').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        if (value.length > 11) value = value.substring(0, 11);
        $(this).val(value);
    });

    // --- Resident Selection Modal ---
    const residentSearch = document.getElementById('residentSearch');
    const residentsTableBody = document.getElementById('residentsTableBody');
    const selectHeadButton = document.getElementById('selectHeadResident');

    $('#residentModal').on('show.bs.modal', function() {
        if (residentSearch) residentSearch.value = '';
        if (residentsTableBody) residentsTableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Type a name or address above to search residents.</td></tr>';
        if (selectHeadButton) selectHeadButton.disabled = true;
        loadResidents(); // Load in background
    });

    if (residentSearch) { residentSearch.addEventListener('input', function() { filterResidents(this.value); }); }

    if (selectHeadButton) {
        selectHeadButton.addEventListener('click', function() {
            if (selectedHeadResident) {
                headNameInput.val(selectedHeadResident.full_name);
                let fullAddress = selectedHeadResident.address || '';
                if (selectedHeadResident.purok && selectedHeadResident.purok.trim() !== '' && !fullAddress.toLowerCase().includes(selectedHeadResident.purok.toLowerCase())) {
                    fullAddress = selectedHeadResident.purok + ', ' + fullAddress;
                }
                $('#address').val(fullAddress);
                $('#head_id').val(selectedHeadResident.id);
                autoFillResidentInfo(selectedHeadResident);
                enableAutoFillMode(); // Disables select button
                $('#residentModal').modal('hide');
                selectedHeadResident = null;
                selectHeadButton.disabled = true;
                $('#head_name, #address').removeClass('is-invalid');
            }
        });
    }

    // *** CORRECTED loadResidents Function ***
    function loadResidents() {
        if (allResidents.length > 0) return; // Load only once
        if (!residentsTableBody) return;

        console.log('Loading residents from combined API endpoints...');

        // Fetch from both separate endpoints and combine, similar to step2
        Promise.all([
            fetch('{{ route("admin.residents.api.all") }}', {
                headers: {'Accept': 'application/json'}
            }).then(response => {
                console.log('Residents API response Status:', response.status);
                if (!response.ok) {
                    return response.text().then(text => {
                       throw new Error(`Residents API error: ${response.status} ${response.statusText} - ${text}`);
                    });
                }
                return response.json();
            }),
            fetch('{{ route("admin.senior-citizens.api.all") }}', {
                headers: {'Accept': 'application/json'}
            }).then(response => {
                console.log('Senior Citizens API response Status:', response.status);
                if (!response.ok) {
                    return response.text().then(text => {
                       throw new Error(`Senior Citizens API error: ${response.status} ${response.statusText} - ${text}`);
                    });
                }
                return response.json();
            })
        ])
        .then(([residents, seniors]) => {
            console.log('Residents loaded:', residents.length, 'Seniors loaded:', seniors.length);
            const allPeople = [
                ...residents.map(resident => ({
                    id: `resident_${resident.id}`,
                    full_name: resident.full_name || `${resident.first_name || ''} ${resident.middle_name || ''} ${resident.last_name || ''}`.replace(/ +/g, ' ').trim(),
                    age: resident.age,
                    gender: resident.gender,
                    address: resident.address,
                    civil_status: resident.civil_status,
                    educational_attainment: resident.educational_attainment,
                    profession_occupation: resident.profession_occupation,
                    contact_number: resident.contact_number,
                    source: 'Regular Resident'
                })),
                ...seniors.map(senior => ({
                    id: `senior_${senior.id}`,
                    full_name: senior.full_name || `${senior.first_name || ''} ${senior.middle_name || ''} ${senior.last_name || ''}`.replace(/ +/g, ' ').trim(),
                    age: senior.age,
                    gender: senior.gender,
                    address: senior.address,
                    civil_status: senior.civil_status,
                    educational_attainment: senior.educational_attainment,
                    profession_occupation: senior.profession_occupation,
                    contact_number: senior.contact_number,
                    source: 'Senior Citizen'
                }))
            ];
            allResidents = allPeople;
            console.log('All people loaded:', allResidents.length);
            // If the table still shows "Loading...", reset it to the prompt.
             if (residentsTableBody && residentsTableBody.innerHTML.includes('fa-spinner')) {
                 residentsTableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Type a name or address above to search residents.</td></tr>';
             }
        })
        .catch(error => {
            console.error('Error loading residents:', error);
            if (residentsTableBody) {
                 residentsTableBody.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Error loading data: ${error.message}<br><small>Check console and server logs.</small></td></tr>`;
            }
        });
    }


    function displayResidents(residents) {
        if (!residentsTableBody) return;
        if (residents.length === 0) {
            residentsTableBody.innerHTML = '<tr><td colspan="6" class="text-center">No residents found matching your search.</td></tr>';
            return;
        }

        residentsTableBody.innerHTML = residents.map(resident => {
            const sourceBadge = resident.source === 'Senior Citizen'
                ? '<span class="badge badge-success">Senior Citizen</span>'
                : '<span class="badge badge-primary">Regular Resident</span>';
            // Use resident.id directly which should already be prefixed
            const residentIdValue = resident.id;

            return `
                <tr>
                    <td class="text-center">
                        <input type="radio" name="selectedHeadResident" value="${residentIdValue}" style="transform: scale(1.3);" onclick="selectHeadResident('${residentIdValue}')">
                    </td>
                    <td>${resident.full_name || 'N/A'}</td>
                    <td>${resident.age ?? 'N/A'}</td>
                    <td>${resident.gender || 'N/A'}</td>
                    <td>${sourceBadge}</td>
                    <td>${resident.address || 'N/A'}</td>
                </tr>
            `;
        }).join('');
    }

    function filterResidents(searchTerm) {
        if (!searchTerm || searchTerm.trim() === '') {
            residentsTableBody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">Type a name or address above to search residents.</td></tr>';
            return;
        }
        // Ensure allResidents is populated before filtering
        if(allResidents.length === 0) {
             console.warn("Attempted to filter before residents were loaded.");
             return; // Or maybe show a loading/error message
        }

        const filtered = allResidents.filter(resident =>
            (resident.full_name && resident.full_name.toLowerCase().includes(searchTerm.toLowerCase())) ||
            (resident.address && resident.address.toLowerCase().includes(searchTerm.toLowerCase()))
        );
        displayResidents(filtered);
    }

    function autoFillResidentInfo(resident) {
        $('#head_age').val(resident.age ?? '');
        $('#head_gender').val(resident.gender || '');
        $('#head_civil_status').val(resident.civil_status || '');
        $('#head_education').val(resident.educational_attainment || '');
        $('#head_occupation').val(resident.profession_occupation || '');
        $('#head_contact').val(resident.contact_number || ''); // Use contact_number from API

        const sourceBadgeClass = resident.source === 'Senior Citizen' ? 'badge-success' : 'badge-primary';
        $('#dataSourceBadge')
            .removeClass('badge-secondary badge-success badge-primary')
            .addClass(sourceBadgeClass)
            .text(resident.source);

        $('#head_age, #head_gender, #head_civil_status, #head_education, #head_occupation, #head_contact').removeClass('is-invalid');
    }

    window.selectHeadResident = function(residentId) {
        // Find based on the prefixed ID from the API
        selectedHeadResident = allResidents.find(r => r.id === residentId);
        console.log("Selected Resident:", selectedHeadResident); // Debugging
        if (selectHeadButton) selectHeadButton.disabled = !selectedHeadResident;
    };

    // --- Form Submission Validation ---
    $('#step1Form').on('submit', function(e) {
        let isValid = true;
        let errorMessage = '';
        const requiredFields = [
            { field: '#head_name', name: 'Household Head Name' },
            { field: '#address', name: 'Address' }
        ];

        requiredFields.forEach(function(item) {
            const $field = $(item.field);
            if (!$field.val() || $field.val().trim() === '') {
                isValid = false;
                errorMessage += `${item.name} is required.\n`;
                $field.addClass('is-invalid');
            } else {
                $field.removeClass('is-invalid');
            }
        });

        const $headContact = $('#head_contact');
        if ($headContact.val() && !/^\d{11}$/.test($headContact.val())) {
             isValid = false;
             errorMessage += 'Contact Number must be exactly 11 digits.\n';
             $headContact.addClass('is-invalid');
        } else if ($headContact.val()) {
            $headContact.removeClass('is-invalid');
        }


        if (!isValid) {
            e.preventDefault();
            alert('Please correct the following errors:\n\n' + errorMessage);
            return false;
        }

        $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Continuing...');
        return true;
    });

    // --- Initial Check on Load ---
    // Always start in manual mode so user can select another resident
    enableManualMode();


});
</script>
@endpush