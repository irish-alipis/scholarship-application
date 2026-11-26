<?php

namespace App\Reports;

use App\Models\Evaluation;
use Carbon\Carbon;

class EvaluationReport implements ReportGeneratorInterface
{
    public function generate(Carbon $from, Carbon $to): array
    {
        return Evaluation::with(['application.applicant.user', 'application.scholarship', 'evaluator.user'])
            ->whereBetween('evaluated_at', [$from, $to])
            ->orderBy('evaluated_at')
            ->get()
            ->map(fn($e) => [
                'Applicant' => $e->application->applicant->user->name,
                'Scholarship' => $e->application->scholarship->name,
                'Evaluator' => $e->evaluator->user->name,
                'Score' => $e->score,
                'Decision' => $e->decision instanceof \BackedEnum ? ucfirst($e->decision->value) : ucfirst((string) $e->decision),
                'Evaluated' => $e->evaluated_at?->format('M d, Y') ?? '-',
            ])
            ->values()
            ->all();
    }

    public function getTitle(): string
    {
        return 'Evaluation Report';
    }
}