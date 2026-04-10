<?php

// The namespace MUST match the folder path exactly
// File is at: app/Models/Employee.php
// So namespace is: App\Models
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Employee extends Authenticatable implements AuditableContract
{
    use SoftDeletes;
    use AuditableTrait; 
    protected $table = 'employees';

    // These fields can be filled via create() or update()
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'employee_id',
        'department',
        'avatar',
        'is_active',
    ];

    // Never expose these in JSON or arrays
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Cast database values to PHP types automatically
    protected $casts = [
        'password'  => 'hashed',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    // ================================================================
    // ROLE CHECK METHODS
    // These are defined directly on the Employee model
    // They ONLY work when $employee is an Employee model instance
    // They do NOT exist on User model
    // ================================================================

    /**
     * Check if this employee is an Admin.
     * Admin has full access including employee management.
     */
    public function isAdmin(): bool
    {
        return (string) $this->role === 'admin';
    }

    /**
     * Check if this employee is a Manager.
     * Manager can approve, reject, verify payments, issue permits.
     */
    public function isManager(): bool
    {
        return (string) $this->role === 'manager';
    }

    /**
     * Check if this employee is Staff.
     * Staff can view and mark applications as under review.
     */
    public function isStaff(): bool
    {
        return (string) $this->role === 'staff';
    }

    /**
     * Check if this employee is Admin or Manager.
     * Used for pages both roles can access.
     */
    public function isAdminOrManager(): bool
    {
        return in_array((string) $this->role, ['admin', 'manager'], true);
    }

    // ================================================================
    // ACCESSOR METHODS
    // These add computed properties to the model
    // Usage: $employee->role_label  (no parentheses — acts like a property)
    // ================================================================

    /**
     * Get a human-readable role label.
     * Usage: $employee->role_label
     */
    public function getRoleLabelAttribute(): string
    {
        $labels = [
            'admin'   => 'Administrator',
            'manager' => 'Manager',
            'staff'   => 'Staff',
        ];

        return $labels[$this->role] ?? ucfirst((string) $this->role);
    }

    /**
     * Get Tailwind background color class for the role.
     * Usage: $employee->role_color
     */
    public function getRoleColorAttribute(): string
    {
        $colors = [
            'admin'   => 'bg-red-600',
            'manager' => 'bg-yellow-500',
            'staff'   => 'bg-green-600',
        ];

        return $colors[$this->role] ?? 'bg-slate-600';
    }

    /**
     * Get Tailwind badge color class for the role.
     * Usage: $employee->role_badge
     */
    public function getRoleBadgeAttribute(): string
    {
        $badges = [
            'admin'   => 'bg-red-100 text-red-700',
            'manager' => 'bg-yellow-100 text-yellow-700',
            'staff'   => 'bg-green-100 text-green-700',
        ];

        return $badges[$this->role] ?? 'bg-slate-100 text-slate-700';
    }

    
    /**
     * Applications processed by this employee.
     */
    public function processedApplications(): HasMany
    {
        return $this->hasMany(BusinessApplication::class, 'processed_by');
    }

    /**
     * Custom notifications relationship.
     * Named appNotifications to avoid conflict with Notifiable trait.
     */
    public function appNotifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

   
    /**
     * Count unread notifications for the navbar bell badge.
     */
    public function unreadNotificationsCount(): int
    {
        return $this->appNotifications()
            ->where('is_read', false)
            ->count();
    }

     // ── Audit Settings ──────────────────────────────────────
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    protected $auditInclude = [
        'name',
        'email',
        'role',
        'position',
        'is_active',
    ];

    protected $auditExclude = [
        'password',
        'remember_token',
    ];

    protected $auditThreshold = 30;

    // ── Make role human readable ────────────────────────────
    public function transformAudit(array $data): array
    {
        $roleLabels = [
            'admin'   => 'Administrator',
            'manager' => 'Manager',
            'staff'   => 'Staff',
        ];

        if (isset($data['old_values']['role'])) {
            $data['old_values']['role'] =
                $roleLabels[$data['old_values']['role']]
                ?? $data['old_values']['role'];
        }
        if (isset($data['new_values']['role'])) {
            $data['new_values']['role'] =
                $roleLabels[$data['new_values']['role']]
                ?? $data['new_values']['role'];
        }

        // Make is_active human readable
        if (isset($data['old_values']['is_active'])) {
            $data['old_values']['is_active'] =
                $data['old_values']['is_active'] ? 'Active' : 'Inactive';
        }
        if (isset($data['new_values']['is_active'])) {
            $data['new_values']['is_active'] =
                $data['new_values']['is_active'] ? 'Active' : 'Inactive';
        }

        return $data;
    }
}