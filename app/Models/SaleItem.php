<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ProductFrequency;
class SaleItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sale_id',
        'cart_id',
        'product_id',
        'prescribed_quantity',   // What doctor prescribed
        'dispensed_quantity',    // What was actually given
        'selling_price',
        'subtotal',
        'notes',
        'frequency',
        'eye',
    ];

    protected $casts = [
        'cart_id' => 'integer',
        'prescribed_quantity' => 'integer',
        'dispensed_quantity' => 'integer',
        'selling_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'frequency' => ProductFrequency::class,
    ];

    /**
     * Get the product associated with this item
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the sale that owns the item
     */
    public function sale()
    {
        return $this->belongsTo(Sales::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Calculate subtotal
     */
    public function calculateSubtotal()
    {
        $this->subtotal = $this->dispensed_quantity * $this->selling_price;
        return $this->subtotal;
    }

    /**
     * Check if fully dispensed
     */
    public function isFullyDispensed()
    {
        return $this->dispensed_quantity >= $this->prescribed_quantity;
    }

    /**
     * Check if partially dispensed
     */
    public function isPartiallyDispensed()
    {
        return $this->dispensed_quantity > 0 && $this->dispensed_quantity < $this->prescribed_quantity;
    }

    /**
     * Get dispensing percentage
     */
    public function getDispensingPercentageAttribute()
    {
        if ($this->prescribed_quantity == 0) {
            return 100;
        }
        
        return round(($this->dispensed_quantity / $this->prescribed_quantity) * 100, 2);
    }

    /**
     * Get undispensed quantity
     */
    public function getUndispensedQuantityAttribute()
    {
        return max(0, $this->prescribed_quantity - $this->dispensed_quantity);
    }

    /**
     * Boot method to auto-calculate subtotal
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($saleItem) {
            if ($saleItem->isDirty(['dispensed_quantity', 'selling_price'])) {
                $saleItem->subtotal = $saleItem->dispensed_quantity * $saleItem->selling_price;
            }
        });
    }
}
