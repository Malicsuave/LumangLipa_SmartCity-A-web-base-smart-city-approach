<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks'; // Explicitly set table name

    protected $fillable = [
        'request_id',
        'rating',
        'comment',
        'service_type'
    ];

    protected $casts = [
        'rating' => 'integer',
        'request_id' => 'integer'
    ];
}
