<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'application_id',
        'schedule',
        'description',
    ];

    protected static function booted(): void
    {
        static::created(function (Interview $interview) {
            $application = $interview->application;
            if ($application) {
                $application->selection()->updateOrCreate(
                    ['application_id' => $application->id],
                    [
                        'stage' => 'Wawancara',
                        'status' => 'wawancara',
                        'date' => now(),
                    ]
                );
            }
        });
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'schedule' => 'datetime',
        ];
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function assessments()
    {
        return $this->hasMany(InterviewAssessment::class);
    }
}
