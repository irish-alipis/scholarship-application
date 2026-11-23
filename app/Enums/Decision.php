<?php

namespace App\Enums;

enum Decision: string
{
    case Approved = 'approved';
    case Rejected = 'rejected';
}