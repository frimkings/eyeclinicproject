<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'user_id',
        'pxnumber',
        'name',
        'contact',
        'gender',
        'dob',
        'address',
        'occupation',
        'email',
        'civil_status',
        'recall_sms_sent_at',
        'insurer_id',
        'insurance_member_id',
        'insurance_policy_number',
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->uuid ??= (string) Str::uuid();
        });
    }

    protected $casts = [
        'recall_sms_sent_at' => 'datetime',
    ];

    // Helpful helper for the Blade file
    public function getAgeAttribute()
    {
        return \Illuminate\Support\Carbon::parse($this->dob)->age;
    }

    protected $hidden = [
        'remember_token',
        'created_at',
        'updated_at',
        'user_id',
        // 'pxnumber',
        // 'id'

    ];




public function insurer()
{
    return $this->belongsTo(Insurer::class);
}

public function clearances()
{
    return $this->hasMany(CashierPatientClearance::class);
}

public function consultations()
{

   return $this->hasMany('App\Models\Consultations');

}

public function appointments()
{
    return $this->hasMany(Appointments::class);
}

public function documents()
{
    return $this->hasMany(PatientDocument::class);
}

public function auditTrails()
{
    return $this->hasMany(AuditTrail::class);
}

}
