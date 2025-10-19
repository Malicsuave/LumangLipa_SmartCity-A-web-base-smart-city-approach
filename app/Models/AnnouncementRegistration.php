<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'announcement_id',
        'first_name',
        'last_name',
        'middle_name',
        'email',
        'phone',
        'address',
        'age',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the announcement that owns the registration.
     */
    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }
}
