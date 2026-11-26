<?php

namespace App\Enums;

enum RequirementType: string
{
    case Gwa = 'gwa';
    case Income = 'income';
    case YearLevel = 'year_level';
    case AnnualFamilyIncome = 'annual_family_income';

    public function label(): string 
    {
        return trim(preg_replace('/(?<!^)[A-Z]/', ' $0', $this->name));
    } 
}