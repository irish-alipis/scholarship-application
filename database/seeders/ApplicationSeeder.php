<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus;
use App\Enums\Decision;
use App\Enums\EligibilityResult;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\Evaluation;
use App\Models\Evaluator;
use App\Models\Notification;
use App\Models\Scholarship;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguarded(function () {
            $scholarship = Scholarship::where('name', 'Academic Excellence Scholarship')->firstOrFail();
            $e1 = Evaluator::where('employee_no', 'EMP-001')->firstOrFail();
            $e2 = Evaluator::where('employee_no', 'EMP-002')->firstOrFail();

            // Maria: being reviewed
            $this->make($scholarship, '2023-0001', ApplicationStatus::UnderEvaluation, EligibilityResult::Eligible, null, $e1);

            // Juan: approved
            $juan = $this->make($scholarship, '2023-0002', ApplicationStatus::Approved, EligibilityResult::Eligible, null, $e1);
            Evaluation::create([
                'application_id' => $juan->id,
                'evaluator_id' => $e1->id,
                'score' => 92,
                'decision' => Decision::Approved,
                'remarks' => 'Meets all criteria.',
                'is_finalized' => true,
                'evaluated_at' => now()->subDay(),
            ]);
            Notification::create([
                'user_id' => $juan->applicant->user_id,
                'application_id' => $juan->id,
                'title' => 'Application approved',
                'message' => 'Your application for ' . $scholarship->name . ' was approved.',
            ]);

            // Ana: rejected
            $ana = $this->make($scholarship, '2023-0003', ApplicationStatus::Rejected, EligibilityResult::Ineligible, 'GWA 2.50 is higher than the required 2.00.', $e2);
            Evaluation::create([
                'application_id' => $ana->id,
                'evaluator_id' => $e2->id,
                'score' => 55,
                'decision' => Decision::Rejected,
                'remarks' => 'Does not meet the GWA requirement.',
                'rejection_reason' => 'GWA is above the allowed maximum.',
                'is_finalized' => true,
                'evaluated_at' => now()->subDay(),
            ]);

            // Pedro: still a draft
            $this->make($scholarship, '2023-0004', ApplicationStatus::Draft);

            // Liza: submitted, waiting for the admin to assign an evaluator
            $this->make($scholarship, '2024-0005', ApplicationStatus::Submitted, EligibilityResult::Ineligible, 'Year level 1 is lower than the required year level 2.');
        });
    }

    private function make(
        Scholarship $scholarship,
        string $studentNo,
        ApplicationStatus $status,
        ?EligibilityResult $result = null,
        ?string $reasons = null,
        ?Evaluator $evaluator = null
    ): Application {
        $applicant = Applicant::where('student_no', $studentNo)->firstOrFail();
        $submitted = $status !== ApplicationStatus::Draft;
        $decided = in_array($status, [ApplicationStatus::Approved, ApplicationStatus::Rejected], true);

        return Application::create([
            'applicant_id' => $applicant->id,
            'scholarship_id' => $scholarship->id,
            'assigned_evaluator_id' => $evaluator?->id,
            'assigned_at' => $evaluator ? now()->subDays(3) : null,
            'status' => $status,
            'eligibility_result' => $result,
            'eligibility_reasons' => $reasons,
            'gwa' => $submitted ? $applicant->gwa : null,
            'annual_family_income' => $submitted ? $applicant->annual_family_income : null,
            'year_level' => $submitted ? $applicant->year_level : null,
            'submitted_at' => $submitted ? now()->subDays(5) : null,
            'decided_at' => $decided ? now()->subDay() : null,
        ]);
    }
}