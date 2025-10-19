<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class CensusMember extends Model
{
    use HasFactory;

    protected $primaryKey = 'member_id';
    
    protected $fillable = [
        'household_id',
        'fullname',
        'relationship_to_head',
        'dob',
        'gender',
        'civil_status',
        'education',
        'occupation',
        'category',
    ];

    protected $casts = [
        'dob' => 'date',
    ];

    /**
     * Get the household that owns the member.
     */
    public function household(): BelongsTo
    {
        return $this->belongsTo(CensusHousehold::class, 'household_id', 'household_id');
    }

    /**
     * Get the member's age.
     */
    public function getAgeAttribute()
    {
        return $this->dob ? Carbon::parse($this->dob)->age : null;
    }
}
