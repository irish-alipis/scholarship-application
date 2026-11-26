<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case UnderEvaluation = 'under_evaluation';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Cancelled = 'cancelled';

    public function label(): string 
    {
        return trim(preg_replace('/(?<!^)[A-Z]/', ' $0', $this->name));
    }
}