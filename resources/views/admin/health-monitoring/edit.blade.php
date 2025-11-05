@extends('layouts.admin')

@section('title', 'Edit Health Record')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="h3 mb-0">Edit Health Record</h2>
            <p class="text-muted">Update health record for {{ $healthRecord->resident->first_name }} {{ $healthRecord->resident->last_name }}</p>
        </div>
    </div>

    <form action="{{ route('admin.health-monitoring.update', $healthRecord->id) }}" method="POST" id="healthRecordForm">
        @csrf
        @method('PUT')
        
        <!-- Resident Information (Read-only) -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Resident Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Resident Name</label>
                            <input type="text" class="form-control" readonly
                                   value="{{ $healthRecord->resident->first_name }} {{ $healthRecord->resident->middle_name }} {{ $healthRecord->resident->last_name }}">
                            <input type="hidden" name="resident_id" value="{{ $healthRecord->resident_id }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="emergency_contact" class="form-label">Emergency Contact</label>
                            <input type="text" name="emergency_contact" id="emergency_contact" class="form-control" 
                                   value="{{ old('emergency_contact', $healthRecord->emergency_contact) }}"
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
                                   class="form-check-input" value="1"
                                   {{ old('is_senior_citizen', $healthRecord->is_senior_citizen) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_senior_citizen">
                                <strong>Senior Citizen</strong> (60+ years old)
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_pregnant" id="is_pregnant" 
                                   class="form-check-input" value="1"
                                   {{ old('is_pregnant', $healthRecord->is_pregnant) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_pregnant">
                                <strong>Pregnant</strong>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check mb-3">
                            <input type="checkbox" name="is_malnourished" id="is_malnourished" 
                                   class="form-check-input" value="1"
                                   {{ old('is_malnourished', $healthRecord->is_malnourished) ? 'checked' : '' }}>
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
                                   class="form-control" value="{{ old('height', $healthRecord->height) }}"
                                   placeholder="e.g., 165.5">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="weight" class="form-label">Weight (kg)</label>
                            <input type="number" step="0.1" name="weight" id="weight" 
                                   class="form-control" value="{{ old('weight', $healthRecord->weight) }}"
                                   placeholder="e.g., 65.0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="blood_pressure" class="form-label">Blood Pressure</label>
                            <input type="text" name="blood_pressure" id="blood_pressure" 
                                   class="form-control" value="{{ old('blood_pressure', $healthRecord->blood_pressure) }}"
                                   placeholder="e.g., 120/80">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="temperature" class="form-label">Temperature (°C)</label>
                            <input type="number" step="0.1" name="temperature" id="temperature" 
                                   class="form-control" value="{{ old('temperature', $healthRecord->temperature) }}"
                                   placeholder="e.g., 36.5">
                        </div>
                    </div>
                </div>
                @if($healthRecord->bmi)
                <div class="alert alert-info">
                    <strong>Current BMI:</strong> {{ number_format($healthRecord->bmi, 2) }} - {{ $healthRecord->getBmiCategory() }}
                </div>
                @endif
                <div id="bmi-result" class="alert alert-info d-none">
                    <strong>Updated BMI:</strong> <span id="bmi-value"></span> - <span id="bmi-category"></span>
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
                                @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $type)
                                    <option value="{{ $type }}" {{ old('blood_type', $healthRecord->blood_type) == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="malnutrition_type" class="form-label">Malnutrition Type</label>
                            <select name="malnutrition_type" id="malnutrition_type" class="form-select">
                                <option value="">-- Select Type --</option>
                                @foreach(['Underweight', 'Stunted', 'Wasted', 'Overweight'] as $type)
                                    <option value="{{ $type }}" {{ old('malnutrition_type', $healthRecord->malnutrition_type) == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="allergies" class="form-label">Allergies</label>
                    <textarea name="allergies" id="allergies" class="form-control" rows="2" 
                              placeholder="List any known allergies...">{{ old('allergies', $healthRecord->allergies) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="current_medications" class="form-label">Current Medications</label>
                    <textarea name="current_medications" id="current_medications" class="form-control" rows="2" 
                              placeholder="List current medications...">{{ old('current_medications', $healthRecord->current_medications) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="medical_conditions" class="form-label">Medical Conditions</label>
                    <textarea name="medical_conditions" id="medical_conditions" class="form-control" rows="2" 
                              placeholder="List any chronic conditions...">{{ old('medical_conditions', $healthRecord->medical_conditions) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Pregnancy Information -->
        <div class="card mb-4 {{ $healthRecord->is_pregnant ? '' : 'd-none' }}" id="pregnancy-section">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-baby me-2"></i>Pregnancy Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="pregnancy_weeks" class="form-label">Weeks Pregnant</label>
                            <input type="number" name="pregnancy_weeks" id="pregnancy_weeks" 
                                   class="form-control" min="1" max="42" 
                                   value="{{ old('pregnancy_weeks', $healthRecord->pregnancy_weeks) }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="expected_delivery_date" class="form-label">Expected Delivery Date</label>
                            <input type="date" name="expected_delivery_date" id="expected_delivery_date" 
                                   class="form-control"
                                   value="{{ old('expected_delivery_date', $healthRecord->expected_delivery_date ? $healthRecord->expected_delivery_date->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="next_prenatal_visit" class="form-label">Next Prenatal Visit</label>
                            <input type="date" name="next_prenatal_visit" id="next_prenatal_visit" 
                                   class="form-control"
                                   value="{{ old('next_prenatal_visit', $healthRecord->next_prenatal_visit ? $healthRecord->next_prenatal_visit->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="pregnancy_complications" class="form-label">Pregnancy Complications</label>
                    <textarea name="pregnancy_complications" id="pregnancy_complications" 
                              class="form-control" rows="2">{{ old('pregnancy_complications', $healthRecord->pregnancy_complications) }}</textarea>
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
                                   class="form-control"
                                   value="{{ old('last_checkup_date', $healthRecord->last_checkup_date ? $healthRecord->last_checkup_date->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="next_checkup_date" class="form-label">Next Checkup Date</label>
                            <input type="date" name="next_checkup_date" id="next_checkup_date" 
                                   class="form-control"
                                   value="{{ old('next_checkup_date', $healthRecord->next_checkup_date ? $healthRecord->next_checkup_date->format('Y-m-d') : '') }}">
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
                    <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $healthRecord->notes) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.health-monitoring.show', $healthRecord->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Health Record
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
});
</script>
@endpush

@endsection
