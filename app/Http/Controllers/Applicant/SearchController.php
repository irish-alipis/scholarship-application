<?php

namespace App\Http\Controllers\Applicant;

use App\Enums\ScholarshipStatus;
use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * The search bar in the header. One box searches the three things an applicant owns or can browse:
 * scholarships, their own applications and their own notifications.
 */
class SearchController extends Controller
{
    private const LIMIT = 8;

    public function __invoke(Request $request): View
    {
        $request->validate(['q' => ['nullable', 'string', 'max:100']]);

        $term = trim((string) $request->query('q', ''));
        $user = $request->user();
        $applicant = $user->applicant;

        $scholarships = collect();
        $applications = collect();
        $notifications = collect();

        if (mb_strlen($term) >= 2) {
            $scholarships = Scholarship::query()
                ->whereIn('status', [ScholarshipStatus::Open->value, ScholarshipStatus::Closed->value])
                ->where(function ($query) use ($term) {
                    $query->where('name', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%")
                        ->orWhere('type', 'like', '%'.str_replace(' ', '_', strtolower($term)).'%');
                })
                ->orderBy('deadline')
                ->limit(self::LIMIT)
                ->get();

            if ($applicant) {
                $applications = $applicant->applications()
                    ->with('scholarship')
                    ->where(function ($query) use ($term) {
                        $query->whereHas('scholarship', fn ($q) => $q->where('name', 'like', "%{$term}%"))
                            ->orWhere('status', 'like', '%'.str_replace(' ', '_', strtolower($term)).'%');
                    })
                    ->latest()
                    ->limit(self::LIMIT)
                    ->get();
            }

            $notifications = $user->appNotifications()
                ->where(function ($query) use ($term) {
                    $query->where('title', 'like', "%{$term}%")->orWhere('message', 'like', "%{$term}%");
                })
                ->latest()
                ->limit(self::LIMIT)
                ->get();
        }

        return view('applicant.search.results', [
            'term' => $term,
            'tooShort' => $term !== '' && mb_strlen($term) < 2,
            'scholarships' => $scholarships,
            'applications' => $applications,
            'notifications' => $notifications,
        ]);
    }
}
