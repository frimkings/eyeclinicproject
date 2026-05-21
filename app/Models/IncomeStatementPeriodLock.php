<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeStatementPeriodLock extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_date',
        'to_date',
        'locked_by',
        'locked_at',
        'notes',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date',
        'locked_at' => 'datetime',
    ];

    public function lockedBy()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }
}
