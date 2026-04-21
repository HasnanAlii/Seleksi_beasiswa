<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'name',
        'student_number',
        'study_program',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
