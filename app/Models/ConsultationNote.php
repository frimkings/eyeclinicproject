<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationNote extends Model
{
    protected $fillable = [
        'consultation_id',
        'patient_id',
        'user_id',
        'note_type',
        'note',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultations::class, 'consultation_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
