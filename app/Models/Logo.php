<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class logo extends Model
{
    protected $fillable = [
        'logo',
        'favicon',
        'footer_logo',
    ];
}
