<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AccountStatus;
use App\Enums\ApplicationStatus;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\Application;
use App\Models\Evaluator;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        // How many applications are in each status, for example ['submitted' => 3]
        $counts = Application::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // One row per status, so statuses with no applications still show 0
        $statusRows = collect(ApplicationStatus::cases())->mapWithKeys(
            fn (ApplicationStatus $status) => [Str::headline($status->name) => $counts[$status->value] ?? 0]
        );

        $pendingEvaluatorRequests = User::query()
            ->where('role', Role::Evaluator->value)
            ->where('status', AccountStatus::Pending->value)
            ->count();

        $submittedApplications = Application::query()
            ->where('status', '!=', ApplicationStatus::Draft->value)
            ->count();

        return view('admin.dashboard', [
            'cards' => [
                ['label' => 'Applicants', 'value' => User::where('role', Role::Applicant->value)->where('status', AccountStatus::Active->value)->count()],
                ['label' => 'Evaluators', 'value' => User::where('role', Role::Evaluator->value)->where('status', AccountStatus::Active->value)->count()],
                ['label' => 'Pending evaluator requests', 'value' => $pendingEvaluatorRequests],
                ['label' => 'Submitted applications', 'value' => $submittedApplications],
            ],
            'statusRows' => $statusRows,
        ]);
    }
}
