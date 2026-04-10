<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class User extends Authenticatable implements Auditable
{
    // DO NOT use Notifiable trait — conflicts with our appNotifications()
    // use Illuminate\Notifications\Notifiable;

    use SoftDeletes;
    use AuditableTrait;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'google_id',
        'facebook_id',
        'avatar',
        'otp',
        'otp_expires_at',
        'email_verified',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    protected function casts(): array
    {
        return [
            'password'          => 'hashed',
            'email_verified'    => 'boolean',
            'otp_expires_at'    => 'datetime',
            'email_verified_at' => 'datetime',
            'deleted_at'        => 'datetime',
        ];
    }

    // ── RELATIONSHIPS ─────────────────────────────────────────────

    public function businessApplications()
    {
        return $this->hasMany(BusinessApplication::class);
    }
    

    public function appNotifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    // ── HELPERS ───────────────────────────────────────────────────

    public function unreadNotificationsCount(): int
    {
        return $this->appNotifications()
            ->where('is_read', false)
            ->count();
    }

    public function isOtpValid(string $otp): bool
    {
        return $this->otp === $otp
            && $this->otp_expires_at !== null
            && now()->lessThanOrEqualTo($this->otp_expires_at);
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirm_password' => ['required', 'string'],
        ]);

        $user = $this->getAuthUser();

        // Verify password before soft deleting
        if (!Hash::check($request->confirm_password, $user->password)) {
            return back()
                ->withErrors(['confirm_password' => 'The password you entered is incorrect.'])
                ->with('tab', 'delete');
        }

        // Log the user out first
        Auth::logout();

        // Soft delete — sets deleted_at, does NOT remove from DB
        $user->delete();

        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Your account has been deactivated. Contact support to reactivate.');
    }

    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    // ── Only track these columns ────────────────────────────
    protected $auditInclude = [
        'name',
        'email',
        'is_active',
        'email_verified_at',
    ];

    // ── Exclude sensitive fields ────────────────────────────
    protected $auditExclude = [
        'password',
        'remember_token',
        'facebook_id',
        'google_id',
    ];

    // ── Keep last 30 audits per user ────────────────────────
    protected $auditThreshold = 30;

    // ── Make values human readable ──────────────────────────
    public function transformAudit(array $data): array
    {
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