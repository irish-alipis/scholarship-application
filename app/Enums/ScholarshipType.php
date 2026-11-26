<?php

namespace App\Enums;

enum ScholarshipType: string
{
    case Academic = 'academic';
    case Merit = 'merit';
    case FinancialAid = 'financial_aid';
    case Other = 'other';

    public function label(): string 
    {
        return trim(preg_replace('/(?<!^)[A-Z]/', ' $0', $this->name));
    } 
}