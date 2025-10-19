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
        'is_active',
        'queue_position',
        'queue_status',
        'priority',
        'queue_joined_at',
        'conversation_started_at',
        'estimated_wait_minutes',
        'assigned_admin_id',
        'conversation_completed_at'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_active' => 'boolean',
        'queue_joined_at' => 'datetime',
        'conversation_started_at' => 'datetime',
        'conversation_completed_at' => 'datetime'
    ];

    public function admin()
    {
        return $this->belongsTo(\App\Models\User::class, 'admin_id');
    }

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

    // Queue-related scopes
    public function scopeInQueue($query)
    {
        return $query->where('queue_status', 'waiting')
                     ->orderBy('queue_position', 'asc');
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
    public static function getNextQueuePosition()
    {
        $maxPosition = self::where('queue_status', 'waiting')
                          ->max('queue_position');
        return ($maxPosition ?? 0) + 1;
    }

    public static function getQueuePosition($sessionId)
    {
        $conversation = self::where('session_id', $sessionId)
                           ->where('queue_status', 'waiting')
                           ->first();
        
        if (!$conversation) {
            return null;
        }

        return self::where('queue_status', 'waiting')
                  ->where('queue_position', '<=', $conversation->queue_position)
                  ->count();
    }

    public static function getNextInQueue()
    {
        return self::where('queue_status', 'waiting')
                  ->orderBy('queue_position', 'asc')
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
                'conversation_completed_at' => now(),
                'is_active' => false
            ]);
    }
}
