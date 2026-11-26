<?php

namespace App\Rules;

use App\Models\Applicant;

class YearLevelRule extends AbstractEligibilityRule
{
    protected function getActualValue(Applicant $applicant): ?float
    {
        return $applicant->year_level === null ? null : (float) $applicant->year_level;
    }

    protected function getLabel(): string
    {
        return 'Year level';
    }

    protected function formatValue(float $value): string
    {
        return (string) (int) $value;
    }
}
