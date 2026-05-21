<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashierPatientClearance extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'user_id',
        'patient_id',
        'service_id',
        'payment_status',
        'doctor_status',
        'clearance_date',
    ];




    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function service()
    {
        return $this->belongsTo(\App\Models\Product::class, 'service_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function consultation()
    {
        return $this->hasOne(\App\Models\Consultations::class, 'clearance_id');
    }

    public function revokeLog()
    {
        return $this->hasOne(ClearanceRevokeLog::class, 'clearance_id')->latest();
    }

    public function pendingRevokeLog()
    {
        return $this->hasOne(ClearanceRevokeLog::class, 'clearance_id')
            ->where('status', ClearanceRevokeLog::STATUS_PENDING);
    }
}
