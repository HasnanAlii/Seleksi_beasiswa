<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScholarshipRequirement extends Model
{
    protected $fillable = [
        'scholarship_id',
        'requirement_id',
        'status',
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function requirement()
    {
        return $this->belongsTo(Requirement::class);
    }
}
