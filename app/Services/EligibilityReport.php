<?php

namespace App\Services;

use App\Enums\EligibilityResult;

/** The outcome of checking one applicant against one scholarship. */
class EligibilityReport
{
    /** @var list<string> */
    private array $reasons = [];

    public function isEligible(): bool
    {
        return $this->reasons === [];
    }

    public function addReason(string $reason): void
    {
        $this->reasons[] = $reason;
    }

    /** @return list<string> */
    public function getReasons(): array
    {
        return $this->reasons;
    }

    public function getResult(): EligibilityResult
    {
        return $this->isEligible() ? EligibilityResult::Eligible : EligibilityResult::Ineligible;
    }
}
