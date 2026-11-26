<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Action;
use App\Enums\EntityType;
use App\Http\Controllers\Controller;
use App\Models\RequiredDocument;
use App\Models\Scholarship;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScholarshipDocumentController extends Controller
{
    public function store(Request $request, Scholarship $scholarship)
    {

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('required_documents', 'name')->where('scholarship_id', $scholarship->id),
            ],
            'is_required' => ['required', 'boolean'],
        ]);
        $scholarship->requiredDocuments()->create($data);

        app(AuditLogger::class)->log(
            auth()->user(),
            Action::Updated,
            EntityType::Requirement,
            $scholarship->id,
            'Added document ' . $data['name'] . 'to' . $scholarship->name
        );

        return back()->with('success', 'Document added.');
    }

    public function destroy(Scholarship $scholarship, int $document)
    {
        $doc = $scholarship->requiredDocuments()->findOrFail($document);
        $name = $doc->name;
        $doc->delete();

        app(AuditLogger::class)->log(
            auth()->user(),
            Action::Updated,
            EntityType::Requirement,
            $scholarship->id,
            'Removed document ' . $name . ' from ' . $scholarship->name
        );

        return back()->with('success', 'Document removed.');
    }
}

