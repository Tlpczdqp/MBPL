<?php
// Also needed — Payment model was referenced in the controller but never created

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'business_application_id',
        'user_id',
        'amount',
        'payment_method',
        'reference_number',
        'proof_image',
        'status',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'   => 'decimal:2',
            'paid_at'  => 'datetime',
        ];
    }

    // RELATIONSHIP: This payment belongs to ONE application
    public function businessApplication()
    {
        return $this->belongsTo(BusinessApplication::class);
    }

    // RELATIONSHIP: This payment belongs to ONE user (who paid)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper: get a clean label for the payment method
    // 'bank_transfer' → 'Bank Transfer'
    public function getPaymentMethodLabelAttribute(): string
    {
        $labels = [
            'gcash'         => 'GCash',
            'paymaya'       => 'PayMaya',
            'bank_transfer' => 'Bank Transfer',
        ];

        return $labels[$this->payment_method]
            ?? ucwords(str_replace('_', ' ', $this->payment_method));
    }

    // Helper: is this payment verified?
    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }
}