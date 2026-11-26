<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AccountStatus;
use App\Enums\Action;
use App\Enums\ApplicationStatus;
use App\Enums\EntityType;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Evaluator;
use App\Services\AuditLogger;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = Application::with(['applicant.user', 'scholarship', 'evaluator.user'])
            ->where('status', '!=', ApplicationStatus::Draft->value)
            ->when($request->status, fn($q, $status) => $q->where('status', $status))
            ->latest('submitted_at')
            ->paginate(10)
            ->withQueryString();

        $counts = [
            'pending' => Application::whereIn('status', [
                ApplicationStatus::Submitted->value,
                ApplicationStatus::UnderEvaluation->value,
            ])->count(),
            'approved' => Application::where('status', ApplicationStatus::Approved->value)->count(),
            'rejected' => Application::where('status', ApplicationStatus::Rejected->value)->count(),
        ];

        return view('Admin.applications.index', compact('applications', 'counts'));
    }

    public function show(Application $application)
    {
        $application->load([
            'applicant.user',
            'scholarship',
            'evaluator.user',
            'documents.requiredDocument',
        ]);

        $evaluators = Evaluator::with('user')
            ->whereHas('user', fn($q) => $q
                ->where('status', AccountStatus::Active->value)
                ->where('role', Role::Evaluator->value))
            ->get();

        return view('Admin.applications.show', compact('application', 'evaluators'));
    }

    public function assign(Request $request, Application $application)
    {
        $data = $request->validate([
            'evaluator_id' => ['required', 'exists:evaluators,id'],
        ]);

        if (!in_array($application->status, [ApplicationStatus::Submitted, ApplicationStatus::UnderEvaluation], true)) {
            return back()->with('error', 'Only submitted or under-evaluation applications can be assigned.');
        }

        $evaluator = Evaluator::with('user')->findOrFail($data['evaluator_id']);

        if ($evaluator->user->status !== AccountStatus::Active) {
            return back()->with('error', 'That evaluator account is not active.');
        }

        if ($evaluator->user->role !== Role::Evaluator) {
            return back()->with('error', 'That user is not an evaluator.');
        }

        if ((int) $application->assigned_evaluator_id === $evaluator->id) {
            return back()->with('error', 'This evaluator is already assigned.');
        }

        $wasAssigned = $application->assigned_evaluator_id !== null;

        $application->assignTo($evaluator);

        app(AuditLogger::class)->log(
            auth()->user(),
            $wasAssigned ? Action::Reassigned : Action::Assigned,
            EntityType::Application,
            $application->id,
            'Evaluator: ' . $evaluator->user->name
        );

        return back()->with('success', $wasAssigned ? 'Application reassigned.' : 'Application assigned.');
    }
}