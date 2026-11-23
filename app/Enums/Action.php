<?php

namespace App\Enums;

enum Action: string
{
    case Login = 'login';
    case Created = 'created';
    case Updated = 'updated';
    case Deactivated = 'deactivated';
    case Submitted = 'submitted';
    case Assigned = 'assigned';
    case Reassigned = 'reassigned';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Cancelled = 'cancelled';
}