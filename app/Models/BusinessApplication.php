<?php
namespace App\Models;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class BusinessApplication extends Model implements Auditable
{
    use AuditableTrait; 
    protected $fillable = [
        'user_id',
        'processed_by',
        'transact_type',
        'billing_freq',
        'business_info',
        'business_name',
        'trade_name',
        'reg_num',
        'business_tin',
        'telephone_num',
        'phone_number',
        'business_email',
        'house_num',
        'building_name',
        'lot_num',
        'block_num',
        'street',
        'barangay',
        'subdivision',
        'city_muni',
        'province',
        'zip_code',
        'sp_owner_lname',
        'sp_owner_fname',
        'sp_owner_mname',
        'corp_owner_lname',
        'corp_owner_fname',
        'corp_owner_mname',
        'corp_location',
        'business_act',
        'business_act_other',
        'status',
        'rejection_reason',
        'application_number',
        'permit_fee',
        'permit_issued_at',
        'permit_valid_until',
    ];

    protected function casts(): array
    {
        return [
            'permit_issued_at'   => 'datetime',
            'permit_valid_until' => 'date',
            'permit_fee'         => 'float',
        ];
    }

    //Audit
    protected $auditEvents = [
        'created',
        'updated',
        'deleted',
        'restored',
    ];

    // ── Only track these important columns ─────────────────
    protected $auditInclude = [
        'status',
        'business_name',
        'transact_type',
        'application_number',
    ];

    // ── Keep last 50 audits per application ────────────────
    protected $auditThreshold = 50;

    // ── Custom audit messages ──────────────────────────────
    public function transformAudit(array $data): array
    {
        $statusLabels = [
            'pending'       => 'Pending',
            'under_review'  => 'Under Review',
            'approved'      => 'Approved',
            'rejected'      => 'Rejected',
            'paid'          => 'Payment Submitted',
            'permit_issued' => 'Permit Issued',
        ];

        // Make status human readable in audit log
        if (isset($data['old_values']['status'])) {
            $data['old_values']['status'] =
                $statusLabels[$data['old_values']['status']]
                ?? $data['old_values']['status'];
        }
        if (isset($data['new_values']['status'])) {
            $data['new_values']['status'] =
                $statusLabels[$data['new_values']['status']]
                ?? $data['new_values']['status'];
        }

        return $data;
    }

    // ── RELATIONSHIPS ───────────────────────────────────────────

    // The user who owns this application
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // The employee who processed it
    public function processedBy()
    {
        return $this->belongsTo(Employee::class, 'processed_by');
    }

    // All uploaded documents for this application
    // Why hasMany? One application has MULTIPLE documents (DTI, ID, Photo, Sketch)
    public function documents()
    {
        return $this->hasMany(BusinessDocument::class);
    }

    // The payment for this application
    // Why hasOne? One application has only ONE payment record
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'business_application_id');
    }

    // ── HELPERS ─────────────────────────────────────────────────

    // Can the user print their permit?
    public function canPrint(): bool
    {
        return $this->status === 'permit_issued';
    }

    public static function generateApplicationNumber(): string
    {
        $year    = date('Y');
        $lastApp = self::whereYear('created_at', $year)->latest('id')->first();
        $nextNum = $lastApp
            ? (intval(substr($lastApp->application_number, -5)) + 1)
            : 1;

        return 'MBPL-' . $year . '-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);
    }

    // Get a human-readable status label
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending'       => 'Pending',
            'under_review'  => 'Under Review',
            'approved'      => 'Approved',
            'rejected'      => 'Rejected',
            'paid'          => 'Payment Submitted',
            'permit_issued' => 'Permit Issued',
        ];

        return $labels[$this->status] ?? ucfirst($this->status);
    }

    // Get a Tailwind CSS class for the status badge color
    public function getStatusColorAttribute(): string
    {
        $colors = [
            'pending'       => 'bg-yellow-100 text-yellow-700',
            'under_review'  => 'bg-blue-100 text-blue-700',
            'approved'      => 'bg-green-100 text-green-700',
            'rejected'      => 'bg-red-100 text-red-700',
            'paid'          => 'bg-purple-100 text-purple-700',
            'permit_issued' => 'bg-emerald-100 text-emerald-700',
        ];

        return $colors[$this->status] ?? 'bg-slate-100 text-slate-600';
    }
    public function getPermitValidityDate($fromDate = null)
{
    $fromDate = $fromDate ? \Carbon\Carbon::parse($fromDate) : now();

    return match (strtolower(trim($this->billing_freq))) {
        'monthly'       => $fromDate->copy()->addMonth(),
        'quarterly'     => $fromDate->copy()->addMonths(3),
        'bi-annually'   => $fromDate->copy()->addMonths(6),
        'bi annually'   => $fromDate->copy()->addMonths(6),
        'semi-annual'   => $fromDate->copy()->addMonths(6),
        'semi annually' => $fromDate->copy()->addMonths(6),
        'annually'      => $fromDate->copy()->addYear(),
        'annual'        => $fromDate->copy()->addYear(),
        default         => $fromDate->copy()->addYear(),
    };
}
}