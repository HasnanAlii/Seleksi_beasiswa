<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuzzyMembership extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'scholarship_id',
        'criteria_id',
        'min_value',
        'mid_value',
        'max_value',
    ];

    /**
     * Get the scholarship that owns the membership.
     */
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'min_value' => 'decimal:2',
            'mid_value' => 'decimal:2',
            'max_value' => 'decimal:2',
        ];
    }

    /**
     * Get the criteria that owns the membership.
     */
    public function criteria()
    {
        return $this->belongsTo(FuzzyCriteria::class, 'criteria_id');
    }
}
