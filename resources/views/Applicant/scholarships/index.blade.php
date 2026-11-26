@extends('layouts.master')

@section('title', 'Scholarships')
@section('page-title', 'Scholarships')

@push('styles')
    @include('applicant.partials.styles')
@endpush

@section('content')
    <form method="GET" action="{{ route('applicant.scholarships.index') }}" class="ap-filters">
        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Search scholarships...">

        <select name="type" class="form-control">
            <option value="">All types</option>
            @foreach ($types as $type)
                <option value="{{ $type->value }}" @selected(($filters['type'] ?? '') === $type->value)>{{ \Illuminate\Support\Str::headline($type->name) }}</option>
            @endforeach
        </select>

        <select name="availability" class="form-control">
            <option value="">Open and closed</option>
            <option value="open" @selected(($filters['availability'] ?? '') === 'open')>Open for applications</option>
            <option value="closed" @selected(($filters['availability'] ?? '') === 'closed')>Closed</option>
        </select>

        <button type="submit" class="ti-btn ti-btn-primary-full">Filter</button>
        <a href="{{ route('applicant.scholarships.index') }}" class="ti-btn ti-btn-light">Reset</a>
    </form>

    <div class="grid grid-cols-12 gap-x-6">
        @forelse ($scholarships as $scholarship)
            @php $mine = $myApplications->get($scholarship->id); @endphp
            <div class="col-span-12 md:col-span-6 xl:col-span-4">
                <div class="box">
                    <div class="box-body">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h5 class="font-semibold mb-0">{{ $scholarship->name }}</h5>
                            @include('applicant.partials.availability-badge', ['scholarship' => $scholarship])
                        </div>
                        <p class="ap-muted mb-2">{{ \Illuminate\Support\Str::headline($scholarship->type->name) }} &middot; {{ $scholarship->slots }} slots</p>
                        <p class="text-[0.875rem] mb-3">{{ \Illuminate\Support\Str::limit($scholarship->description, 140) }}</p>
                        <p class="ap-muted mb-3">
                            Deadline: {{ $scholarship->deadline->format('M d, Y g:i A') }}
                            @php $left = \App\Support\ScholarshipAvailability::daysLeft($scholarship); @endphp
                            @if ($left !== null && \App\Support\ScholarshipAvailability::isOpen($scholarship))
                                ({{ $left }} {{ \Illuminate\Support\Str::plural('day', $left) }} left)
                            @endif
                        </p>
                        <div class="ap-actions">
                            <a href="{{ route('applicant.scholarships.show', $scholarship) }}" class="ti-btn ti-btn-primary-full">View details</a>
                            @if ($mine)
                                <a href="{{ route('applicant.applications.show', $mine) }}" class="ap-badge ap-badge-blue">Applied: {{ \Illuminate\Support\Str::headline($mine->status->name) }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-12">
                <div class="box"><div class="box-body text-textmuted">No scholarships match your search.</div></div>
            </div>
        @endforelse
    </div>

    <div class="ap-pagination">{{ $scholarships->links() }}</div>
@endsection
