<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\TracksActivity;
use Carbon\Carbon;

class FamilyMember extends Model
{
    use HasFactory, TracksActivity;

    protected $fillable = [
        'resident_id',
        'household_id',
        'name',
        'relationship',
        'related_to',
        'gender',
        'birthday',
        'work',
        'contact_number',
        'allergies',
        'medical_condition',
    ];

    protected $casts = [
        'birthday' => 'date',
    ];

    /**
     * Relationship with resident
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Get age attribute
     */
    public function getAgeAttribute(): int
    {
        return $this->birthday->age;
    }
}
