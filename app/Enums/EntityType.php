<?php

namespace App\Enums;

enum EntityType: string
{
    case User = 'user';
    case Scholarship = 'scholarship';
    case Requirement = 'requirement';
    case Application = 'application';
    case Evaluation = 'evaluation';

    public function label(): string
    {
        return ucfirst($this->value);
    }
}