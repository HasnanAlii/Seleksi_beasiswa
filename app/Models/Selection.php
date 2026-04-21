<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Selection extends Model
{
    protected $fillable = [
        'application_id',
        'stage',
        'status',
        'notes',
        'date',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}
