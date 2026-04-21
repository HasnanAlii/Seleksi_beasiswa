<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    protected $fillable = [
        'scholarship_name',
        'scholarship_type',
        'quota',
        'validity_period',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'validity_period' => 'date',
        ];
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function requirements()
    {
        return $this->hasMany(ScholarshipRequirement::class);
    }
}
