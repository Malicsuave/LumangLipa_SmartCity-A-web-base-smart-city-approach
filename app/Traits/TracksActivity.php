<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\UserActivity;

trait TracksActivity
{
    /**
     * Register the model event hooks.
     */
    public static function bootTracksActivity()
    {
        static::created(function ($model) {
            static::recordActivity('created', $model);
        });

        static::updated(function ($model) {
            // Only record if actual changes were made
            if (count($model->getDirty()) > 0) {
                static::recordActivity('updated', $model);
            }
        });

        static::deleted(function ($model) {
            static::recordActivity('deleted', $model);
        });
    }

    /**
     * Record the activity for the model event.
     */
    protected static function recordActivity(string $event, $model)
    {
        // Do nothing if no authenticated user is found
        if (!Auth::check()) {
            return;
        }
        
        $user = Auth::user();
        $modelName = class_basename($model);
        
        // Prepare details based on the event type
        $details = [
            'model_type' => $modelName,
            'model_id' => $model->id,
        ];
        
        // Add specific details for updates
        if ($event === 'updated') {
            $details['changes'] = [];
            $changes = [];
            
            foreach ($model->getDirty() as $attribute => $value) {
                // Skip certain attributes if needed
                if (in_array($attribute, $model->getHidden() ?? [])) {
                    continue;
                }
                
                $original = $model->getOriginal($attribute);
                $changes[$attribute] = [
                    'from' => $original,
                    'to' => $value,
                ];
            }
            
            $details['changes'] = $changes;
        }
        
        // For create and delete events, include a summary of the data
        if ($event === 'created' || $event === 'deleted') {
            $visibleData = [];
            foreach ($model->getAttributes() as $key => $value) {
                // Skip hidden attributes and sensitive data
                if (in_array($key, $model->getHidden() ?? []) || 
                    in_array($key, ['password', 'remember_token', 'two_factor_secret'])) {
                    continue;
                }
                
                $visibleData[$key] = $value;
            }
            
            $details['data'] = $visibleData;
        }
        
        // Record the activity
        UserActivity::create([
            'user_id' => $user->id,
            'activity_type' => "{$modelName}_{$event}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'device_type' => static::detectDeviceType(request()->userAgent()),
            'is_suspicious' => false,
            'details' => $details,
        ]);
    }
    
    /**
     * Detect the device type from user agent string
     */
    protected static function detectDeviceType(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'unknown';
        }
        
        $userAgent = strtolower($userAgent);
        
        $mobileKeywords = ['mobile', 'android', 'iphone', 'ipod', 'blackberry', 'webos'];
        $tabletKeywords = ['ipad', 'tablet', 'playbook', 'silk'];
        
        foreach ($tabletKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                return 'tablet';
            }
        }
        
        foreach ($mobileKeywords as $keyword) {
            if (stripos($userAgent, $keyword) !== false) {
                return 'mobile';
            }
        }
        
        return 'desktop';
    }
}