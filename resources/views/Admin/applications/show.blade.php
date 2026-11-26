@extends('layouts.master')

@section('title', 'Application Details')
@section('page-title', 'Application Details')

@section('content')
    <div class="box custom-box">
        <div class="box-header flex justify-between">
            <div class="box-title">{{ $application->scholarship->name }}</div>
            <a href="{{ route('admin.applications.index') }}"
                class="ti-btn ti-btn-light !py-1 !px-2 !text-[0.75rem]">Back</a>
        </div>
        <div class="box-body">
            <div class="grid grid-cols-12 gap-4">
                <div class="md:col-span-3 col-span-6"><small>Applicant</small>
                    <div class="font-semibold">{{ $application->applicant->user->name }}</div>
                </div>
                <div class="md:col-span-3 col-span-6"><small>Student No.</small>
                    <div class="font-semibold">{{ $application->applicant->student_no }}</div>
                </div>
                <div class="md:col-span-3 col-span-6"><small>Course</small>
                    <div class="font-semibold">{{ $application->applicant->course }}</div>
                </div>
                <div class="md:col-span-3 col-span-6"><small>Status</small>
                    <div class="font-semibold">{{ $application->status->label() }}</div>
                </div>

                <div class="md:col-span-3 col-span-6"><small>GWA</small>
                    <div class="font-semibold">{{ $application->gwa ?? '-' }}</div>
                </div>
                <div class="md:col-span-3 col-span-6"><small>Annual Family Income</small>
                    <div class="font-semibold">
                        {{ $application->annual_family_income !== null ? '₱' . number_format($application->annual_family_income, 2) : '-' }}
                    </div>
                </div>
                <div class="md:col-span-3 col-span-6"><small>Year Level</small>
                    <div class="font-semibold">{{ $application->year_level ?? '-' }}</div>
                </div>
                <div class="md:col-span-3 col-span-6"><small>Submitted</small>
                    <div class="font-semibold">{{ $application->submitted_at?->format('M d, Y h:i A') ?? '-' }}</div>
                </div>

                <div class="md:col-span-6 col-span-12"><small>Eligibility Result</small>
                    <div class="font-semibold">{{ $application->eligibility_result ?? '-' }}</div>
                </div>
                <div class="md:col-span-6 col-span-12"><small>Eligibility Reasons</small>
                    <div>{{ $application->eligibility_reasons ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="box custom-box">
        <div class="box-header">
            <div class="box-title">Evaluator Assignment</div>
        </div>
        <div class="box-body">
            <p class="mb-3">
                Current evaluator:
                <strong>{{ $application->evaluator?->user->name ?? 'Unassigned' }}</strong>
                @if($application->assigned_at) <small>(since {{ $application->assigned_at->format('M d, Y') }})</small>
                @endif
            </p>

            @if(in_array($application->status, [\App\Enums\ApplicationStatus::Submitted, \App\Enums\ApplicationStatus::UnderEvaluation], true))
                <form method="POST" action="{{ route('admin.applications.assign', $application) }}">
                    @csrf
                    @method('PATCH')
                    <div class="grid grid-cols-12 gap-4 items-end">
                        <div class="md:col-span-6 col-span-12">
                            <label class="form-label">{{ $application->evaluator ? 'Reassign to' : 'Assign to' }}</label>
                            <select name="evaluator_id" class="form-control">
                                <option value="">Select evaluator</option>
                                @foreach($evaluators as $ev)
                                    <option value="{{ $ev->id }}" @selected((int) $application->assigned_evaluator_id === $ev->id)>
                                        {{ $ev->user->name }} ({{ $ev->employee_no }})
                                    </option>
                                @endforeach
                            </select>
                            @error('evaluator_id') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="md:col-span-3 col-span-12">
                            <button type="submit" class="ti-btn ti-btn-primary w-full">
                                {{ $application->evaluator ? 'Reassign' : 'Assign' }}
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <small class="text-muted">This application is {{ strtolower($application->status->label()) }}, so it can no
                    longer be assigned.</small>
            @endif
        </div>
    </div>

    <div class="box custom-box">
        <div class="box-header">
            <div class="box-title">Submitted Documents</div>
        </div>
        <div class="box-body">
            <ul>
                @forelse($application->documents as $doc)
                    <li>{{ $doc->requiredDocument->name }}: {{ $doc->file_name }}</li>
                @empty
                    <li>No documents uploaded.</li>
                @endforelse
            </ul>
        </div>
    </div>
@endsection