<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointments extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'patient_id',
        'user_id',
        'title',
        'recall_category',
        'scheduled_at',
        'notes',
        'reminder_channel',
        'reminder_status',
        'reminder_sent_at',
        'missed_at',
        'status',
    ];

    // Ensure scheduled_at is treated as a Carbon instance
    protected $casts = [
        'scheduled_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
        'missed_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
