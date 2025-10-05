<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PreRegistration extends Model
{
    use HasFactory;

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($preRegistration) {
            if (!$preRegistration->registration_id) {
                $preRegistration->registration_id = static::generateRegistrationId();
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
            $regularCount = static::where('registration_id', 'LIKE', "PRE-{$yearMonth}-%")->count();
            $seniorCount = \App\Models\SeniorPreRegistration::where('registration_id', 'LIKE', "PRE-{$yearMonth}-%")->count();
            
            $totalCount = $regularCount + $seniorCount;
            $newNumber = $totalCount + $attempt;
            
            $registrationId = sprintf('PRE-%s-%02d', $yearMonth, $newNumber);
            
            // Check if this ID already exists in either table
            $existsInRegular = static::where('registration_id', $registrationId)->exists();
            $existsInSenior = \App\Models\SeniorPreRegistration::where('registration_id', $registrationId)->exists();
            
            if (!$existsInRegular && !$existsInSenior) {
                return $registrationId;
            }
            
            $attempt++;
        } while ($attempt <= 100); // Prevent infinite loop
        
        // Fallback with timestamp if all else fails
        return sprintf('PRE-%s-%s', $yearMonth, time());
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registration_id',
        'type_of_resident',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birthplace',
        'birthdate',
        'sex',
        'civil_status',
        'citizenship_type',
        'citizenship_country',
        'profession_occupation',
        'contact_number',
        'email_address',
        'religion',
        'educational_attainment',
        'education_status',
        'address',
        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_number',
        'emergency_contact_address',
        'photo',
        'signature',
        'proof_of_residency',
        'status',
        'rejection_reason',
        'approved_at',
        'rejected_at',
        'approved_by',
        'rejected_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birthdate' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    /**
     * Get the admin who approved this registration.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the admin who rejected this registration.
     */
    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Get the resident created after approval.
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Get the full name attribute.
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
     * Get the mother's full name attribute.
     */
    public function getMotherFullNameAttribute(): ?string
    {
        if (!$this->mother_first_name && !$this->mother_last_name) {
            return null;
        }
        
        $name = $this->mother_first_name ?? '';
        if ($this->mother_middle_name) {
            $name .= ' ' . $this->mother_middle_name;
        }
        if ($this->mother_last_name) {
            $name .= ' ' . $this->mother_last_name;
        }
        return trim($name);
    }

    /**
     * Check if the applicant is a senior citizen (60 years old or above).
     */
    public function getIsSeniorCitizenAttribute(): bool
    {
        return $this->birthdate && Carbon::parse($this->birthdate)->diffInYears(now()) >= 60;
    }

    /**
     * Get the age of the applicant.
     */
    public function getAgeAttribute(): int
    {
        return $this->birthdate ? Carbon::parse($this->birthdate)->diffInYears(now()) : 0;
    }

    /**
     * Scope for pending registrations.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved registrations.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected registrations.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope for searching pre-registrations.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('middle_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('email_address', 'like', "%{$search}%")
                ->orWhere('contact_number', 'like', "%{$search}%")
                ->orWhere('address', 'like', "%{$search}%");
        });
    }
}
