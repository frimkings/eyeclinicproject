<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use SoftDeletes;


protected $fillable = [
    'user_id',
    'patient_id',
    'consultation_id',
    'transaction_id',
    'total_amount',
    'amount_paid',
    'payment_status',
    'profit',
    'discount_type',
    'discount_value',
    'discount_amount',
    'discount_approved_by',
    'is_refunded',
    'refunded_at',
    'refunded_by',
    'refund_reason',
];

protected $casts = [
    'is_refunded'     => 'boolean',
    'refunded_at'     => 'datetime',
    'total_amount'    => 'decimal:2',
    'amount_paid'     => 'decimal:2',
    'discount_value'  => 'decimal:2',
    'discount_amount' => 'decimal:2',
];

public function getRemainingBalanceAttribute(): float
{
    return max(0, (float) $this->total_amount - (float) $this->amount_paid);
}

public function isFullyPaid(): bool
{
    return $this->payment_status === 'paid';
}

public function paymentTransactions()
{
    return $this->hasMany(\App\Models\PaymentTransaction::class, 'sale_id')->orderBy('created_at');
}


    public function items()
    {
        return $this->hasMany(SaleItem::class,  'sale_id');
    }

   

public function patient()
{
    return $this->belongsTo(Patient::class);
}

public function consultation()
{
    return $this->belongsTo(Consultations::class, 'consultation_id');
}

public function user()
{
    return $this->belongsTo(User::class);
}


public function approvedBy()
{
    return $this->belongsTo(User::class, 'discount_approved_by');
}

public function refundedBy()
{
    return $this->belongsTo(User::class, 'refunded_by');
}

public function refundLogs()
{
    return $this->hasMany(\App\Models\RefundLog::class, 'sale_id')->orderBy('created_at', 'desc');
}

public function pendingRefundLog(): HasOne
{
    return $this->hasOne(RefundLog::class, 'sale_id')
                ->whereIn('status', [RefundLog::STATUS_PENDING, RefundLog::STATUS_APPROVED])
                ->latest();
}






}
