<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeniorPreRegistration extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($seniorPreRegistration) {
            if (!$seniorPreRegistration->registration_id) {
                $seniorPreRegistration->registration_id = static::generateRegistrationId();
            }
        });
    }

    /**
     * Generate a unique registration ID with unified format: PRE-YYYY-MM-XX
     */
    public static function generateRegistrationId(): string
    {
        $date = now();
        $yearMonth = $date->format('Y-m');
        
        // Keep trying until we find a unique ID
        $attempt = 1;
        do {
            // Get the count of ALL registrations (both regular and senior) for this month
            $regularCount = \App\Models\PreRegistration::where('registration_id', 'LIKE', "PRE-{$yearMonth}-%")->count();
            $seniorCount = static::where('registration_id', 'LIKE', "PRE-{$yearMonth}-%")->count();
            
            $totalCount = $regularCount + $seniorCount;
            $newNumber = $totalCount + $attempt;
            
            $registrationId = sprintf('PRE-%s-%02d', $yearMonth, $newNumber);
            
            // Check if this ID already exists in either table
            $existsInRegular = \App\Models\PreRegistration::where('registration_id', $registrationId)->exists();
            $existsInSenior = static::where('registration_id', $registrationId)->exists();
            
            if (!$existsInRegular && !$existsInSenior) {
                return $registrationId;
            }
            
            $attempt++;
        } while ($attempt <= 100); // Prevent infinite loop
        
        // Fallback with timestamp if all else fails
        return sprintf('PRE-%s-%s', $yearMonth, time());
    }

    protected $fillable = [
        'registration_id',
        
        // Step 1: Personal Information
        'type_of_resident',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birthdate',
        'birthplace',
        'sex',
        'civil_status',
        'citizenship_type',
        'citizenship_country',
        'nationality',
        'religion',
        'educational_attainment',
        'education_status',
        'profession_occupation',
        
        // Step 2: Contact & Address
        'contact_number',
        'email_address',
        'address',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_number',
        'emergency_contact_address',
        
        // Step 3: Photos & Documents
        'photo',
        'signature',
        'proof_of_residency',
        
        // Step 4: Senior Specific Information
        'health_condition',
        'mobility_status',
        'medical_conditions',
        'receiving_pension',
        'pension_type',
        'pension_amount',
        'has_philhealth',
        'philhealth_number',
        'has_senior_discount_card',
        'services',
        'notes',
        
        // System fields
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'rejected_at',
        'rejected_by',
        'senior_citizen_id',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'pension_amount' => 'decimal:2',
        'receiving_pension' => 'boolean',
        'has_philhealth' => 'boolean',
        'has_senior_discount_card' => 'boolean',
        'services' => 'array',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the full name attribute
     */
    public function getFullNameAttribute()
    {
        $nameParts = array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->suffix
        ]);
        
        return implode(' ', $nameParts);
    }

    /**
     * Get the age attribute
     */
    public function getAgeAttribute()
    {
        if (!$this->birthdate) {
            return null;
        }
        
        return \Carbon\Carbon::parse($this->birthdate)->age;
    }
}
