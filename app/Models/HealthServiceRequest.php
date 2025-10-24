<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class HealthServiceRequest extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'barangay_id',
        'service_type',
        'purpose',
        'status',
        'requested_at',
        'approved_at',
        'approved_by',
        'scheduled_at',
        'completed_at',
        'rejection_reason',
        'admin_notes',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('health_service_request')
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class, 'barangay_id', 'barangay_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    public function getResidentNameAttribute()
    {
        $resident = $this->resident;
        if ($resident) {
            return trim("{$resident->first_name} {$resident->middle_name} {$resident->last_name}");
        }
        return 'Unknown Resident';
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="badge badge-warning">Pending</span>',
            'approved' => '<span class="badge badge-success">Approved</span>',
            'scheduled' => '<span class="badge badge-info">Scheduled</span>',
            'completed' => '<span class="badge badge-primary">Completed</span>',
            'rejected' => '<span class="badge badge-danger">Rejected</span>',
            default => '<span class="badge badge-secondary">Unknown</span>',
        };    }
}
