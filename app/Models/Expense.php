<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'expense_category_id',
        'expense_date',
        'description',
        'amount',
        'reference',
        'notes',
        'receipt_path',
        'recorded_by',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount'       => 'decimal:2',
    ];

    public function getReceiptUrlAttribute(): ?string
    {
        return $this->receipt_path
            ? route('admin.expenses.receipt', ['filename' => basename($this->receipt_path)])
            : null;
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
