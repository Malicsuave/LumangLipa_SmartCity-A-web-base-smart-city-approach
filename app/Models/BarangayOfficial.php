<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangayOfficial extends Model
{
    use HasFactory;

    protected $fillable = [
        'captain_name',
        'secretary_name',
        'sk_chairperson_name',
        'treasurer_name',
        'councilor1_name',
        'councilor2_name',
        'councilor3_name',
        'councilor4_name',
        'councilor5_name',
        'councilor6_name',
        'councilor7_name',
    ];
}
