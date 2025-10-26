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
        'user_session',
        'is_read',
        'queue_status',
        'conversation_started_at',
        'assigned_admin_id',
        'conversation_completed_at',
        'user_status',
        'last_seen',
        'user_connected_at',
        'user_disconnected_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'conversation_started_at' => 'datetime',
        'conversation_completed_at' => 'datetime',
        'last_seen' => 'datetime',
        'user_connected_at' => 'datetime',
        'user_disconnected_at' => 'datetime'
    ];

    public function assignedAdmin()
    {
        return $this->belongsTo(\App\Models\User::class, 'assigned_admin_id');
    }

    // Scopes
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
        return $query->where('queue_status', 'active');
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForAdmin($query, $adminId)
    {
        return $query->where('assigned_admin_id', $adminId);
    }

    public function scopeRecentConversations($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    // Queue-related scopes
    public function scopeInQueue($query)
    {
        return $query->where('queue_status', 'waiting')
                     ->orderBy('created_at', 'asc');
    }

    public function scopeActiveConversation($query)
    {
        return $query->where('queue_status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('queue_status', 'completed');
    }

    public function scopeAssignedTo($query, $adminId)
    {
        return $query->where('assigned_admin_id', $adminId);
    }

    // Queue management methods
    public static function getQueuePosition($sessionId)
    {
        $conversation = self::where('session_id', $sessionId)
                           ->where('queue_status', 'waiting')
                           ->first();
        
        if (!$conversation) {
            return null;
        }

        return self::where('queue_status', 'waiting')
                  ->where('created_at', '<=', $conversation->created_at)
                  ->count();
    }

    public static function getNextInQueue()
    {
        return self::where('queue_status', 'waiting')
                  ->orderBy('created_at', 'asc')
                  ->first();
    }

    public function activateConversation($adminId)
    {
        // Update all messages in this session
        self::where('session_id', $this->session_id)
            ->update([
                'queue_status' => 'active',
                'assigned_admin_id' => $adminId,
                'conversation_started_at' => now()
            ]);
    }

    public function completeConversation()
    {
        // Update all messages in this session
        self::where('session_id', $this->session_id)
            ->update([
                'queue_status' => 'completed',
                'conversation_completed_at' => now()
            ]);
    }

    // User status management methods
    public static function updateUserHeartbeat($sessionId, $userSession)
    {
        // Update last_seen for the user session
        self::where('session_id', $sessionId)
            ->orWhere('user_session', $userSession)
            ->update([
                'last_seen' => now(),
                'user_status' => 'online'
            ]);
    }

    public static function markUserConnected($sessionId, $userSession)
    {
        // Mark user as connected when they start a conversation or join queue
        self::where('session_id', $sessionId)
            ->orWhere('user_session', $userSession)
            ->update([
                'user_status' => 'online',
                'user_connected_at' => now(),
                'last_seen' => now()
            ]);
    }

    public static function markUserDisconnected($sessionId, $userSession)
    {
        // Mark user as disconnected
        self::where('session_id', $sessionId)
            ->orWhere('user_session', $userSession)
            ->update([
                'user_status' => 'disconnected',
                'user_disconnected_at' => now()
            ]);
    }

    public static function checkOfflineUsers($minutesThreshold = 2)
    {
        // Mark users as offline if they haven't been seen for the threshold time
        self::where('user_status', 'online')
            ->where('last_seen', '<', now()->subMinutes($minutesThreshold))
            ->update([
                'user_status' => 'offline',
                'user_disconnected_at' => now()
            ]);
    }

    public function isUserOnline()
    {
        return $this->user_status === 'online' && 
               $this->last_seen && 
               $this->last_seen->greaterThan(now()->subMinutes(2));
    }

    public function getUserStatusText()
    {
        if ($this->user_status === 'disconnected') {
            return 'Disconnected';
        }
        
        if ($this->user_status === 'offline' || !$this->isUserOnline()) {
            return 'Offline';
        }
        
        return 'Online';
    }
}
