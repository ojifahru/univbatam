<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class registration extends Model
{
    use HasTranslations;

    protected $fillable = [
        'key',
        'value',
    ];

    protected $translatable = [
        'key',
        'value',
    ];

    protected $casts = [
        'key' => 'array',
        'value' => 'array',
    ];
}
