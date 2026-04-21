<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'student_id',
        'scholarship_id',
        'status',
        'description',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    public function selection()
    {
        return $this->hasOne(Selection::class);
    }
}
