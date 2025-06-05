<?php

namespace App\Models;

use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Identity extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'email',
        'domain',
        'address',
        'phone',
        'meta_description',
        'meta_keywords',
        'maps',
        'facebook',
        'twitter',
        'instagram',
        'linkedin',
        'whatsapp',
    ];
}
