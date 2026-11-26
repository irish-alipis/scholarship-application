<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $applicant = $user->applicant;

        $applications = $applicant
            ? $applicant->applications()->with('scholarship')->latest()->get()
            : collect();

        $notifications = $user->appNotifications()->latest()->take(5)->get();
        $unread = $user->appNotifications()->where('is_read', false)->count();

        $profileComplete = $applicant !== null
            && $applicant->gwa !== null
            && $applicant->annual_family_income !== null
            && $applicant->year_level !== null;

        return view('applicant.dashboard', [
            'profileComplete' => $profileComplete,
            'applications' => $applications,
            'notifications' => $notifications,
            'cards' => [
                ['label' => 'My applications', 'value' => $applications->count()],
                ['label' => 'Unread notifications', 'value' => $unread],
            ],
        ]);
    }
}
