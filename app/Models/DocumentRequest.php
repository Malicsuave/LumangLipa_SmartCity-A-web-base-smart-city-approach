<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Str;

class DocumentRequest extends Model
{
    use HasFactory;
    use LogsActivity;

    protected static $logName = 'document_request';
    protected static $logAttributes = [
        'barangay_id',
        'document_type',
        'purpose',
        'status',
        'requested_at',
        'approved_at',
        'approved_by',
        'claimed_at',
        'claimed_by',
        'rejection_reason',
    ];
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;
    protected static $logAttributesToIgnore = [];

    protected $fillable = [
        'barangay_id',
        'document_type',
        'purpose',
        'status',
        'requested_at',
        'approved_at',
        'approved_by',
        'claimed_at',
        'claimed_by',
        'rejection_reason',
        'uuid', // Add uuid to fillable
        'resident_id', // <-- Add this line!
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'claimed_at' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class, 'barangay_id', 'barangay_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'approved_by');
    }

    public function claimedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'claimed_by');
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
            'claimed' => '<span class="badge badge-info">Claimed</span>',
            'rejected' => '<span class="badge badge-danger">Rejected</span>',
            default => '<span class="badge badge-secondary">Unknown</span>',
        };
    }

    public function getActivitylogOptions(): \Spatie\Activitylog\LogOptions
    {
        return \Spatie\Activitylog\LogOptions::defaults()
            ->useLogName('document_request')
            ->logOnly([
                'barangay_id',
                'document_type',
                'purpose',
                'status',
                'requested_at',
                'approved_at',
                'approved_by',
                'claimed_at',
                'claimed_by',
                'rejection_reason',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
