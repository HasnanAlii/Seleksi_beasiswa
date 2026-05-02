<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    protected $fillable = [
        'requirement_name',
    ];

    public function scholarshipRequirements()
    {
        return $this->hasMany(ScholarshipRequirement::class);
    }
}
