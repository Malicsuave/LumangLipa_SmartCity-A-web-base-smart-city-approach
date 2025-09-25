<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'message',
        'sender_type',
        'admin_id',
        'user_session',
        'is_read',
        'is_active'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function admin()
    {
        return $this->belongsTo(\App\Models\User::class, 'admin_id');
    }

    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    public function scopeByUserSession($query, $userSession)
    {
        return $query->where('user_session', $userSession);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForAdmin($query, $adminId)
    {
        return $query->where('admin_id', $adminId);
    }

    public function scopeRecentConversations($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }
}
