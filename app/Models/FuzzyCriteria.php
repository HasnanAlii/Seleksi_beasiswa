<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FuzzyCriteria extends Model
{
    protected $table = 'fuzzy_criteria';

    protected $fillable = [
        'criteria_name',
    ];

    public function memberships()
    {
        return $this->hasMany(FuzzyMembership::class, 'criteria_id');
    }
}
