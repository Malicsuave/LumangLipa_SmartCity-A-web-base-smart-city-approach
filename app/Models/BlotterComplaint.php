<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlotterComplaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'barangay_id',
        'case_number',
        'complainants',
        'respondents',
        'complaint_details',
        'resolution_sought',
        'verification_method',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function resident()
    {
        return $this->belongsTo(Resident::class, 'barangay_id', 'barangay_id');
    }

    // Query Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeUnderInvestigation($query)
    {
        return $query->where('status', 'under_investigation');
    }

    // Helper Methods
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'under_investigation' => '<span class="badge bg-info">Under Investigation</span>',
            'resolved' => '<span class="badge bg-success">Resolved</span>',
            'dismissed' => '<span class="badge bg-secondary">Dismissed</span>',
        ];

        return $badges[$this->status] ?? '<span class="badge bg-secondary">Unknown</span>';
    }

    public function getFormattedStatusAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }

    // Generate unique case number
    public static function generateCaseNumber()
    {
        $year = date('Y');
        $prefix = 'BLC-' . $year . '-';
        
        $lastCase = self::whereYear('created_at', $year)
            ->orderBy('case_number', 'desc')
            ->first();
        
        if ($lastCase) {
            $lastNumber = intval(substr($lastCase->case_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
