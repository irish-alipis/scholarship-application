<?php

namespace App\Enums;

enum RequirementCondition: string
{
    case LessThanOrEqual = 'less_than_or_equal';
    case GreaterThanOrEqual = 'greater_than_or_equal';
    case Equal = 'equal';
}