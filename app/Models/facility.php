<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Str;

class Facility extends Model
{
    use HasTranslations;

    protected $fillable = ['name', 'slug', 'description', 'image'];

    public $translatable = ['name', 'slug', 'description'];

    protected static function booted()
    {
        static::saving(function ($facility) {
            // Generate slug from name for each locale
            foreach ($facility->getTranslatedLocales('name') as $locale) {
                $name = $facility->getTranslation('name', $locale);
                $facility->setTranslation('slug', $locale, Str::slug($name));
            }
        });
    }
}
