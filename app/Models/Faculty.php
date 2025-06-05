<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Faculty extends Model
{
    use HasTranslations;
    protected $fillable = [
        'name',
    ];
    protected $translatable = [
        'name',
    ];

    public function departements()
    {
        return $this->hasMany(Departement::class);
    }
}
