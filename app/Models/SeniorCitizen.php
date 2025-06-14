<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\TracksActivity;

class SeniorCitizen extends Model
{
    use HasFactory, TracksActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'resident_id',
        'senior_id_number',
        'senior_id_issued_at',
        'senior_id_expires_at',
        'senior_id_status',
        'health_conditions',
        'medications',
        'allergies',
        'blood_type',
        'emergency_contact_name',
        'emergency_contact_number',
        'emergency_contact_relationship',
        'receiving_pension',
        'pension_type',
        'pension_amount',
        'has_philhealth',
        'philhealth_number',
        'has_senior_discount_card',
        'programs_enrolled',
        'last_medical_checkup',
        'special_needs',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'senior_id_issued_at' => 'date',
        'senior_id_expires_at' => 'date',
        'receiving_pension' => 'boolean',
        'pension_amount' => 'decimal:2',
        'has_philhealth' => 'boolean',
        'has_senior_discount_card' => 'boolean',
        'programs_enrolled' => 'array',
        'last_medical_checkup' => 'date',
    ];

    /**
     * Get the resident that owns the senior citizen record.
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Generate a unique senior ID number
     * 
     * @return string
     */
    public static function generateSeniorIdNumber(): string
    {
        $year = date('Y');
        $lastSenior = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $nextNumber = $lastSenior ? (int)substr($lastSenior->senior_id_number, -3) + 1 : 1;
        
        return 'SENIOR-LUM-' . $year . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
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
        
        return $this->senior_id_expires_at && $this->senior_id_expires_at->isPast();
    }
    
    /**
     * Get formatted pension amount
     * 
     * @return string|null
     */
    public function getFormattedPensionAmountAttribute(): ?string
    {
        return $this->pension_amount ? '₱' . number_format($this->pension_amount, 2) : null;
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
}
