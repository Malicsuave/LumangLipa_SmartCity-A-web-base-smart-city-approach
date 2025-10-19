<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'health_service_request_id',
        'meeting_title',
        'meeting_date',
        'meeting_location',
        'meeting_notes',
        'status',
        'created_by',
    ];

    protected $casts = [
        'meeting_date' => 'datetime',
    ];

    public function healthServiceRequest(): BelongsTo
    {
        return $this->belongsTo(HealthServiceRequest::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'scheduled' => '<span class="badge badge-info">Scheduled</span>',
            'completed' => '<span class="badge badge-success">Completed</span>',
            'cancelled' => '<span class="badge badge-danger">Cancelled</span>',
            default => '<span class="badge badge-secondary">Unknown</span>',
        };
    }
}
