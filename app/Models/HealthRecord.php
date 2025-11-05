<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class HealthRecord extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'barangay_id',
        'is_senior_citizen',
        'is_pregnant',
        'expected_due_date',
        'trimester',
        'is_malnourished',
        'malnutrition_type',
        'height',
        'weight',
        'bmi',
        'blood_type',
        'blood_pressure',
        'temperature',
        'medical_conditions',
        'current_medications',
        'allergies',
        'medical_history',
        'immunizations',
        'last_immunization_date',
        'last_immunization_type',
        'last_checkup_date',
        'next_checkup_date',
        'checkup_notes',
        'prenatal_visits_count',
        'last_prenatal_visit',
        'next_prenatal_visit',
        'prenatal_notes',
        'is_using_family_planning',
        'family_planning_method',
        'family_planning_start_date',
        'emergency_contact_name',
        'emergency_contact_number',
        'emergency_contact_relationship',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_senior_citizen' => 'boolean',
        'is_pregnant' => 'boolean',
        'is_malnourished' => 'boolean',
        'is_using_family_planning' => 'boolean',
        'expected_due_date' => 'date',
        'last_immunization_date' => 'date',
        'last_checkup_date' => 'date',
        'next_checkup_date' => 'date',
        'last_prenatal_visit' => 'date',
        'next_prenatal_visit' => 'date',
        'family_planning_start_date' => 'date',
        'immunizations' => 'array',
        'height' => 'decimal:2',
        'weight' => 'decimal:2',
        'bmi' => 'decimal:2',
        'temperature' => 'decimal:2',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('health_record')
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class, 'barangay_id', 'barangay_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Accessor for BMI calculation
    public function calculateBmi()
    {
        if ($this->height && $this->weight) {
            $heightInMeters = $this->height / 100;
            $bmi = $this->weight / ($heightInMeters * $heightInMeters);
            return round($bmi, 2);
        }
        return null;
    }

    // Get BMI category
    public function getBmiCategoryAttribute()
    {
        $bmi = $this->bmi ?? $this->calculateBmi();
        if (!$bmi) return 'Unknown';
        
        if ($bmi < 18.5) return 'Underweight';
        if ($bmi < 25) return 'Normal';
        if ($bmi < 30) return 'Overweight';
        return 'Obese';
    }

    // Get pregnancy status text
    public function getPregnancyStatusAttribute()
    {
        if (!$this->is_pregnant) return 'Not Pregnant';
        
        if ($this->trimester) {
            return "Pregnant - {$this->trimester}{$this->getOrdinalSuffix($this->trimester)} Trimester";
        }
        
        return 'Pregnant';
    }

    private function getOrdinalSuffix($number)
    {
        $suffixes = ['th', 'st', 'nd', 'rd'];
        $mod = $number % 100;
        return ($mod >= 11 && $mod <= 13) ? 'th' : ($suffixes[$number % 10] ?? 'th');
    }

    // Check if immunizations are up to date
    public function getImmunizationsUpToDateAttribute()
    {
        if (!$this->last_immunization_date) return false;
        
        // Consider up to date if within last year
        return $this->last_immunization_date->greaterThan(now()->subYear());
    }

    // Check if checkup is overdue
    public function getCheckupOverdueAttribute()
    {
        if (!$this->next_checkup_date) return false;
        
        return $this->next_checkup_date->lessThan(now());
    }

    // Check if prenatal visit is overdue
    public function getPrenatalOverdueAttribute()
    {
        if (!$this->is_pregnant || !$this->next_prenatal_visit) return false;
        
        return $this->next_prenatal_visit->lessThan(now());
    }
}
