<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Str;

class Departement extends Model
{
    use HasTranslations;
    protected $fillable = [
        'name',
        'slug',
        'department_link',
        'faculty_id',
    ];
    protected $translatable = [
        'name',
        'slug',
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    protected static function booted()
    {
        static::saving(function ($department) {
            // Generate slug from name for each locale
            foreach ($department->getTranslatedLocales('name') as $locale) {
                $name = $department->getTranslation('name', $locale);
                $department->setTranslation('slug', $locale, Str::slug($name));
            }
        });
    }
}
