<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Action;
use App\Enums\EntityType;
use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use App\Models\ScholarshipRequirement;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScholarshipRequirementController extends Controller
{
    public function store(Request $request, Scholarship $scholarship)
    {
        $data = $request->validate([
            'requirement_type' => ['required', Rule::enum(\App\Enums\RequirementType::class)],
            'requirement_condition' => ['required', Rule::enum(\App\Enums\RequirementCondition::class)],
            'required_value' => ['required', 'numeric', 'min:0'],
        ]);

        // One rule per type: adding the same type again updates it.
        $scholarship->requirements()->updateOrCreate(
            ['requirement_type' => $data['requirement_type']],
            $data
        );

        app(AuditLogger::class)->log(
            auth()->user(),
            Action::Updated,
            EntityType::Requirement,
            $scholarship->id,
            "Set {$data['requirement_type']} {$data['requirement_condition']} {$data['required_value']} on {$scholarship->name}"
        );

        return back()->with('success', 'Requirement saved.');
    }

    public function destroy(Scholarship $scholarship, int $requirement)
    {
        $req = $scholarship->requirements()->findOrFail($requirement);
        $req->delete();
        app(AuditLogger::class)->log(
            auth()->user(),
            Action::Updated,
            EntityType::Requirement,
            $scholarship->id,
            'Removed a requirement from ' . $scholarship->name
        );

        return back()->with('success', 'Requirement removed.');
    }
}