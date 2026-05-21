<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class LoginLog extends Model
{
    use HasFactory;

    // Since we created custom column names in the migration
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'login_at'
    ];

    // Tell Laravel that login_at should be treated as a Carbon date object
    protected $casts = [
        'login_at' => 'datetime',
    ];

    // Disable standard created_at/updated_at since we use login_at
    public $timestamps = false;

    /**
     * Get the user that owns the login log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function recordFor(User $user, ?Request $request = null): self
    {
        $request = $request ?: request();

        return static::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_at' => now(),
        ]);
    }
}
