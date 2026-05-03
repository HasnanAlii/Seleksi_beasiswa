<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewAssessment extends Model
{
    protected $fillable = [
        'interview_id',
        'score',
        'notes',
        'interviewer',
    ];

    protected static function booted(): void
    {
        static::created(function (InterviewAssessment $assessment) {
            $application = $assessment->interview->application;
            if ($application) {
                $application->selection()->updateOrCreate(
                    ['application_id' => $application->id],
                    [
                        'stage' => 'Wawancara Selesai',
                        'status' => 'siap di proses',
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
            'score' => 'decimal:2',
        ];
    }

    public function interview()
    {
        return $this->belongsTo(Interview::class);
    }
}
