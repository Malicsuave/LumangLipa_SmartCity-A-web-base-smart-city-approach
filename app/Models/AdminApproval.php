<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminApproval extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'role_id',
        'is_active',
        'approved_by',
        'approved_at',
        'notes',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'approved_at' => 'datetime',
    ];
    
    /**
     * Get the role associated with this admin approval.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    
    /**
     * Get the user associated with this email (if exists).
     */
    public function user()
    {
        return $this->hasOne(User::class, 'email', 'email');
    }
    
    /**
     * Check if an email is approved for admin access.
     *
     * @param string $email
     * @return AdminApproval|null
     */
    public static function findApprovedEmail(string $email)
    {
        return self::where('email', $email)
            ->where('is_active', true)
            ->first();
    }
    
    /**
     * Approve a new admin with the specified role.
     *
     * @param string $email Gmail address to approve
     * @param int|string $roleId Role ID or name to assign
     * @param string $approvedBy Email of the admin who approved this
     * @param string|null $notes Optional notes about this approval
     * @return AdminApproval
     */
    public static function approveAdmin(string $email, $roleId, string $approvedBy, ?string $notes = null)
    {
        // If role name was provided instead of ID, look up the ID
        if (is_string($roleId) && !is_numeric($roleId)) {
            $role = Role::where('name', $roleId)->first();
            if ($role) {
                $roleId = $role->id;
            } else {
                throw new \InvalidArgumentException("Role '{$roleId}' not found");
            }
        }
        
        return self::updateOrCreate(
            ['email' => $email],
            [
                'role_id' => $roleId,
                'is_active' => true,
                'approved_by' => $approvedBy,
                'approved_at' => now(),
                'notes' => $notes,
            ]
        );
    }
    
    /**
     * Deactivate an admin approval.
     *
     * @param string $deactivatedBy Email of the admin performing the deactivation
     * @param string|null $notes Optional notes about the deactivation
     * @return bool
     */
    public function deactivate(string $deactivatedBy, ?string $notes = null)
    {
        $this->is_active = false;
        $this->approved_by = $deactivatedBy; // Track who last modified
        if ($notes) {
            $this->notes = $notes;
        }
        return $this->save();
    }
    
    /**
     * Scope a query to only include active approvals.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    /**
     * Scope a query to only include specific role.
     */
    public function scopeWithRole($query, $roleName)
    {
        return $query->whereHas('role', function($q) use ($roleName) {
            $q->where('name', $roleName);
        });
    }
}
