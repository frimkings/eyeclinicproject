<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'customer_number',
        'name',
        'phone',
        'email',
        'address',
        'loyalty_stamps',
        'notes',
    ];

    protected $casts = [
        'loyalty_stamps' => 'integer',
    ];

    public static function nextNumber(): string
    {
        $prefix = 'CUS-' . now()->format('Y') . '-';
        $last = static::where('customer_number', 'like', $prefix . '%')
            ->orderByDesc('customer_number')
            ->value('customer_number');

        $next = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
