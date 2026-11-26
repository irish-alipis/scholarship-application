@extends('layouts.master')

@section('title', $scholarship->name)
@section('page-title', 'Scholarship Details')

@push('styles')
    @include('applicant.partials.styles')
@endpush

@section('content')
    @php
        $isOpen = \App\Support\ScholarshipAvailability::isOpen($scholarship);
        $left = \App\Support\ScholarshipAvailability::daysLeft($scholarship);
    @endphp

    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12 xl:col-span-8">
            <div class="box">
                <div class="box-header">
                    <div class="box-title">{{ $scholarship->name }}</div>
                    @include('applicant.partials.availability-badge', ['scholarship' => $scholarship])
                </div>
                <div class="box-body">
                    <p class="text-[0.875rem]">{{ $scholarship->description }}</p>
                    <div class="ap-row"><span>Type</span><span>{{ \Illuminate\Support\Str::headline($scholarship->type->name) }}</span></div>
                    <div class="ap-row"><span>Slots</span><span>{{ $scholarship->slots }}</span></div>
                    <div class="ap-row"><span>Applications open</span><span>{{ $scholarship->start_date->format('M d, Y g:i A') }}</span></div>
                    <div class="ap-row">
                        <span>Application deadline</span>
                        <span>
                            {{ $scholarship->deadline->format('M d, Y g:i A') }}
                            @if ($isOpen && $left !== null)
                                <span class="ap-muted">({{ $left }} {{ \Illuminate\Support\Str::plural('day', $left) }} left)</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="box">
                <div class="box-header"><div class="box-title">Eligibility criteria</div></div>
                <div class="box-body">
                    @forelse ($scholarship->requirements as $requirement)
                        <div class="ap-row"><span>{{ \Illuminate\Support\Str::headline($requirement->requirement_type->name) }}</span><span>{{ $requirement->describe() }}</span></div>
                    @empty
                        <p class="mb-0 text-textmuted">This scholarship has no eligibility criteria.</p>
                    @endforelse
                </div>
            </div>

            <div class="box">
                <div class="box-header"><div class="box-title">Required documents</div></div>
                <div class="box-body">
                    @forelse ($scholarship->requiredDocuments as $document)
                        <div class="ap-row">
                            <span>{{ $document->name }}</span>
                            <span class="ap-badge {{ $document->is_required ? 'ap-badge-blue' : 'ap-badge-grey' }}">{{ $document->is_required ? 'Required' : 'Optional' }}</span>
                        </div>
                    @empty
                        <p class="mb-0 text-textmuted">No documents are required.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-span-12 xl:col-span-4">
            <div class="box">
                <div class="box-header"><div class="box-title">Apply</div></div>
                <div class="box-body">
                    @if ($activeApplication)
                        <div class="ap-note ap-note-info">
                            You already have an application for this scholarship:
                            @include('applicant.partials.status-badge', ['status' => $activeApplication->status])
                        </div>
                        <a href="{{ route('applicant.applications.show', $activeApplication) }}" class="ti-btn ti-btn-primary-full w-full">View my application</a>
                    @elseif (! $isOpen)
                        <div class="ap-note ap-note-bad">
                            @if (\App\Support\ScholarshipAvailability::label($scholarship) === 'Not yet open')
                                Applications open on {{ $scholarship->start_date->format('M d, Y') }}.
                            @else
                                Applications for this scholarship are closed.
                            @endif
                        </div>
                    @elseif (! $hasProfile)
                        <div class="ap-note ap-note-info">Set up your student profile before you apply.</div>
                        <a href="{{ route('applicant.profile.edit') }}" class="ti-btn ti-btn-primary-full w-full">Set up student profile</a>
                    @else
                        @if ($report)
                            @include('applicant.partials.eligibility', ['report' => $report])
                        @else
                            <div class="ap-note ap-note-info">Add your GWA, annual family income and year level to your <a href="{{ route('applicant.profile.edit') }}" class="underline">student profile</a> to see if you are eligible.</div>
                        @endif
                        <form method="POST" action="{{ route('applicant.applications.store', $scholarship) }}">
                            @csrf
                            <button type="submit" class="ti-btn ti-btn-primary-full w-full">Apply for this scholarship</button>
                        </form>
                    @endif
                </div>
            </div>

            <a href="{{ route('applicant.scholarships.index') }}" class="ti-btn ti-btn-light">&larr; Back to scholarships</a>
        </div>
    </div>
@endsection
