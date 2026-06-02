<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Insurer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'code', 'scheme_type', 'contact_person',
        'contact_phone', 'notes', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function claims(): HasMany
    {
        return $this->hasMany(InsuranceClaim::class);
    }

    public function schemeBadgeClass(): string
    {
        return match($this->scheme_type) {
            'NHIS'      => 'badge-success',
            'Private'   => 'badge-primary',
            'Corporate' => 'badge-info',
            default     => 'badge-secondary',
        };
    }
}
