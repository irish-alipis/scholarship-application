<?php

namespace App\Models;

use App\Enums\RequirementCondition;
use App\Enums\RequirementType;
use App\Rules\EligibilityRule;
use App\Rules\GwaRule;
use App\Rules\IncomeRule;
use App\Rules\YearLevelRule;
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
            RequirementType::Income,
            RequirementType::AnnualFamilyIncome => '₱' . number_format($this->required_value, 2),
            RequirementType::YearLevel => (string) (int) $this->required_value,
            default => number_format($this->required_value, 2),
        };
    }

    public function toRule(): EligibilityRule
    {
        return match ($this->requirement_type) {
            RequirementType::Gwa => new GwaRule($this->requirement_condition, $this->required_value),
            RequirementType::Income,
            RequirementType::AnnualFamilyIncome => new IncomeRule($this->requirement_condition, $this->required_value),
            RequirementType::YearLevel => new YearLevelRule($this->requirement_condition, $this->required_value),
        };
    }

    /** Plain wording, for example "GWA must be 2.00 or lower". */
    public function describe(): string
    {
        return $this->toRule()->describe();
    }
}