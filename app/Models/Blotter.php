<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Blotter extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'blotter_id',
        'barangay_id',
        'resident_id',
        'incident_type',
        'incident_title',
        'incident_description',
        'incident_date',
        'incident_time',
        'incident_location',
        'parties_involved',
        'witnesses',
        'desired_resolution',
        'evidence_files',
        'status',
        'filed_at',
        'approved_at',
        'approved_by',
        'resolved_at',
        'resolution_notes',
        'admin_notes'
    ];

    protected $casts = [
        'filed_at' => 'datetime',
        'approved_at' => 'datetime',
        'resolved_at' => 'datetime',
        'incident_date' => 'date'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('blotter')
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Get the resident that owns the blotter report.
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class, 'barangay_id', 'barangay_id');
    }

    /**
     * Get the user who approved the blotter report.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the status color for display.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'approved' => 'info',
            'investigating' => 'primary',
            'resolved' => 'success',
            'dismissed' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get the status display name.
     */
    public function getStatusDisplayAttribute()
    {
        return match($this->status) {
            'pending' => 'Pending Review',
            'approved' => 'Under Investigation',
            'investigating' => 'Investigating',
            'resolved' => 'Resolved',
            'dismissed' => 'Dismissed',
            default => ucfirst($this->status)
        };
    }

    /**
     * Scope to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get recent blotter reports.
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('filed_at', 'desc')->limit($limit);
    }
}