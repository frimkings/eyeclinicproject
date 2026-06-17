<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PickupDeliveryTask extends Model
{
    public const STATUSES = [
        'Pickup Requested',
        'Picked Up',
        'At Laundry',
        'Ready for Delivery',
        'Out for Delivery',
        'Delivered',
    ];

    protected $fillable = [
        'order_id',
        'zone_code',
        'zone_name',
        'pickup_scheduled_at',
        'delivery_scheduled_at',
        'rider_id',
        'assigned_by',
        'status',
        'pickup_completed_at',
        'delivery_completed_at',
        'notes',
    ];

    protected $casts = [
        'pickup_scheduled_at' => 'datetime',
        'delivery_scheduled_at' => 'datetime',
        'pickup_completed_at' => 'datetime',
        'delivery_completed_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function rider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rider_id');
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
