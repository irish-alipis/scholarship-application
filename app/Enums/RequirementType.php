<?php

namespace App\Enums;

enum RequirementType: string
{
    case Gwa = 'gwa';
    case Income = 'income';
    case YearLevel = 'year_level';
}