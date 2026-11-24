<?php

namespace App\Models;

use App\Enums\RequirementCondition;
use App\Enums\RequirementType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScholarshipRequirement extends Model
{
    protected $fillable = [
        'scholarship_id', 'requirement_type', 'requirement_condition', 'required_value',
    ];

    protected function casts(): array
    {
        return [
            'requirement_type' => RequirementType::class,
            'requirement_condition' => RequirementCondition::class,
            'required_value' => 'float',
        ];
    }

    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class);
    }

    // TODO (Applicant owner, with the rule classes): toRule()
}