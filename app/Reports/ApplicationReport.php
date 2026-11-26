<?php

namespace App\Reports;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Carbon\Carbon;

class ApplicationReport implements ReportGeneratorInterface
{
    public function generate(Carbon $from, Carbon $to): array
    {
        return Application::with(['applicant.user', 'scholarship', 'evaluator.user'])
            ->where('status', '!=', ApplicationStatus::Draft->value)
            ->whereBetween('submitted_at', [$from, $to])
            ->orderBy('submitted_at')
            ->get()
            ->map(fn($a) => [
                'Applicant' => $a->applicant->user->name,
                'Student No.' => $a->applicant->student_no,
                'Scholarship' => $a->scholarship->name,
                'Status' => $a->status->label(),
                'Evaluator' => $a->evaluator?->user->name ?? 'Unassigned',
                'Submitted' => $a->submitted_at?->format('M d, Y') ?? '-',
                'Decided' => $a->decided_at?->format('M d, Y') ?? '-',
            ])
            ->values()
            ->all();
    }

    public function getTitle(): string
    {
        return 'Application Report';
    }
}