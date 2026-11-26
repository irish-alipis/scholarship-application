<?php

namespace App\Http\Controllers\Applicant;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\RequiredDocument;
use App\Models\Scholarship;
use App\Services\ApplicationService;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Scholarship Application and Application Monitoring.
 * Controllers only validate and call ApplicationService; the rules live there.
 */
class ApplicationController extends Controller
{
    public function __construct(private readonly ApplicationService $applications)
    {
    }

    /** My Applications: search by scholarship name, filter by status. */
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:'.implode(',', array_column(ApplicationStatus::cases(), 'value'))],
        ]);

        $applicant = $request->user()->applicant;

        $applications = $applicant
            ? $applicant->applications()
                ->with('scholarship')
                ->when($filters['q'] ?? null, function ($query, $term) {
                    $query->whereHas('scholarship', fn ($q) => $q->where('name', 'like', "%{$term}%"));
                })
                ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
                ->latest()
                ->paginate(10)
                ->withQueryString()
            : null;

        return view('applicant.applications.index', [
            'applications' => $applications,
            'filters' => $filters,
            'statuses' => ApplicationStatus::cases(),
            'hasProfile' => $applicant !== null,
        ]);
    }

    /** Apply: create a draft and go to the form. */
    public function store(Request $request, Scholarship $scholarship): RedirectResponse
    {
        $applicant = $request->user()->applicant;

        if (! $applicant) {
            return redirect()->route('applicant.profile.edit')
                ->with('error', 'Set up your student profile first, then apply.');
        }

        try {
            $application = $this->applications->createDraft($applicant, $scholarship, $request->user());
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('applicant.applications.edit', $application)
            ->with('success', 'Draft created. Fill in your details and upload the documents.');
    }

    /** Track status, eligibility, evaluation result, evaluator remarks and rejection reason. */
    public function show(Application $application): View
    {
        Gate::authorize('view', $application);

        $application->load(['scholarship', 'applicant', 'documents.requiredDocument', 'evaluation']);

        // The applicant sees an evaluation only once the evaluator has finalized it.
        $evaluation = $application->evaluation?->is_finalized ? $application->evaluation : null;

        return view('applicant.applications.show', [
            'application' => $application,
            'evaluation' => $evaluation,
            'canEdit' => $this->applications->canEdit($application),
            'canCancel' => $application->isEditable(),
            'notifications' => $application->appNotifications()->latest()->take(5)->get(),
        ]);
    }

    public function edit(Application $application): View|RedirectResponse
    {
        Gate::authorize('update', $application);

        if (! $this->applications->canEdit($application)) {
            return redirect()->route('applicant.applications.show', $application)
                ->with('error', 'This application can no longer be edited.');
        }

        $application->load(['scholarship.requiredDocuments', 'applicant', 'documents']);

        return view('applicant.applications.edit', [
            'application' => $application,
            'applicant' => $application->applicant,
            'documents' => $application->documents->keyBy('required_document_id'),
            'report' => $this->applications->previewEligibility($application),
        ]);
    }

    /** Save the personal, academic and financial details. */
    public function update(Request $request, Application $application): RedirectResponse
    {
        Gate::authorize('update', $application);

        $data = $request->validate([
            'course' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gwa' => ['nullable', 'numeric', 'between:1,5'],
            'annual_family_income' => ['nullable', 'numeric', 'min:0', 'max:9999999999'],
            'year_level' => ['nullable', 'integer', 'between:1,6'],
        ]);

        try {
            $this->applications->updateDetails($application, $data, $request->user());
        } catch (DomainException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('applicant.applications.edit', $application)
            ->with('success', 'Your details were saved.');
    }

    public function uploadDocument(Request $request, Application $application, RequiredDocument $requiredDocument): RedirectResponse
    {
        Gate::authorize('update', $application);

        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [
            'file.required' => 'Choose a file to upload.',
            'file.mimes' => 'The document must be a PDF, JPG or PNG file.',
            'file.max' => 'The document must not be larger than 5 MB.',
        ]);

        try {
            $this->applications->attachDocument($application, $requiredDocument, $request->file('file'), $request->user());
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', $requiredDocument->name.' was uploaded.');
    }

    public function destroyDocument(Request $request, Application $application, ApplicationDocument $document): RedirectResponse
    {
        Gate::authorize('update', $application);

        try {
            $this->applications->removeDocument($application, $document, $request->user());
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'The file was removed.');
    }

    /** Open one uploaded file. Only its owner gets it. */
    public function document(ApplicationDocument $document): StreamedResponse
    {
        Gate::authorize('view', $document->application);

        abort_unless(Storage::disk('local')->exists($document->file_path), 404);

        return Storage::disk('local')->response($document->file_path, $document->file_name);
    }

    public function submit(Request $request, Application $application): RedirectResponse
    {
        Gate::authorize('submit', $application);

        try {
            $submitted = $this->applications->submit($application, $request->user());
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        $message = 'Your application was submitted.';
        if ($submitted->eligibility_result?->value === 'ineligible') {
            $message .= ' It does not meet every requirement yet; the evaluator will see the reasons.';
        }

        return redirect()->route('applicant.applications.show', $submitted)->with('success', $message);
    }

    public function cancel(Request $request, Application $application): RedirectResponse
    {
        Gate::authorize('cancel', $application);

        try {
            $this->applications->cancel($application, $request->user());
        } catch (DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('applicant.applications.show', $application)
            ->with('success', 'Your application was cancelled.');
    }
}
