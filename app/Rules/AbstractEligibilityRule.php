<?php

namespace App\Rules;

use App\Enums\RequirementCondition;
use App\Models\Applicant;

/**
 * Shared logic for every rule. A subclass only says which applicant value
 * it checks (getActualValue) and what to call it (getLabel).
 */
abstract class AbstractEligibilityRule implements EligibilityRule
{
    private ?string $failureReason = null;

    public function __construct(
        protected readonly RequirementCondition $condition,
        protected readonly float $requiredValue,
    ) {
    }

    /** The applicant's own value, or null when the profile does not have it yet. */
    abstract protected function getActualValue(Applicant $applicant): ?float;

    abstract protected function getLabel(): string;

    public function evaluate(Applicant $applicant): bool
    {
        $this->failureReason = null;
        $actual = $this->getActualValue($applicant);

        if ($actual === null) {
            $this->failureReason = $this->getLabel().' is missing from your profile.';

            return false;
        }

        if ($this->compare($actual)) {
            return true;
        }

        $this->failureReason = $this->buildReason($actual);

        return false;
    }

    protected function compare(float $actual): bool
    {
        return match ($this->condition) {
            RequirementCondition::LessThanOrEqual => $actual <= $this->requiredValue,
            RequirementCondition::GreaterThanOrEqual => $actual >= $this->requiredValue,
            RequirementCondition::Equal => abs($actual - $this->requiredValue) < 0.00001,
        };
    }

    public function getFailureReason(): ?string
    {
        return $this->failureReason;
    }

    public function describe(): string
    {
        $label = $this->getLabel();
        $required = $this->formatValue($this->requiredValue);

        return match ($this->condition) {
            RequirementCondition::LessThanOrEqual => "{$label} must be {$required} or lower",
            RequirementCondition::GreaterThanOrEqual => "{$label} must be {$required} or higher",
            RequirementCondition::Equal => "{$label} must be exactly {$required}",
        };
    }

    /** How a number is shown in messages. A subclass may override it. */
    protected function formatValue(float $value): string
    {
        return floor($value) === $value ? number_format($value) : number_format($value, 2);
    }

    private function buildReason(float $actual): string
    {
        $label = $this->getLabel();
        $actualText = $this->formatValue($actual);
        $requiredText = $this->formatValue($this->requiredValue);

        return match ($this->condition) {
            RequirementCondition::LessThanOrEqual => "{$label} {$actualText} is higher than the required {$requiredText}.",
            RequirementCondition::GreaterThanOrEqual => "{$label} {$actualText} is lower than the required {$requiredText}.",
            RequirementCondition::Equal => "{$label} {$actualText} does not match the required {$requiredText}.",
        };
    }
}
