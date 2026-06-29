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
        'document_path',
        'validation_status',
        'validation_notes',
        'validated_at',
    ];

    protected function casts(): array
    {
        return [
            'validation_status' => 'integer',
            'validated_at' => 'datetime',
        ];
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function requirement()
    {
        return $this->belongsTo(Requirement::class);
    }

    public function documents()
    {
        return $this->hasMany(ApplicationRequirementDocument::class, 'application_requirement_value_id');
    }
}
