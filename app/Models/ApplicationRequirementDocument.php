<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicationRequirementDocument extends Model
{
    protected $fillable = [
        'application_requirement_value_id',
        'document_path',
        'original_name',
    ];

    public function requirementValue()
    {
        return $this->belongsTo(ApplicationRequirementValue::class, 'application_requirement_value_id');
    }
}
