<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLogArchive extends Model
{
    protected $table = 'login_logs_archive';

    public $timestamps = false;

    protected $casts = [
        'login_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
