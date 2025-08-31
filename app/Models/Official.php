<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Official extends Model
{
    use HasFactory;

    // Disable timestamps since our table doesn't have created_at/updated_at columns
    public $timestamps = false;

    protected $fillable = [
        'position',
        'name',
        'committee',
        'profile_pic',
    ];

    protected $casts = [
        // No casts needed for now
    ];

    // Position constants
    const POSITION_CAPTAIN = 'Captain';
    const POSITION_COUNCILOR = 'Councilor';
    const POSITION_SK_CHAIRMAN = 'SK Chairman';
    const POSITION_SECRETARY = 'Secretary';
    const POSITION_TREASURER = 'Treasurer';

    // Get available positions
    public static function getPositions()
    {
        return [
            self::POSITION_CAPTAIN,
            self::POSITION_COUNCILOR,
            self::POSITION_SK_CHAIRMAN,
            self::POSITION_SECRETARY,
            self::POSITION_TREASURER,
        ];
    }

    // Get officials ordered by position hierarchy
    public static function getOrderedOfficials()
    {
        $positionOrder = [
            'Captain' => 1,
            'Councilor' => 2,
            'SK Chairman' => 3,
            'Secretary' => 4,
            'Treasurer' => 5,
        ];

        return self::all()->sortBy(function ($official) use ($positionOrder) {
            return $positionOrder[$official->position] ?? 999;
        });
    }

    // Get officials by position
    public static function getByPosition($position)
    {
        return self::where('position', $position)->get();
    }

    // Get profile picture URL
    public function getProfilePicUrlAttribute()
    {
        if ($this->profile_pic && Storage::disk('public')->exists('officials/' . $this->profile_pic)) {
            return asset('storage/officials/' . $this->profile_pic);
        }
        return null;
    }

    // Get initials for display when no photo
    public function getInitialsAttribute()
    {
        $parts = explode(' ', $this->name);
        $initials = '';
        foreach ($parts as $part) {
            if (preg_match('/^[A-Z]/', $part)) {
                $initials .= substr($part, 0, 1);
            }
        }
        return substr($initials, 0, 3); // Max 3 initials
    }
}
