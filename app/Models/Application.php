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

    protected static function booted(): void
    {
        static::saved(function (Application $application) {
            if ($application->status === 'diproses') {
                $application->selection()->updateOrCreate(
                    ['application_id' => $application->id],
                    [
                        'stage' => 'Administrasi',
                        'status' => 'verifikasi',
                        'date' => now(),
                    ]
                );
            } elseif ($application->status === 'ditolak') {
                $application->selection()->updateOrCreate(
                    ['application_id' => $application->id],
                    [
                        'stage' => 'Administrasi',
                        'status' => 'tidak diterima',
                        'date' => now(),
                    ]
                );
            }
        });
    }

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

    public function requirementValues()
    {
        return $this->hasMany(ApplicationRequirementValue::class);
    }
}
