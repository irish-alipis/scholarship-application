<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AccountStatus;
use App\Enums\ApplicationStatus;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Evaluation;
use App\Models\Scholarship;
use App\Models\User;
use App\Reports\ApplicationReport;
use App\Reports\EvaluationReport;
use App\Reports\ReportGeneratorInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    private function generator(?string $type): ReportGeneratorInterface
    {
        return $type === 'evaluation' ? new EvaluationReport() : new ApplicationReport();
    }

    private function range(Request $request): array
    {
        $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'type' => ['nullable', 'in:application,evaluation'],
        ]);

        return [
            Carbon::parse($request->input('from', now()->subDays(30)))->startOfDay(),
            Carbon::parse($request->input('to', now()))->endOfDay(),
        ];
    }

    public function index(Request $request)
    {
        [$from, $to] = $this->range($request);

        $generator = $this->generator($request->input('type'));
        $rows = $generator->generate($from, $to);
        $title = $generator->getTitle();

        $totals = [
            'applicants' => User::where('role', Role::Applicant->value)->where('status', AccountStatus::Active->value)->count(),
            'evaluators' => User::where('role', Role::Evaluator->value)->where('status', AccountStatus::Active->value)->count(),
            'submitted' => Application::where('status', '!=', ApplicationStatus::Draft->value)->count(),
        ];

        $statusCounts = Application::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $byScholarship = Scholarship::withCount([
            'applications' => fn ($q) => $q->where('status', '!=', ApplicationStatus::Draft->value),
        ])->get();

        $evaluationStats = [
            'total' => Evaluation::count(),
            'finalized' => Evaluation::where('is_finalized', true)->count(),
            'average_score' => round((float) Evaluation::avg('score'), 2),
            'approved' => Evaluation::where('decision', 'approved')->count(),
            'rejected' => Evaluation::where('decision', 'rejected')->count(),
        ];

        return view('Admin.reports.index', compact(
            'rows', 'title', 'from', 'to', 'totals', 'statusCounts', 'byScholarship', 'evaluationStats'
        ));
    }

    public function export(Request $request)
    {
        [$from, $to] = $this->range($request);

        $generator = $this->generator($request->input('type'));
        $rows = $generator->generate($from, $to);

        $filename = Str::slug($generator->getTitle())
            . '-' . $from->format('Ymd') . '-' . $to->format('Ymd') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');

            if ($rows) {
                fputcsv($out, array_keys($rows[0]), ',', '"', '');
            }
            foreach ($rows as $row) {
                fputcsv($out, $row, ',', '"', '');
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}