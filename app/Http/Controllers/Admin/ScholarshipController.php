<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Action;
use App\Enums\EntityType;
use App\Enums\ScholarshipStatus;
use App\Enums\ScholarshipType;
use App\Http\Controllers\Controller;
use App\Models\Scholarship;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class ScholarshipController extends Controller
{
    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'type' => ['required', Rule::enum(ScholarshipType::class)],
            'slots' => ['required', 'integer', 'min:1'],
            'start_date' => ['required', 'date'],
            'deadline' => ['required', 'date', 'after:start_date'],
        ];
    }

    public function index()
    {
        $scholarships = Scholarship::latest()->paginate(10);

        return view('Admin.scholarships.index', compact('scholarships'));
    }

    public function create()
    {
        return view('Admin.scholarships.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['status'] = ScholarshipStatus::Draft;

        $scholarship = Scholarship::create($data);

        app(AuditLogger::class)->log(auth()->user(), Action::Created, EntityType::Scholarship, $scholarship->id, 'Created scholarship ' . $scholarship->name);

        return redirect()->route('admin.scholarships.show', $scholarship)
            ->with('success', 'Scholarship created. Now add its eligibility requirements.');
    }

    public function show(Scholarship $scholarship)
    {
        $scholarship->load('requirements', 'requiredDocuments');

        return view('Admin.scholarships.show', compact('scholarship'));
    }

    public function edit(Scholarship $scholarship)
    {
        return view('Admin.scholarships.edit', compact('scholarship'));
    }

    public function update(Request $request, Scholarship $scholarship)
    {
        $scholarship->fill($request->validate($this->rules()));
        $changed = implode(', ', array_keys($scholarship->getDirty()));
        $scholarship->save();
        app(AuditLogger::class)->log(
            auth()->user(),
            Action::Updated,
            EntityType::Scholarship,
            $scholarship->id,
            'Changed fields: ' . ($changed ?: 'none')
        );

        return redirect()->route('admin.scholarships.show', $scholarship)
            ->with('success', 'Scholarship updated.');
    }

    public function updateStatus(Request $request, Scholarship $scholarship)
    {
        $data = $request->validate([
            'status' => ['required', Rule::enum(ScholarshipStatus::class)],
        ]);

        if ($data['status'] === ScholarshipStatus::Open->value && !$scholarship->open()) {
            return back()->with('error', 'Cannot open: the deadline has already passed. Edit the dates first.');
        }

        match ($data['status']) {
            ScholarshipStatus::Open->value => null, // already handled above
            ScholarshipStatus::Closed->value => $scholarship->close(),
            ScholarshipStatus::Inactive->value => $scholarship->deactivate(),
            default => $scholarship->update(['status' => ScholarshipStatus::Draft]),
        };

        app(AuditLogger::class)->log(
            auth()->user(),
            Action::Updated,
            EntityType::Scholarship,
            $scholarship->id,
            'Status set to ' . $data['status']
        );

        return back()->with('success', 'Status updated.');
    }
}