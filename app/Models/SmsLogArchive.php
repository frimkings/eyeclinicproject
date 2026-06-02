<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLogArchive extends Model
{
    protected $table = 'sms_logs_archive';

    public $timestamps = false;

    protected $casts = [
        'success'    => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
