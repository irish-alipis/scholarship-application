<?php

namespace App\Services;

use App\Models\Applicant;
use App\Models\Scholarship;

class EligibilityService
{
    /**
     * Run every requirement of the scholarship against the applicant.
     * Each requirement becomes a rule; this method never needs to know which kind.
     */
    public function check(Applicant $applicant, Scholarship $scholarship): EligibilityReport
    {
        $report = new EligibilityReport();

        foreach ($scholarship->requirements as $requirement) {
            $rule = $requirement->toRule();

            if (! $rule->evaluate($applicant)) {
                $report->addReason($rule->getFailureReason());
            }
        }

        return $report;
    }
}
