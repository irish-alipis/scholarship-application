<?php

namespace App\Rules;

use App\Models\Applicant;

class IncomeRule extends AbstractEligibilityRule
{
    protected function getActualValue(Applicant $applicant): ?float
    {
        return $applicant->annual_family_income;
    }

    protected function getLabel(): string
    {
        return 'Annual family income';
    }
}
