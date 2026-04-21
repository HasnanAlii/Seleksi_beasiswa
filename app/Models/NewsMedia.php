<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsMedia extends Model
{
    protected $fillable = [
        'news_id',
        'file',
        'description',
    ];

    public function news()
    {
        return $this->belongsTo(News::class);
    }
}
