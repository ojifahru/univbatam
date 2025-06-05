<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Str; // Import the Str class

class News extends Model
{
    use HasTranslations;
    protected $fillable = [
        'title',
        'content',
        'category_id',
        'image',
        'is_published',
        'user_id',
        'published_at',
    ];

    protected $translatable = [
        'title',
        'content',
    ];

    protected $casts = [
        'title' => 'array',
        'content' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class);
    }
}
