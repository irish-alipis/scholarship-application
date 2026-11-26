<?php

namespace App\Support;

use App\Enums\ScholarshipStatus;
use App\Models\Scholarship;

/**
 * Open or closed, as the applicant sees it. A scholarship counts as open only
 * while its status is Open AND today is between the start date and the deadline.
 */
class ScholarshipAvailability
{
    public static function isOpen(Scholarship $scholarship): bool
    {
        return $scholarship->status === ScholarshipStatus::Open
            && now()->greaterThanOrEqualTo($scholarship->start_date)
            && now()->lessThanOrEqualTo($scholarship->deadline);
    }

    public static function label(Scholarship $scholarship): string
    {
        if (self::isOpen($scholarship)) {
            return 'Open';
        }

        if ($scholarship->status === ScholarshipStatus::Open && now()->lessThan($scholarship->start_date)) {
            return 'Not yet open';
        }

        return 'Closed';
    }

    /** green for open, grey for not yet open, red for closed */
    public static function color(Scholarship $scholarship): string
    {
        return match (self::label($scholarship)) {
            'Open' => 'green',
            'Not yet open' => 'grey',
            default => 'red',
        };
    }

    /** Whole days left until the deadline, or null once it has passed. */
    public static function daysLeft(Scholarship $scholarship): ?int
    {
        if (now()->greaterThan($scholarship->deadline)) {
            return null;
        }

        return (int) ceil(abs(now()->diffInDays($scholarship->deadline)));
    }
}
