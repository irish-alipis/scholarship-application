<?php

namespace App\Http\Controllers\Applicant;

use App\Enums\Action;
use App\Enums\EntityType;
use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Student profile (the details the eligibility check uses), account status and password.
 * Name and email stay on the shared Profile page.
 */
class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('applicant.profile.edit', [
            'user' => $user,
            'applicant' => $user->applicant,
        ]);
    }

    public function update(Request $request, AuditLogger $audit): RedirectResponse
    {
        $user = $request->user();
        $applicant = $user->applicant;

        $data = $request->validate([
            'student_no' => ['required', 'string', 'max:50', Rule::unique('applicants', 'student_no')->ignore($applicant?->id)],
            'course' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:1000'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gwa' => ['nullable', 'numeric', 'between:1,5'],
            'annual_family_income' => ['nullable', 'numeric', 'min:0', 'max:9999999999'],
            'year_level' => ['nullable', 'integer', 'between:1,6'],
        ]);

        $isNew = $applicant === null;
        $applicant ??= new Applicant(['user_id' => $user->id]);

        $applicant->fill(Arr::only($data, ['student_no', 'course', 'address', 'birth_date']));

        if (isset($data['gwa'])) {
            $applicant->setGwa((float) $data['gwa']);
        }
        if (isset($data['annual_family_income'])) {
            $applicant->setAnnualFamilyIncome((float) $data['annual_family_income']);
        }
        if (isset($data['year_level'])) {
            $applicant->setYearLevel((int) $data['year_level']);
        }

        $applicant->save();

        $audit->log(
            $user, $isNew ? Action::Created : Action::Updated, EntityType::User, $user->id,
            $isNew ? 'Created the student profile.' : 'Updated the student profile.'
        );

        return redirect()->route('applicant.profile.edit')->with('success', 'Your student profile was saved.');
    }

    public function updatePassword(Request $request, AuditLogger $audit): RedirectResponse
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = $request->user();
        $user->update(['password' => Hash::make($data['password'])]);

        $audit->log($user, Action::Updated, EntityType::User, $user->id, 'Changed the password.');

        return redirect()->route('applicant.profile.edit')->with('success', 'Your password was changed.');
    }
}
