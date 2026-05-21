<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LensOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'frame_model_number',
        'frame_product_id',
        'lens_product_id',
        'frame_price',
        'lens_price',
        'notes',
        'pickUpDate',
        'refraction_id',
        'user_id',
        'order_id',
        'status',
        'paid_amount',
        'collected_at',
        'renewal_date',
        'renewal_reminder_sent_at',
        'renewal_approval_status',
        'renewal_approved_by',
        'renewal_actioned_at',
    ];

    protected $casts = [
        'collected_at'             => 'datetime',
        'renewal_date'             => 'date',
        'renewal_reminder_sent_at' => 'datetime',
        'renewal_actioned_at'      => 'datetime',
    ];

    public function renewalApprovedBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'renewal_approved_by');
    }

    public function refraction()
    {
        return $this->belongsTo(Refractions::class, 'refraction_id');
    }

    public function frameProduct()
    {
        return $this->belongsTo(Product::class, 'frame_product_id');
    }

    public function lensProduct()
    {
        return $this->belongsTo(Product::class, 'lens_product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
