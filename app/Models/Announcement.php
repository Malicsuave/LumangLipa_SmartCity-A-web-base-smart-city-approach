<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'type',
        'max_slots',
        'current_slots',
        'start_date',
        'end_date',
        'image',
        'is_active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'max_slots' => 'integer',
        'current_slots' => 'integer',
        'additional_info' => 'array',
    ];

    // Relationships
    public function registrations()
    {
        return $this->hasMany(AnnouncementRegistration::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrentlyActive($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('end_date')
                          ->orWhere('end_date', '>=', now());
                    });
    }

    public function scopeWithSlots($query)
    {
        return $query->where('type', 'limited_slots');
    }

    public function scopeAvailable($query)
    {
        return $query->where(function($q) {
            $q->where('type', '!=', 'limited_slots')
              ->orWhereRaw('current_slots < max_slots');
        });
    }

    // Accessors
    public function getStatusAttribute()
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        if ($this->end_date && $this->end_date < now()) {
            return 'expired';
        }

        if ($this->type === 'limited_slots') {
            if ($this->current_slots >= $this->max_slots) {
                return 'full';
            }
            return 'available';
        }

        return 'active';
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->type !== 'limited_slots' || $this->max_slots == 0) {
            return 0;
        }

        return round(($this->current_slots / $this->max_slots) * 100, 1);
    }

    public function getProgressColorAttribute()
    {
        $percentage = $this->progress_percentage;
        
        if ($percentage >= 90) return 'danger';
        if ($percentage >= 70) return 'warning';
        if ($percentage >= 50) return 'info';
        return 'success';
    }

    public function getSlotsRemainingAttribute()
    {
        if ($this->type !== 'limited_slots') {
            return null;
        }

        return max(0, $this->max_slots - $this->current_slots);
    }

    public function getIsExpiredAttribute()
    {
        return $this->end_date && $this->end_date < now();
    }

    public function getIsFullAttribute()
    {
        return $this->type === 'limited_slots' && $this->current_slots >= $this->max_slots;
    }

    public function getTypeDisplayAttribute()
    {
        $typeLabels = [
            'general' => 'General',
            'limited_slots' => 'Registration Required',
            'event' => 'Event',
            'service' => 'Service',
            'program' => 'Program'
        ];

        return $typeLabels[$this->type] ?? ucfirst(str_replace('_', ' ', $this->type));
    }

    // Methods
    public function canRegister()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->is_expired) {
            return false;
        }

        if ($this->type === 'limited_slots' && $this->is_full) {
            return false;
        }

        return true;
    }

    public function register($userData)
    {
        if (!$this->canRegister()) {
            return false;
        }

        // Check if user already registered
        $existingRegistration = $this->registrations()
            ->where('email', $userData['email'])
            ->first();

        if ($existingRegistration) {
            return false; // Already registered
        }

        // Create registration
        $registration = $this->registrations()->create($userData);

        // Update current slots count
        if ($this->type === 'limited_slots') {
            $this->increment('current_slots');
        }

        return $registration;
    }

    public function getRegistrationCount()
    {
        return $this->registrations()->count();
    }

    public function updateSlotsCount()
    {
        if ($this->type === 'limited_slots') {
            $this->update([
                'current_slots' => $this->registrations()->count()
            ]);
        }
    }
}
