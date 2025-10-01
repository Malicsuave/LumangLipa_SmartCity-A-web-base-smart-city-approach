<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CensusHousehold extends Model
{
    use HasFactory;

    protected $primaryKey = 'household_id';
    
    protected $fillable = [
        'head_name',
        'address',
        'contact_number',
        'housing_type',
    ];

    /**
     * Get the census members for the household.
     */
    public function members(): HasMany
    {
        return $this->hasMany(CensusMember::class, 'household_id', 'household_id');
    }

    /**
     * Get the total number of members in the household.
     */
    public function getTotalMembersAttribute()
    {
        return $this->members()->count();
    }
}
