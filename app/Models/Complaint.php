<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Complaint extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'barangay_id',
        'complaint_type',
        'subject',
        'description',
        'incident_details',
        'incident_date',
        'incident_location',
        'involved_parties',
        'status',
        'filed_at',
        'approved_at',
        'approved_by',
        'scheduled_at',
        'resolved_at',
        'dismissal_reason',
        'resolution_notes',
        'admin_notes',
    ];
    protected $casts = [
        'filed_at' => 'datetime',
        'approved_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'resolved_at' => 'datetime',
        'incident_date' => 'date',
        'involved_parties' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('complaint')
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class, 'barangay_id', 'barangay_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getComplainantNameAttribute()
    {
        $resident = $this->resident;
        return $resident ? $resident->first_name . ' ' . $resident->last_name : 'Unknown';
    }

    public function getFormattedComplaintTypeAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->complaint_type));
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => ['badge-warning', 'Pending'],
            'approved' => ['badge-info', 'Approved'],
            'scheduled' => ['badge-primary', 'Scheduled'],
            'resolved' => ['badge-success', 'Resolved'],
            'dismissed' => ['badge-danger', 'Dismissed'],
        ];
        $status = $this->status;
        $badge = $badges[$status] ?? ['badge-secondary', ucfirst($status)];
        return '<span class="badge ' . $badge[0] . '">' . $badge[1] . '</span>';
    }
}
