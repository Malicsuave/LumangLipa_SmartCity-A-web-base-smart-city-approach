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
        'captain_photo',
        'secretary_photo',
        'sk_chairperson_photo',
        'treasurer_photo',
        'councilor1_photo',
        'councilor2_photo',
        'councilor3_photo',
        'councilor4_photo',
        'councilor5_photo',
        'councilor6_photo',
        'councilor7_photo',
    ];
}
