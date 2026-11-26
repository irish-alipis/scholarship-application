<?php

namespace Tests\Unit;

use App\Enums\ApplicationStatus;
use App\Enums\RequirementCondition;
use App\Models\Applicant;
use App\Models\Application;
use App\Rules\GwaRule;
use App\Rules\IncomeRule;
use App\Rules\YearLevelRule;
use App\Services\EligibilityReport;
use InvalidArgumentException;
use Tests\TestCase;

/** The rule hierarchy and the status core. None of these tests touch the database. */
class EligibilityRulesTest extends TestCase
{
    public function test_gwa_rule_passes_and_fails_with_a_readable_reason(): void
    {
        $rule = new GwaRule(RequirementCondition::LessThanOrEqual, 2.00);

        $this->assertTrue($rule->evaluate(new Applicant(['gwa' => 1.75])));
        $this->assertNull($rule->getFailureReason());

        $this->assertFalse($rule->evaluate(new Applicant(['gwa' => 2.50])));
        $this->assertSame('GWA 2.50 is higher than the required 2.00.', $rule->getFailureReason());
    }

    public function test_year_level_rule_uses_greater_than_or_equal(): void
    {
        $rule = new YearLevelRule(RequirementCondition::GreaterThanOrEqual, 2);

        $this->assertTrue($rule->evaluate(new Applicant(['year_level' => 2])));
        $this->assertFalse($rule->evaluate(new Applicant(['year_level' => 1])));
        $this->assertSame('Year level 1 is lower than the required 2.', $rule->getFailureReason());
    }

    public function test_income_rule_and_missing_value(): void
    {
        $rule = new IncomeRule(RequirementCondition::LessThanOrEqual, 250000);

        $this->assertTrue($rule->evaluate(new Applicant(['annual_family_income' => 250000])));
        $this->assertFalse($rule->evaluate(new Applicant()));
        $this->assertSame('Annual family income is missing from your profile.', $rule->getFailureReason());
    }

    public function test_rules_describe_themselves(): void
    {
        $this->assertSame('GWA must be 2.00 or lower', (new GwaRule(RequirementCondition::LessThanOrEqual, 2.00))->describe());
        $this->assertSame('Year level must be 2 or higher', (new YearLevelRule(RequirementCondition::GreaterThanOrEqual, 2))->describe());
    }

    public function test_report_is_eligible_until_a_reason_is_added(): void
    {
        $report = new EligibilityReport();
        $this->assertTrue($report->isEligible());

        $report->addReason('GWA is too high.');
        $this->assertFalse($report->isEligible());
        $this->assertSame(['GWA is too high.'], $report->getReasons());
    }

    public function test_applicant_setters_reject_invalid_values(): void
    {
        $applicant = new Applicant();

        $applicant->setGwa(1.5);
        $this->assertEquals(1.5, $applicant->gwa);

        foreach ([fn () => $applicant->setGwa(0.5), fn () => $applicant->setGwa(5.5),
                  fn () => $applicant->setAnnualFamilyIncome(-1), fn () => $applicant->setYearLevel(0)] as $call) {
            try {
                $call();
                $this->fail('An invalid value was accepted.');
            } catch (InvalidArgumentException) {
                $this->addToAssertionCount(1);
            }
        }
    }

    public function test_status_transitions_follow_the_flow(): void
    {
        $application = new Application();
        $this->assertSame(ApplicationStatus::Draft, $application->status);

        $this->assertTrue($application->canTransitionTo(ApplicationStatus::Submitted));
        $this->assertTrue($application->canTransitionTo(ApplicationStatus::Cancelled));
        $this->assertFalse($application->canTransitionTo(ApplicationStatus::Approved));
        $this->assertFalse($application->canTransitionTo(ApplicationStatus::UnderEvaluation));

        foreach ([ApplicationStatus::Approved, ApplicationStatus::Rejected, ApplicationStatus::Cancelled] as $final) {
            $application->forceFill(['status' => $final]);
            $this->assertFalse($application->isEditable());
            foreach (ApplicationStatus::cases() as $target) {
                $this->assertFalse($application->canTransitionTo($target), "{$final->value} must stay final");
            }
        }
    }
}
