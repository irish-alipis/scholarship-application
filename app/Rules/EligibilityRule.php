<?php

namespace App\Rules;

use App\Models\Applicant;

/**
 * One scholarship requirement, turned into something that can be checked.
 * EligibilityService only knows this contract, not GWA, income or year level.
 */
interface EligibilityRule
{
    /** True when the applicant meets this requirement. */
    public function evaluate(Applicant $applicant): bool;

    /** Why the last evaluate() failed, or null when it passed. */
    public function getFailureReason(): ?string;

    /** Plain wording of the requirement, for the scholarship details page. */
    public function describe(): string;
}
