<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUSES = [
        'Pending',
        'Received',
        'Washing',
        'Drying',
        'Ironing',
        'Ready',
        'Out for Delivery',
        'Delivered',
        'Cancelled',
    ];

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_id',
        'patient_id',
        'plan_id',
        'service_type',
        'zone',
        'zone_fee',
        'pieces',
        'add_ons',
        'add_ons_total',
        'subtotal',
        'total',
        'total_amount',
        'paid_amount',
        'payment_status',
        'payment_method',
        'status',
        'cancel_reason',
        'cancelled_at',
        'cancelled_by',
        'notes',
        'clothing_photo_path',
        'loyalty_stamps_awarded',
    ];

    protected $casts = [
        'add_ons' => 'array',
        'zone_fee' => 'decimal:2',
        'add_ons_total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'pieces' => 'integer',
        'loyalty_stamps_awarded' => 'integer',
        'cancelled_at' => 'datetime',
    ];

    public static function nextOrderNumber(): string
    {
        $prefix = 'JW-' . now()->format('Ymd') . '-';
        $last = static::where('order_number', 'like', $prefix . '%')
            ->lockForUpdate()
            ->orderByDesc('order_number')
            ->value('order_number');

        $next = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(OrderPayment::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(OrderActivityLog::class);
    }

    public function pickupDeliveryTask()
    {
        return $this->hasOne(PickupDeliveryTask::class);
    }
}
