<?php

namespace App\Rules;

use App\Models\Applicant;

class GwaRule extends AbstractEligibilityRule
{
    protected function getActualValue(Applicant $applicant): ?float
    {
        return $applicant->gwa;
    }

    protected function getLabel(): string
    {
        return 'GWA';
    }

    protected function formatValue(float $value): string
    {
        return number_format($value, 2);
    }
}
