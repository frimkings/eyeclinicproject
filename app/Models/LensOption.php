<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LensOption extends Model
{
    public const FAMILIES = ['Single Vision', 'Bifocal', 'Progressive'];

    protected $fillable = ['family', 'display_name'];
}
