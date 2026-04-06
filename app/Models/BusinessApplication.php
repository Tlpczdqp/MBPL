<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessApplication extends Model
{
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
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // ── HELPERS ─────────────────────────────────────────────────

    // Can the user print their permit?
    public function canPrint(): bool
    {
        return $this->status === 'permit_issued';
    }

    // Generate a unique application number like BPA-2024-00001
    public static function generateApplicationNumber(): string
    {
        $year    = date('Y');
        $lastApp = self::whereYear('created_at', $year)->latest('id')->first();
        $nextNum = $lastApp
            ? (intval(substr($lastApp->application_number, -5)) + 1)
            : 1;

        return 'BPA-' . $year . '-' . str_pad($nextNum, 5, '0', STR_PAD_LEFT);
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
}