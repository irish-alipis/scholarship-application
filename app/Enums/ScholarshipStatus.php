<?php

namespace App\Enums;

enum ScholarshipStatus: string
{
    case Draft = 'draft';
    case Open = 'open';
    case Closed = 'closed';
    case Inactive = 'inactive';

    public function label(): string 
    {
        return trim(preg_replace('/(?<!^)[A-Z]/', ' $0', $this->name));
    } 
}