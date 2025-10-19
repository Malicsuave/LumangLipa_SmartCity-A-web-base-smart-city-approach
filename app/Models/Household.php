<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\TracksActivity;

class Household extends Model
{
    use HasFactory, TracksActivity;

    protected $fillable = [
        'resident_id',
        'primary_name',
        'primary_birthday',
        'primary_gender',
        'primary_phone',
        'primary_work',
        'primary_allergies',
        'primary_medical_condition',
        'secondary_name',
        'secondary_birthday',
        'secondary_gender',
        'secondary_phone',
        'secondary_work',
        'secondary_allergies',
        'secondary_medical_condition',
        'emergency_contact_name',
        'emergency_relationship',
        'emergency_work',
        'emergency_phone',
    ];

    protected $casts = [
        'primary_birthday' => 'date',
        'secondary_birthday' => 'date',
    ];

    /**
     * Relationship with resident
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Get primary person age
     */
    public function getPrimaryAgeAttribute(): ?int
    {
        return $this->primary_birthday ? $this->primary_birthday->age : null;
    }

    /**
     * Get secondary person age
     */
    public function getSecondaryAgeAttribute(): ?int
    {
        return $this->secondary_birthday ? $this->secondary_birthday->age : null;
    }
}
