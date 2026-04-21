<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title',
        'content',
    ];

    public function media()
    {
        return $this->hasMany(NewsMedia::class);
    }
}
