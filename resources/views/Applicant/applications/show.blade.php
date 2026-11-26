@extends('layouts.master')

@section('title', 'Application')
@section('page-title', 'My Application')

@push('styles')
    @include('applicant.partials.styles')
@endpush

@section('content')
    @php
        $status = $application->status;
        $scholarship = $application->scholarship;

        // Tracker: Draft, Submitted, Under Evaluation, then the decision.
        $order = [\App\Enums\ApplicationStatus::Draft, \App\Enums\ApplicationStatus::Submitted, \App\Enums\ApplicationStatus::UnderEvaluation];
        $position = array_search($status, $order, true);
    @endphp

    <div class="ap-steps">
        @if ($status === \App\Enums\ApplicationStatus::Cancelled)
            <span class="ap-step ap-step-done">Draft</span>
            <span class="ap-step ap-step-bad">Cancelled</span>
        @else
            @foreach ($order as $i => $step)
                @php
                    $class = $position === false ? 'ap-step-done' : ($i < $position ? 'ap-step-done' : ($i === $position ? 'ap-step-now' : ''));
                @endphp
                <span class="ap-step {{ $class }}">{{ \Illuminate\Support\Str::headline($step->name) }}</span>
            @endforeach
            @if ($status === \App\Enums\ApplicationStatus::Approved)
                <span class="ap-step ap-step-now" style="background:#e6f6ec;color:#146c43;">Approved</span>
            @elseif ($status === \App\Enums\ApplicationStatus::Rejected)
                <span class="ap-step ap-step-bad">Rejected</span>
            @else
                <span class="ap-step">Decision</span>
            @endif
        @endif
    </div>

    <div class="grid grid-cols-12 gap-x-7">
        <div class="col-span-12 xl:col-span-8">

            {{-- Decision, evaluator remarks and rejection reason --}}
            @if ($evaluation)
                <div class="box">
                    <div class="box-header"><div class="box-title">Evaluation result</div></div>
                    <div class="box-body">
                        @if ($evaluation->decision === \App\Enums\Decision::Approved)
                            <div class="ap-note ap-note-ok">Congratulations! Your application was approved.</div>
                        @else
                            <div class="ap-note ap-note-bad">Your application was not approved.</div>
                        @endif
                        <div class="ap-row"><span>Decision</span><span>{{ \Illuminate\Support\Str::headline($evaluation->decision->name) }}</span></div>
                        @if ($evaluation->score !== null)
                            <div class="ap-row"><span>Score</span><span>{{ $evaluation->score }} / 100</span></div>
                        @endif
                        <div class="ap-row"><span>Decided on</span><span>{{ ($application->decided_at ?? $evaluation->evaluated_at)?->format('M d, Y') ?? '-' }}</span></div>
                        @if ($evaluation->remarks)
                            <div class="ap-row"><span>Evaluator remarks</span><span style="text-align:right;max-width:70%;">{{ $evaluation->remarks }}</span></div>
                        @endif
                        @if ($evaluation->decision === \App\Enums\Decision::Rejected && $evaluation->rejection_reason)
                            <div class="ap-row"><span>Reason for rejection</span><span style="text-align:right;max-width:70%;">{{ $evaluation->rejection_reason }}</span></div>
                        @endif
                    </div>
                </div>
            @elseif ($status === \App\Enums\ApplicationStatus::UnderEvaluation)
                <div class="ap-note ap-note-info">An evaluator is reviewing your application. You will be notified of the result.</div>
            @elseif ($status === \App\Enums\ApplicationStatus::Submitted)
                <div class="ap-note ap-note-info">Your application was submitted and is waiting to be assigned to an evaluator.</div>
            @endif

            <div class="box">
                <div class="box-header">
                    <div class="box-title">{{ $scholarship->name }}</div>
                    @include('applicant.partials.status-badge', ['status' => $status])
                </div>
                <div class="box-body">
                    <div class="ap-row"><span>Submitted on</span><span>{{ $application->submitted_at?->format('M d, Y g:i A') ?? 'Not yet submitted' }}</span></div>
                    <div class="ap-row"><span>Deadline</span><span>{{ $scholarship->deadline->format('M d, Y g:i A') }}</span></div>
                    @if ($application->cancelled_at)
                        <div class="ap-row"><span>Cancelled on</span><span>{{ $application->cancelled_at->format('M d, Y g:i A') }}</span></div>
                    @endif
                    <div class="ap-row">
                        <span>Eligibility result</span>
                        <span>
                            @if ($application->eligibility_result)
                                <span class="ap-badge {{ $application->eligibility_result === \App\Enums\EligibilityResult::Eligible ? 'ap-badge-green' : 'ap-badge-red' }}">{{ \Illuminate\Support\Str::headline($application->eligibility_result->name) }}</span>
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    @foreach ($application->eligibilityReasonList() as $reason)
                        <div class="ap-row"><span>&nbsp;</span><span style="text-align:right;">{{ $reason }}</span></div>
                    @endforeach
                </div>
            </div>

            <div class="box">
                <div class="box-header"><div class="box-title">Details sent with this application</div></div>
                <div class="box-body">
                    @php $shown = $application->status === \App\Enums\ApplicationStatus::Draft ? $application->applicant : $application; @endphp
                    <div class="ap-row"><span>GWA</span><span>{{ $shown->gwa !== null ? number_format($shown->gwa, 2) : '-' }}</span></div>
                    <div class="ap-row"><span>Annual family income</span><span>{{ $shown->annual_family_income !== null ? number_format($shown->annual_family_income, 2) : '-' }}</span></div>
                    <div class="ap-row"><span>Year level</span><span>{{ $shown->year_level ?? '-' }}</span></div>
                    @if ($application->status !== \App\Enums\ApplicationStatus::Draft)
                        <p class="ap-muted mt-2 mb-0">These values were saved when you submitted.</p>
                    @endif
                </div>
            </div>

            <div class="box">
                <div class="box-header"><div class="box-title">Uploaded documents</div></div>
                <div class="box-body">
                    @forelse ($application->documents as $document)
                        <div class="ap-row">
                            <span>{{ $document->requiredDocument->name }}</span>
                            <span><a href="{{ $document->getUrl() }}" target="_blank" class="underline">{{ $document->file_name }}</a></span>
                        </div>
                    @empty
                        <p class="mb-0 text-textmuted">No documents uploaded.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
