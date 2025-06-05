<?php

namespace App\Models;

use Spatie\Translatable\HasTranslations;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasTranslations;
    protected $fillable = [
        'name',
    ];

    protected $translatable = [
        'name',
    ];
    protected $casts = [
        'name' => 'array',
    ];

    public function news()
    {
        return $this->hasMany(News::class);
    }
}
