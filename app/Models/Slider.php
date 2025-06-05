<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Slider extends Model
{
    protected $fillable = [
        'image',
    ];

    protected $casts = [
        'image' => 'string',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
