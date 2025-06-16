<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory;    protected $fillable = [
        'barangay_id',
        'complaint_type',
        'subject',
        'description',
        'status',
        'filed_at',
        'approved_at',
        'approved_by',
        'scheduled_at',
        'resolved_at',
        'dismissal_reason',
        'resolution_notes',
        'admin_notes',
    ];    protected $casts = [
        'filed_at' => 'datetime',
        'approved_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

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
            'pending' => 'badge-warning',
            'approved' => 'badge-info',
            'scheduled' => 'badge-primary',
            'resolved' => 'badge-success',
            'dismissed' => 'badge-danger',
        ];

        return $badges[$this->status] ?? 'badge-secondary';
    }
}
