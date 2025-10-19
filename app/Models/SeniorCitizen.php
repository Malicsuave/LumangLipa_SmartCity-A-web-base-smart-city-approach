<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Traits\TracksActivity;

class SeniorCitizen extends Model
{
    use HasFactory, TracksActivity, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // Step 1: Personal Information
        'type_of_resident',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birthdate',
        'birthplace',
        'sex',
        'sex_details',
        'civil_status',
        'citizenship_type',
        'citizenship_country',
        'educational_attainment',
        'education_status',
        'religion',
        'profession_occupation',
        
        // Step 2: Contact Information
        'contact_number',
        'email_address',
        'current_address',
        'purok',
        
        // Step 3: Photo and Signature
        'photo',
        'signature',
        
        // Step 4: Senior Citizen Specific Information
        'health_condition',
        'mobility_status',
        'medical_conditions',
        'emergency_contact_name',
        'emergency_contact_number',
        'emergency_contact_relationship',
        'receiving_pension',
        'pension_type',
        'pension_amount',
        'has_philhealth',
        'philhealth_number',
        'has_senior_discount_card',
        'services',
        
        // Senior ID Management
        'senior_id_number',
        'senior_id_issued_at',
        'senior_id_expires_at',
        'senior_id_status',
        
        // Additional fields
        'notes',
        'registered_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'birthdate' => 'date',
        'senior_id_issued_at' => 'date',
        'senior_id_expires_at' => 'date',
        'receiving_pension' => 'boolean',
        'pension_amount' => 'decimal:2',
        'has_philhealth' => 'boolean',
        'has_senior_discount_card' => 'boolean',
        'services' => 'array',
    ];

    /**
     * Get the user who registered this senior citizen.
     */
    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }
    
    /**
     * Get the full name attribute
     */
    public function getFullNameAttribute(): string
    {
        $name = $this->first_name;
        if ($this->middle_name) {
            $name .= ' ' . $this->middle_name;
        }
        $name .= ' ' . $this->last_name;
        if ($this->suffix) {
            $name .= ' ' . $this->suffix;
        }
        return $name;
    }
    
    /**
     * Get the age attribute
     */
    public function getAgeAttribute(): int
    {
        return \Carbon\Carbon::parse($this->birthdate)->diffInYears(now());
    }

    /**
     * Generate a unique senior ID number
     * 
     * @return string
     */
    public static function generateSeniorIdNumber(): string
    {
        $year = date('Y');
        $prefix = "SC-LUM-{$year}-";
        
        // Get the last senior citizen registered this year
        $lastSenior = self::where('senior_id_number', 'like', $prefix . '%')
            ->orderBy('senior_id_number', 'desc')
            ->first();
        
        if ($lastSenior) {
            // Extract the number from the last ID
            $lastNumber = (int) substr($lastSenior->senior_id_number, -4);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }
        
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Check if the senior ID needs renewal
     * 
     * @return boolean
     */
    public function needsRenewal(): bool
    {
        if ($this->senior_id_status !== 'issued') {
            return false;
        }
        
        return $this->senior_id_expires_at && $this->senior_id_expires_at < now();
    }
    
    /**
     * Get formatted pension amount
     * 
     * @return string|null
     */
    public function getFormattedPensionAmountAttribute(): ?string
    {
        return $this->pension_amount ? '₱' . number_format((float)$this->pension_amount, 2) : null;
    }
    
    /**
     * Get the senior ID status label
     */
    public function getIdStatusLabelAttribute(): string
    {
        return match($this->senior_id_status) {
            'issued' => 'Issued',
            'pending' => 'Pending',
            'needs_renewal' => 'Needs Renewal',
            'expired' => 'Expired',
            default => 'Not Issued'
        };
    }
    
    /**
     * Route notifications for the mail channel.
     */
    public function routeNotificationForMail($notification)
    {
        return $this->email_address;
    }
}
