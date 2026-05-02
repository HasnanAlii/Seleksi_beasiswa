<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationRequirementValue extends Model
{
    protected $fillable = [
        'application_id',
        'requirement_id',
        'term',
        'applicant_value',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function requirement()
    {
        return $this->belongsTo(Requirement::class);
    }
}
