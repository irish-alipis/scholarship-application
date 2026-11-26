<?php

namespace App\Models;

use App\Enums\RequirementCondition;
use App\Enums\RequirementType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScholarshipRequirement extends Model
{
    protected $fillable = [
        'scholarship_id',
        'requirement_type',
        'requirement_condition',
        'required_value',
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

    public function displayValue(): string
    {
        return match ($this->requirement_type) {
            RequirementType::AnnualFamilyIncome => '₱' . number_format($this->required_value, 2),
            RequirementType::YearLevel => (string) (int) $this->required_value,
            default => number_format($this->required_value, 2),
        };
    }

    // TODO (Applicant owner, with the rule classes): toRule()
}