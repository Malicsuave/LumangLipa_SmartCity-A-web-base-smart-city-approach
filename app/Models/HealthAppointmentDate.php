<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HealthAppointmentDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_date',
        'title',
        'description',
        'location',
        'start_time',
        'end_time',
        'max_slots',
        'booked_slots',
        'status',
        'created_by',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(HealthServiceRequest::class, 'appointment_date_id');
    }

    public function getAvailableSlotsAttribute()
    {
        return $this->max_slots - $this->booked_slots;
    }

    public function getIsFullAttribute()
    {
        return $this->booked_slots >= $this->max_slots;
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'open' => '<span class="badge badge-success">Open for Booking</span>',
            'closed' => '<span class="badge badge-warning">Closed</span>',
            'completed' => '<span class="badge badge-primary">Completed</span>',
            'cancelled' => '<span class="badge badge-danger">Cancelled</span>',
            default => '<span class="badge badge-secondary">Unknown</span>',
        };
    }
}
