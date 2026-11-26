<?php

namespace App\Http\Controllers\Applicant;

use App\Enums\ScholarshipStatus;
use App\Enums\ScholarshipType;
use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use App\Services\EligibilityService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Scholarship Browsing: list with search and filters, and the details page. */
class ScholarshipController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'type' => ['nullable', 'in:'.implode(',', array_column(ScholarshipType::cases(), 'value'))],
            'availability' => ['nullable', 'in:open,closed'],
        ]);

        $query = Scholarship::query()
            // Drafts and inactive scholarships are not shown to applicants.
            ->whereIn('status', [ScholarshipStatus::Open->value, ScholarshipStatus::Closed->value])
            ->when($filters['q'] ?? null, function ($query, $term) {
                $query->where(function ($query) use ($term) {
                    $query->where('name', 'like', "%{$term}%")->orWhere('description', 'like', "%{$term}%");
                });
            })
            ->when($filters['type'] ?? null, fn ($query, $type) => $query->where('type', $type))
            ->when(($filters['availability'] ?? null) === 'open', function ($query) {
                $query->where('status', ScholarshipStatus::Open->value)
                    ->where('start_date', '<=', now())
                    ->where('deadline', '>=', now());
            })
            ->when(($filters['availability'] ?? null) === 'closed', function ($query) {
                $query->where(function ($query) {
                    $query->where('status', ScholarshipStatus::Closed->value)
                        ->orWhere('deadline', '<', now())
                        ->orWhere('start_date', '>', now());
                });
            })
            ->orderBy('deadline');

        $applicant = $request->user()->applicant;

        return view('applicant.scholarships.index', [
            'scholarships' => $query->paginate(9)->withQueryString(),
            'filters' => $filters,
            'types' => ScholarshipType::cases(),
            // The applicant's latest live application per scholarship, to show "Applied".
            'myApplications' => $applicant
                ? $applicant->applications()->latest()->get()->unique('scholarship_id')->keyBy('scholarship_id')
                : collect(),
        ]);
    }

    public function show(Request $request, Scholarship $scholarship, EligibilityService $eligibility): View
    {
        abort_unless(
            in_array($scholarship->status, [ScholarshipStatus::Open, ScholarshipStatus::Closed], true),
            404
        );

        $scholarship->load(['requirements', 'requiredDocuments']);

        $applicant = $request->user()->applicant;

        $activeApplication = $applicant
            ? $applicant->applications()
                ->where('scholarship_id', $scholarship->id)
                ->latest()->get()
                ->first(fn ($application) => $application->isActive())
            : null;

        $profileComplete = $applicant !== null
            && $applicant->gwa !== null
            && $applicant->annual_family_income !== null
            && $applicant->year_level !== null;

        return view('applicant.scholarships.show', [
            'scholarship' => $scholarship,
            'activeApplication' => $activeApplication,
            'hasProfile' => $applicant !== null,
            'profileComplete' => $profileComplete,
            // "Would I be eligible?" uses the details already saved in the profile.
            'report' => $profileComplete ? $eligibility->check($applicant, $scholarship) : null,
        ]);
    }
}
