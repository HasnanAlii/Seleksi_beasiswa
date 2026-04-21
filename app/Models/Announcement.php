<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'scholarship_id',
        'title',
        'date',
        'publish_status',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'publish_status' => 'boolean',
        ];
    }

    /**
     * Get the scholarship that owns the announcement.
     */
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }
}
