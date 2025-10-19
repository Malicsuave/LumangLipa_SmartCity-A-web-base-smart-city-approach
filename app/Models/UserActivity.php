<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'activity_type',
        'ip_address',
        'user_agent',
        'device_type',
        'location',
        'is_suspicious',
        'details',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_suspicious' => 'boolean',
        'details' => 'array',
    ];

    /**
     * Get the user that owns the activity.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log a new user activity.
     *
     * @param  \App\Models\User  $user
     * @param  string  $activityType
     * @param  array  $additionalDetails
     * @return \App\Models\UserActivity
     */
    public static function log($user, $activityType, $additionalDetails = [])
    {
        $request = request();
        $userAgent = $request->userAgent();
        
        // Simple device type detection
        $deviceType = 'unknown';
        if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $userAgent)) {
            $deviceType = 'mobile';
        } elseif (preg_match('/android|ipad|playbook|silk/i', $userAgent)) {
            $deviceType = 'tablet';
        } else {
            $deviceType = 'desktop';
        }
        
        // Check if login is from a new location or device (suspicious)
        $isSuspicious = false;
        if ($activityType === 'login') {
            $lastLogin = self::where('user_id', $user->id)
                ->where('activity_type', 'login')
                ->where('ip_address', '!=', $request->ip())
                ->orderBy('created_at', 'desc')
                ->first();
            
            if ($lastLogin && $lastLogin->ip_address !== $request->ip()) {
                $isSuspicious = true;
            }
        }
        
        return self::create([
            'user_id' => $user->id,
            'activity_type' => $activityType,
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent,
            'device_type' => $deviceType,
            'is_suspicious' => $isSuspicious,
            'details' => $additionalDetails,
        ]);
    }
}
