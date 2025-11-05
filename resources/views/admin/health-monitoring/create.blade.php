@extends('layouts.admin')

@section('title', 'Add Health Record')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="h3 mb-0">Add New Health Record</h2>
            <p class="text-muted">Create a comprehensive health record for a resident</p>
        </div>
    </div>

    <form action="{{ route('admin.health-monitoring.store') }}" method="POST" id="healthRecordForm">
        @csrf
        
        <!-- Resident Selection -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Resident Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="resident_id" class="form-label">Select Resident <span class="text-danger">*</span></label>
                            <select name="resident_id" id="resident_id" class="form-select" required>
                                <option value="">-- Select Resident --</option>
                                @foreach($residents as $resident)
                                    <option value="{{ $resident->id }}" 
                                            data-age="{{ $resident->age ?? 'N/A' }}"
                                            {{ request('resident_id') == $resident->id ? 'selected' : '' }}>
                                        {{ $resident->first_name }} {{ $resident->middle_name }} {{ $resident->last_name }} 
                                        ({{ $resident->barangay_id }})
                                    </option>
                                @endforeach
                            </select>
                            @error('resident_id')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="emergency_contact" class="form-label">Emergency Contact</label>
                            <input type="text" name="emergency_contact" id="emergency_contact" class="form-control" 
                                   placeholder="Name: Number">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demographics -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>Demographics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_senior_citizen" id="is_senior_citizen" 
                                   class="form-check-input" value="1">
                            <label class="form-check-label" for="is_senior_citizen">
                                <strong>Senior Citizen</strong> (60+ years old)
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_pregnant" id="is_pregnant" 
                                   class="form-check-input" value="1">
                            <label class="form-check-label" for="is_pregnant">
                                <strong>Pregnant</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_malnourished" id="is_malnourished" 
                                   class="form-check-input" value="1">
                            <label class="form-check-label" for="is_malnourished">
                                <strong>Malnourished</strong>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vitals -->
        <div class="card mb-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-heartbeat me-2"></i>Vital Signs</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="height" class="form-label">Height (cm)</label>
                            <input type="number" step="0.1" name="height" id="height" 
                                   class="form-control" placeholder="e.g., 165.5">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="weight" class="form-label">Weight (kg)</label>
                            <input type="number" step="0.1" name="weight" id="weight" 
                                   class="form-control" placeholder="e.g., 65.0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="blood_pressure" class="form-label">Blood Pressure</label>
                            <input type="text" name="blood_pressure" id="blood_pressure" 
                                   class="form-control" placeholder="e.g., 120/80">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="temperature" class="form-label">Temperature (°C)</label>
                            <input type="number" step="0.1" name="temperature" id="temperature" 
                                   class="form-control" placeholder="e.g., 36.5">
                        </div>
                    </div>
                </div>
                <div id="bmi-result" class="alert alert-info d-none">
                    <strong>BMI:</strong> <span id="bmi-value"></span> - <span id="bmi-category"></span>
                </div>
            </div>
        </div>

        <!-- Medical Information -->
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="fas fa-notes-medical me-2"></i>Medical Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="blood_type" class="form-label">Blood Type</label>
                            <select name="blood_type" id="blood_type" class="form-select">
                                <option value="">-- Select Blood Type --</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="malnutrition_type" class="form-label">Malnutrition Type</label>
                            <select name="malnutrition_type" id="malnutrition_type" class="form-select">
                                <option value="">-- Select Type --</option>
                                <option value="Underweight">Underweight</option>
                                <option value="Stunted">Stunted (Height-for-age)</option>
                                <option value="Wasted">Wasted (Weight-for-height)</option>
                                <option value="Overweight">Overweight/Obese</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="allergies" class="form-label">Allergies</label>
                    <textarea name="allergies" id="allergies" class="form-control" rows="2" 
                              placeholder="List any known allergies..."></textarea>
                </div>
                <div class="mb-3">
                    <label for="current_medications" class="form-label">Current Medications</label>
                    <textarea name="current_medications" id="current_medications" class="form-control" rows="2" 
                              placeholder="List current medications..."></textarea>
                </div>
                <div class="mb-3">
                    <label for="medical_conditions" class="form-label">Medical Conditions</label>
                    <textarea name="medical_conditions" id="medical_conditions" class="form-control" rows="2" 
                              placeholder="List any chronic conditions (diabetes, hypertension, etc.)..."></textarea>
                </div>
            </div>
        </div>

        <!-- Pregnancy Information (shown if pregnant checkbox is checked) -->
        <div class="card mb-4 d-none" id="pregnancy-section">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-baby me-2"></i>Pregnancy Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="pregnancy_weeks" class="form-label">Weeks Pregnant</label>
                            <input type="number" name="pregnancy_weeks" id="pregnancy_weeks" 
                                   class="form-control" min="1" max="42" placeholder="e.g., 12">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="expected_delivery_date" class="form-label">Expected Delivery Date</label>
                            <input type="date" name="expected_delivery_date" id="expected_delivery_date" 
                                   class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="next_prenatal_visit" class="form-label">Next Prenatal Visit</label>
                            <input type="date" name="next_prenatal_visit" id="next_prenatal_visit" 
                                   class="form-control">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="pregnancy_complications" class="form-label">Pregnancy Complications</label>
                    <textarea name="pregnancy_complications" id="pregnancy_complications" 
                              class="form-control" rows="2" placeholder="Note any complications..."></textarea>
                </div>
            </div>
        </div>

        <!-- Checkup Dates -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Checkup Schedule</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="last_checkup_date" class="form-label">Last Checkup Date</label>
                            <input type="date" name="last_checkup_date" id="last_checkup_date" 
                                   class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="next_checkup_date" class="form-label">Next Checkup Date</label>
                            <input type="date" name="next_checkup_date" id="next_checkup_date" 
                                   class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Additional Notes</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3" 
                              placeholder="Any additional notes or observations..."></textarea>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.health-monitoring.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Health Record
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle pregnancy section
    const isPregnantCheckbox = document.getElementById('is_pregnant');
    const pregnancySection = document.getElementById('pregnancy-section');
    
    isPregnantCheckbox.addEventListener('change', function() {
        if (this.checked) {
            pregnancySection.classList.remove('d-none');
        } else {
            pregnancySection.classList.add('d-none');
        }
    });
    
    // Calculate BMI
    const heightInput = document.getElementById('height');
    const weightInput = document.getElementById('weight');
    const bmiResult = document.getElementById('bmi-result');
    const bmiValue = document.getElementById('bmi-value');
    const bmiCategory = document.getElementById('bmi-category');
    
    function calculateBMI() {
        const height = parseFloat(heightInput.value);
        const weight = parseFloat(weightInput.value);
        
        if (height && weight && height > 0 && weight > 0) {
            const heightInMeters = height / 100;
            const bmi = (weight / (heightInMeters * heightInMeters)).toFixed(2);
            
            let category = '';
            let categoryClass = '';
            
            if (bmi < 18.5) {
                category = 'Underweight';
                categoryClass = 'text-warning';
            } else if (bmi >= 18.5 && bmi < 25) {
                category = 'Normal';
                categoryClass = 'text-success';
            } else if (bmi >= 25 && bmi < 30) {
                category = 'Overweight';
                categoryClass = 'text-warning';
            } else {
                category = 'Obese';
                categoryClass = 'text-danger';
            }
            
            bmiValue.textContent = bmi;
            bmiCategory.textContent = category;
            bmiCategory.className = categoryClass;
            bmiResult.classList.remove('d-none');
        } else {
            bmiResult.classList.add('d-none');
        }
    }
    
    heightInput.addEventListener('input', calculateBMI);
    weightInput.addEventListener('input', calculateBMI);
    
    // Auto-check senior citizen based on age
    const residentSelect = document.getElementById('resident_id');
    const seniorCheckbox = document.getElementById('is_senior_citizen');
    
    residentSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const age = selectedOption.getAttribute('data-age');
        
        if (age && !isNaN(age) && parseInt(age) >= 60) {
            seniorCheckbox.checked = true;
        }
    });
});
</script>
@endpush

@endsection
