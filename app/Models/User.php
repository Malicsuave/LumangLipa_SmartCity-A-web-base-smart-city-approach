<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'profile_photo_path',
        'google_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            return Storage::disk('public')->url($this->profile_photo_path);
        }

        // Use a colorful avatar with initials if no photo is uploaded
        $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        $bgColor = '3498db'; // Blue color without #
        $textColor = 'ffffff'; // White text without #
        
        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color='.$textColor.'&background='.$bgColor.'&size=256&rounded=true';
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        // Use a colorful avatar with initials if no photo is uploaded
        $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        $bgColor = '#3498db'; // A nice blue color
        $textColor = '#ffffff'; // White text
        
        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color='.urlencode($textColor).'&background='.urlencode($bgColor).'&size=256';
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    
    /**
     * Get all of the activities for the user.
     */
    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }
    
    /**
     * Get the user's last login activity.
     */
    public function lastLogin()
    {
        return $this->hasMany(UserActivity::class)
            ->where('activity_type', 'login')
            ->latest()
            ->first();
    }
    
    /**
     * Determine if the user's account is locked.
     */
    public function isLocked()
    {
        return $this->locked_until && now()->lt($this->locked_until);
    }
}
