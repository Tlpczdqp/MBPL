<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'business_application_id',
        'user_id',
        'paymongo_checkout_id',   // ✅
        'paymongo_payment_id',    // ✅
        'checkout_url',           // ✅
        'amount',
        'currency',               // ✅
        'payment_method',         // ✅
        'reference_number',       // ✅
        'paid_at',                // ✅
        'status',                 // ✅
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount'  => 'decimal:2',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(BusinessApplication::class, 'business_application_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isPaid(): bool    { return $this->status === 'paid'; }
    public function isFailed(): bool  { return $this->status === 'failed'; }
    public function isPending(): bool { return $this->status === 'pending'; }
}