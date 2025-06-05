<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Str;

class AboutUs extends Model
{
    use HasTranslations;

    protected $fillable = [
        'key',
        'slug',
        'value',
    ];

    public $translatable = ['key', 'value', 'slug']; // Add slug to translatable fields

    protected static function booted()
    {
        static::saving(function ($aboutUs) {
            // Generate slug from key for each locale
            foreach ($aboutUs->getTranslatedLocales('key') as $locale) {
                $key = $aboutUs->getTranslation('key', $locale);
                $aboutUs->setTranslation('slug', $locale, Str::slug($key));
            }
        });
    }
}
