<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\TracksActivity;

class Gad extends Model
{
    use HasFactory, TracksActivity, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'resident_id',
        'gender_identity',
        'gender_details',
        'programs_enrolled',
        'enrollment_date',
        'program_end_date',
        'program_status',
        'is_pregnant',
        'due_date',
        'is_lactating',
        'needs_maternity_support',
        'is_vaw_case',
        'vaw_report_date',
        'vaw_case_number',
        'vaw_case_details',
        'vaw_case_status',
        'is_solo_parent',
        'solo_parent_id',
        'solo_parent_id_issued',
        'solo_parent_id_expiry',
        'solo_parent_details',
        'assistance_provided',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'programs_enrolled' => 'array',
        'enrollment_date' => 'date',
        'program_end_date' => 'date',
        'is_pregnant' => 'boolean',
        'due_date' => 'date',
        'is_lactating' => 'boolean',
        'needs_maternity_support' => 'boolean',
        'is_vaw_case' => 'boolean',
        'vaw_report_date' => 'date',
        'is_solo_parent' => 'boolean',
        'solo_parent_id_issued' => 'date',
        'solo_parent_id_expiry' => 'date',
    ];

    /**
     * Get the resident that owns the GAD record.
     */
    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Get available GAD program types
     * 
     * @return array
     */
    public static function getProgramTypes(): array
    {
        return [
            'Maternal and Child Health',
            'Women\'s Livelihood Program',
            'Gender-Based Violence Prevention',
            'Solo Parent Support',
            'Women\'s Leadership Training',
            'LGBTQ+ Support Services',
            'Girl Child Protection',
            'Women\'s Health and Wellness',
            'Gender Awareness Seminars',
            'Other Gender Programs'
        ];
    }
    
    /**
     * Check if solo parent ID needs renewal
     * 
     * @return boolean
     */
    public function needsSoloParentIdRenewal(): bool
    {
        if (!$this->is_solo_parent || !$this->solo_parent_id_expiry) {
            return false;
        }
        
        // Consider renewal needed if expiring in the next 30 days
        return $this->solo_parent_id_expiry->isPast() || 
               $this->solo_parent_id_expiry->diffInDays(now()) <= 30;
    }
    
    /**
     * Get VAW case status label
     * 
     * @return string|null
     */
    public function getVawCaseStatusLabelAttribute(): ?string
    {
        if (!$this->is_vaw_case) {
            return null;
        }
        
        $statuses = [
            'Pending' => 'Pending Investigation',
            'Ongoing' => 'Ongoing Case',
            'Resolved' => 'Case Resolved',
            'Closed' => 'Case Closed'
        ];
        
        return $statuses[$this->vaw_case_status] ?? $this->vaw_case_status;
    }
    
    /**
     * Get days remaining until due date
     * 
     * @return int|null
     */
    public function getDaysUntilDueAttribute(): ?int
    {
        if (!$this->is_pregnant || !$this->due_date) {
            return null;
        }
        
        if ($this->due_date->isPast()) {
            return 0;
        }
        
        return now()->diffInDays($this->due_date, false);
    }
}
