<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Evaluator = 'evaluator';
    case Applicant = 'applicant';
}