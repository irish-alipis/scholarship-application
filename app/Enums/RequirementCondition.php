<?php

namespace App\Enums;

enum RequirementCondition: string
{
    case LessThanOrEqual = 'less_than_or_equal';
    case GreaterThanOrEqual = 'greater_than_or_equal';
    case Equal = 'equal';

    public function lable(): string 
    {
        return match ($this->name) {
            'LessThanOrEqual', 'LESS_THAN_OR_EQUAL' => 'at most (<=)',
            'GreaterThanOrEqual', 'GREATER_THAN_OR_EQUAL' => 'at least (>=)',
            default => 'equal to (=)',
        };
    }
    
}