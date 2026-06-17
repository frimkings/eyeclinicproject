<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderPayment extends Model
{
    protected $fillable = [
        'order_id',
        'receipt_number',
        'type',
        'refunded_payment_id',
        'amount',
        'payment_method',
        'reference',
        'refund_reason',
        'paid_at',
        'received_by',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public static function nextReceiptNumber(string $type = 'payment'): string
    {
        $prefix = ($type === 'refund' ? 'JW-RF-' : 'JW-RC-') . now()->format('Ymd') . '-';
        $last = static::where('receipt_number', 'like', $prefix . '%')
            ->lockForUpdate()
            ->orderByDesc('receipt_number')
            ->value('receipt_number');

        $next = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function refundedPayment(): BelongsTo
    {
        return $this->belongsTo(self::class, 'refunded_payment_id');
    }

    public function refunds()
    {
        return $this->hasMany(self::class, 'refunded_payment_id');
    }

    public function getRefundedAmountAttribute(): float
    {
        return (float) $this->refunds()->where('type', 'refund')->sum('amount');
    }
}
