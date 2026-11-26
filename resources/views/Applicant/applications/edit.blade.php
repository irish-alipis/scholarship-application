@extends('layouts.master')

@section('title', 'Application Form')
@section('page-title', 'Application Form')

@push('styles')
    @include('applicant.partials.styles')
@endpush

@section('content')
    @php
        $scholarship = $application->scholarship;
        $isDraft = $application->status === \App\Enums\ApplicationStatus::Draft;
        $missing = $application->missingDocuments();
    @endphp

    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12 xl:col-span-8">

            <div class="box">
                <div class="box-header">
                    <div class="box-title">{{ $scholarship->name }}</div>
                    @include('applicant.partials.status-badge', ['status' => $application->status])
                </div>
                <div class="box-body">
                    <p class="ap-muted mb-0">Deadline: {{ $scholarship->deadline->format('M d, Y g:i A') }}.
                        @unless ($isDraft)
                            Your application is submitted. You can still change your details and documents until the deadline; your eligibility is checked again when you save.
                        @endunless
                    </p>
                </div>
            </div>

            {{-- Personal, academic and financial information --}}
            <form method="POST" action="{{ route('applicant.applications.update', $application) }}">
                @csrf
                @method('PUT')

                <div class="box">
                    <div class="box-header"><div class="box-title">Personal information</div></div>
                    <div class="box-body">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label" for="student_no">Student number</label>
                                <input id="student_no" type="text" class="form-control" value="{{ $applicant->student_no }}" disabled>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label" for="course">Course</label>
                                <input id="course" name="course" type="text" class="form-control" value="{{ old('course', $applicant->course) }}" required>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label" for="birth_date">Birth date</label>
                                <input id="birth_date" name="birth_date" type="date" class="form-control" value="{{ old('birth_date', $applicant->birth_date?->format('Y-m-d')) }}">
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label" for="address">Address</label>
                                <input id="address" name="address" type="text" class="form-control" value="{{ old('address', $applicant->address) }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box">
                    <div class="box-header"><div class="box-title">Academic and financial information</div></div>
                    <div class="box-body">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label" for="gwa">GWA (1.00 to 5.00)</label>
                                <input id="gwa" name="gwa" type="number" step="0.01" min="1" max="5" class="form-control" value="{{ old('gwa', $applicant->gwa) }}">
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label" for="year_level">Year level</label>
                                <input id="year_level" name="year_level" type="number" step="1" min="1" max="6" class="form-control" value="{{ old('year_level', $applicant->year_level) }}">
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label" for="annual_family_income">Annual family income</label>
                                <input id="annual_family_income" name="annual_family_income" type="number" step="0.01" min="0" class="form-control" value="{{ old('annual_family_income', $applicant->annual_family_income) }}">
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="ti-btn ti-btn-primary-full">{{ $isDraft ? 'Save draft' : 'Save changes' }}</button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Documents: each row has its own upload form, so they sit outside the form above --}}
            <div class="box">
                <div class="box-header"><div class="box-title">Required documents</div></div>
                <div class="box-body">
                    <p class="ap-muted">PDF, JPG or PNG, up to 5 MB each.</p>
                    @forelse ($scholarship->requiredDocuments as $required)
                        @php $uploaded = $documents->get($required->id); @endphp
                        <div class="ap-row" style="align-items:center;flex-wrap:wrap;">
                            <span style="color:inherit;">
                                {{ $required->name }}
                                <span class="ap-badge {{ $required->is_required ? 'ap-badge-blue' : 'ap-badge-grey' }}">{{ $required->is_required ? 'Required' : 'Optional' }}</span>
                                <br>
                                @if ($uploaded)
                                    <a href="{{ $uploaded->getUrl() }}" target="_blank" class="underline text-[0.8125rem]">{{ $uploaded->file_name }}</a>
                                    <span class="ap-muted">uploaded {{ $uploaded->uploaded_at->diffForHumans() }}</span>
                                @else
                                    <span class="ap-muted">Not uploaded yet</span>
                                @endif
                            </span>
                            <span class="ap-actions">
                                <form method="POST" action="{{ route('applicant.applications.documents.store', [$application, $required]) }}" enctype="multipart/form-data" class="ap-actions">
                                    @csrf
                                    <input type="file" name="file" accept=".pdf,.jpg,.jpeg,.png" class="form-control" style="max-width:15rem;" required>
                                    <button type="submit" class="ti-btn ti-btn-sm ti-btn-light">{{ $uploaded ? 'Replace' : 'Upload' }}</button>
                                </form>
                                @if ($uploaded && ($isDraft || ! $required->is_required))
                                    <form method="POST" action="{{ route('applicant.applications.documents.destroy', [$application, $uploaded]) }}" onsubmit="return confirm('Remove this file?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ti-btn ti-btn-sm ti-btn-danger-full">Remove</button>
                                    </form>
                                @endif
                            </span>
                        </div>
                    @empty
                        <p class="mb-0 text-textmuted">This scholarship does not require any documents.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-span-12 xl:col-span-4">
            <div class="box">
                <div class="box-header"><div class="box-title">Eligibility check</div></div>
                <div class="box-body">
                    @include('applicant.partials.eligibility', ['report' => $report])
                    <p class="ap-muted mb-3">Based on the details you have saved. Save your changes to refresh it.</p>
                    <h6 class="font-semibold mb-2">Requirements</h6>
                    @foreach ($scholarship->requirements as $requirement)
                        <div class="ap-row"><span style="color:inherit;">{{ $requirement->describe() }}</span></div>
                    @endforeach
                </div>
            </div>

            <div class="box">
                <div class="box-header"><div class="box-title">Submit</div></div>
                <div class="box-body">
                    @if ($isDraft)
                        @if ($missing->isNotEmpty())
                            <div class="ap-note ap-note-info">Still missing: {{ $missing->pluck('name')->implode(', ') }}.</div>
                        @endif
                        <form method="POST" action="{{ route('applicant.applications.submit', $application) }}" onsubmit="return confirm('Submit this application? Your GWA, income and year level will be saved with it.');">
                            @csrf
                            <button type="submit" class="ti-btn ti-btn-primary-full w-full">Submit application</button>
                        </form>
                    @else
                        <p class="ap-muted mb-0">This application has already been submitted.</p>
                    @endif
                </div>
            </div>

            <div class="ap-actions" style="flex-direction:column;align-items:stretch;">
                <form method="POST" action="{{ route('applicant.applications.cancel', $application) }}" onsubmit="return confirm('Cancel this application? This cannot be undone.');">
                    @csrf
                    <button type="submit" class="ti-btn ti-btn-danger-full w-full">Cancel application</button>
                </form>
                <a href="{{ route('applicant.applications.show', $application) }}" class="ti-btn ti-btn-light">View application status</a>
            </div>
        </div>
    </div>
@endsection
