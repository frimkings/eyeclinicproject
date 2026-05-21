<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditTrail extends Model
{
    protected $fillable = [
        'user_id',
        'patient_id',
        'auditable_type',
        'auditable_id',
        'event',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public static function record(string $event, string $description, $auditable = null, array $oldValues = [], array $newValues = [], ?int $patientId = null): self
    {
        return self::create([
            'user_id' => Auth::id(),
            'patient_id' => $patientId,
            'auditable_type' => $auditable ? get_class($auditable) : null,
            'auditable_id' => $auditable->id ?? null,
            'event' => $event,
            'description' => $description,
            'old_values' => $oldValues ?: null,
            'new_values' => $newValues ?: null,
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function auditable()
    {
        return $this->morphTo();
    }
}
