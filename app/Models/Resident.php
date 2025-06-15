<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use App\Traits\TracksActivity;

class Resident extends Model
{
    use HasFactory, TracksActivity, SoftDeletes, Notifiable;

    protected $fillable = [
        'barangay_id',
        'type_of_resident',
        'last_name',
        'first_name',
        'middle_name',
        'suffix',
        'birthplace',
        'birthdate',
        'sex',
        'civil_status',
        'citizenship_type',
        'citizenship_country',
        'profession_occupation',
        'monthly_income',
        'contact_number',
        'email_address',
        'religion',
        'educational_attainment',
        'education_status',
        'mother_first_name',
        'mother_middle_name',
        'mother_last_name',
        'address',
        'philsys_id',
        'population_sectors',
        'photo',
        'signature',
        'id_status',
        'id_issued_at',
        'id_expires_at',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'population_sectors' => 'array',
        'id_issued_at' => 'date',
        'id_expires_at' => 'date',
    ];

    /**
     * Auto-generate barangay ID when creating resident
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($resident) {
            if (empty($resident->barangay_id)) {
                $resident->barangay_id = self::generateBarangayId();
            }
        });
    }

    /**
     * Generate unique barangay ID
     */
    public static function generateBarangayId(): string
    {
        $year = date('Y');
        $lastResident = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNumber = $lastResident ? (int)substr($lastResident->barangay_id, -4) + 1 : 1;
        
        return 'BRG-LUM-' . $year . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get full name attribute
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
     * Get age attribute
     */
    public function getAgeAttribute(): int
    {
        return $this->birthdate->age;
    }

    /**
     * Get mother's full name
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
     * Relationship with household information
     */
    public function household(): HasOne
    {
        return $this->hasOne(Household::class);
    }

    /**
     * Relationship with family members
     */
    public function familyMembers(): HasMany
    {
        return $this->hasMany(FamilyMember::class);
    }
    
    /**
     * Relationship with senior citizen information
     */
    public function seniorCitizen(): HasOne
    {
        return $this->hasOne(SeniorCitizen::class);
    }
    
    /**
     * Relationship with GAD (Gender and Development) information
     */
    public function gad(): HasOne
    {
        return $this->hasOne(Gad::class);
    }
    
    /**
     * Check if resident is a senior citizen (60 years old or above)
     */
    public function getIsSeniorCitizenAttribute(): bool
    {
        return $this->birthdate && $this->birthdate->diffInYears(now()) >= 60;
    }

    /**
     * Population sectors constants
     */
    public static function getPopulationSectors(): array
    {
        return [
            'Labor Force',
            'Overseas Filipino Worker',
            'Solo Parent',
            'Person with Disability',
            'Indigenous People',
            'Employed',
            'Self-employed (including businessman/women)',
            'Unemployed',
            'Student',
            'Out of school children (6-14 years old)',
            'Out of School Youth (15-24 years old)',
            'Not applicable'
        ];
    }

    /**
     * Scope for searching residents
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('barangay_id', 'like', "%{$search}%")
              ->orWhere('contact_number', 'like', "%{$search}%");
        });
    }
    
    /**
     * Get the photo URL attribute
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/residents/photos/' . $this->photo);
        }
        
        // Default avatar based on gender
        if ($this->sex === 'Female') {
            return asset('images/avatars/female-avatar.png');
        }
        
        return asset('images/avatars/male-avatar.png');
    }
    
    /**
     * Get the signature URL attribute
     */
    public function getSignatureUrlAttribute(): ?string
    {
        return $this->signature 
            ? asset('storage/residents/signatures/' . $this->signature)
            : null;
    }
    
    /**
     * Get the ID card status
     */
    public function getIdStatusLabelAttribute(): string
    {
        return match($this->id_status) {
            'issued' => 'Issued',
            'pending' => 'Pending',
            'needs_renewal' => 'Needs Renewal',
            default => 'Not Issued'
        };
    }
    
    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->email_address ?: $this->email; // Try both possible email field names
    }
}