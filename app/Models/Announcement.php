<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory;    protected $fillable = [
        'title',
        'content',
        'type',
        'max_slots',
        'current_slots',
        'date',
        'start_time',
        'end_time',
        'image',
        'is_active'
    ];

    protected $casts = [
        'date' => 'date',
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
                        $q->whereNull('date')
                          ->orWhereDate('date', '>=', now()->toDateString());
                    });
    }

    public function scopeWithSlots($query)
    {
        return $query->where('type', 'health_related');
    }

    public function scopeAvailable($query)
    {
        return $query->where(function($q) {
            $q->where('type', '!=', 'health_related')
              ->orWhereRaw('current_slots < max_slots');
        });
    }

    // Accessors
    public function getStatusAttribute()
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        // Check if announcement has expired (date-based)
        if ($this->date) {
            $announcementDate = Carbon::parse($this->date);
            $today = Carbon::today();
            
            // If the announcement date is before today, it's expired
            if ($announcementDate->lt($today)) {
                return 'expired';
            }
        }

        if ($this->type === 'health_related') {
            if ($this->current_slots >= $this->max_slots) {
                return 'full';
            }
            return 'available';
        }

        return 'active';
    }

    public function getProgressPercentageAttribute()
    {
        if ($this->type !== 'health_related' || $this->max_slots == 0) {
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
        if ($this->type !== 'health_related') {
            return null;
        }

        return max(0, $this->max_slots - $this->current_slots);
    }

    public function getIsExpiredAttribute()
    {
        return $this->date && $this->date < now()->toDateString();
    }

    public function getIsFullAttribute()
    {
        return $this->type === 'health_related' && $this->current_slots >= $this->max_slots;
    }

    public function getTypeDisplayAttribute()
    {
        $typeLabels = [
            'general' => 'General',
            'health_related' => 'Health Related',
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

        if ($this->type === 'health_related' && $this->is_full) {
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
        if ($this->type === 'health_related') {
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
        if ($this->type === 'health_related') {
            $this->update([
                'current_slots' => $this->registrations()->count()
            ]);
        }
    }
}
