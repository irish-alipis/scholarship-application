<?php

namespace App\Enums;

enum EligibilityResult: string
{
    case Eligible = 'eligible';
    case Ineligible = 'ineligible';
}