<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefundLog extends Model
{
    use SoftDeletes;

    const STATUS_PENDING   = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_PROCESSED = 'processed';

    protected $fillable = [
        'sale_id',
        'status',
        'initiated_by',
        'approved_by',
        'processed_by',
        'rejected_by',
        'reason',
        'rejection_reason',
        'initiated_at',
        'approved_at',
        'processed_at',
        'rejected_at',
    ];

    protected $casts = [
        'initiated_at' => 'datetime',
        'approved_at'  => 'datetime',
        'processed_at' => 'datetime',
        'rejected_at'  => 'datetime',
    ];

    // ── Scopes ────────────────────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeProcessed($query)
    {
        return $query->where('status', self::STATUS_PROCESSED);
    }

    // ── Computed attributes ────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PENDING   => 'Pending',
            self::STATUS_APPROVED  => 'Approved',
            self::STATUS_REJECTED  => 'Rejected',
            self::STATUS_PROCESSED => 'Processed',
            default                => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_PROCESSED => 'success',
            self::STATUS_APPROVED  => 'primary',
            self::STATUS_REJECTED  => 'danger',
            default                => 'warning',
        };
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public static function pendingCount(): int
    {
        return static::where('status', self::STATUS_PENDING)->count();
    }

    // ── Relationships ──────────────────────────────────────────────────────────

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sales::class, 'sale_id');
    }

    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}
