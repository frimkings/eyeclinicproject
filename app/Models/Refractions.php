<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refractions extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultation_id',
        'user_id',
        'pd',
        'lensType',
        'refractionOD',
        'refractionOS',
        'refractionOD_distance_va',
        'refractionOD_ADD',
        'refractionOD_near_va',
        'refractionOS_distance_va',
        'refractionOS_ADD',
        'refractionOS_near_va',
        'refractionnotes',
    ];


    public function consultation()
    {
        return $this->belongsTo('App\Models\Consultations');
    }

public function lensOrder()
{
    // Since the foreign key is on the LensOrder table:
    return $this->hasOne(LensOrder::class, 'refraction_id');
}

    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }


}
