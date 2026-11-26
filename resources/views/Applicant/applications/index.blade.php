@extends('layouts.master')

@section('title', 'My Applications')
@section('page-title', 'My Applications')

@push('styles')
    @include('applicant.partials.styles')
@endpush

@section('content')
    @unless ($hasProfile)
        <div class="ap-note ap-note-info">
            Set up your <a href="{{ route('applicant.profile.edit') }}" class="underline">student profile</a> before you apply for a scholarship.
        </div>
    @endunless

    <form method="GET" action="{{ route('applicant.applications.index') }}" class="ap-filters">
        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Search by scholarship name...">

        <select name="status" class="form-control">
            <option value="">All statuses</option>
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" @selected(($filters['status'] ?? '') === $status->value)>{{ \Illuminate\Support\Str::headline($status->name) }}</option>
            @endforeach
        </select>

        <button type="submit" class="ti-btn ti-btn-primary-full">Filter</button>
        <a href="{{ route('applicant.applications.index') }}" class="ti-btn ti-btn-light">Reset</a>
        <a href="{{ route('applicant.scholarships.index') }}" class="ti-btn ti-btn-light ms-auto">Browse scholarships</a>
    </form>

    <div class="box">
        <div class="box-body overflow-x-auto">
            <table class="table whitespace-nowrap min-w-full">
                <thead>
                    <tr class="border-b border-defaultborder">
                        <th class="py-2 text-start">Scholarship</th>
                        <th class="py-2 text-start">Status</th>
                        <th class="py-2 text-start">Eligibility</th>
                        <th class="py-2 text-start">Submitted</th>
                        <th class="py-2 text-start">Deadline</th>
                        <th class="py-2 text-start">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse (($applications ?? collect()) as $application)
                        <tr class="border-b border-defaultborder">
                            <td class="py-2">{{ $application->scholarship->name }}</td>
                            <td class="py-2">@include('applicant.partials.status-badge', ['status' => $application->status])</td>
                            <td class="py-2">{{ $application->eligibility_result ? \Illuminate\Support\Str::headline($application->eligibility_result->name) : '-' }}</td>
                            <td class="py-2">{{ $application->submitted_at?->format('M d, Y') ?? '-' }}</td>
                            <td class="py-2">{{ $application->scholarship->deadline->format('M d, Y') }}</td>
                            <td class="py-2">
                                <div class="ap-actions">
                                    <a href="{{ route('applicant.applications.show', $application) }}" class="ti-btn ti-btn-sm ti-btn-light">View</a>
                                    @if ($application->status === \App\Enums\ApplicationStatus::Draft && \App\Support\ScholarshipAvailability::isOpen($application->scholarship))
                                        <a href="{{ route('applicant.applications.edit', $application) }}" class="ti-btn ti-btn-sm ti-btn-primary-full">Continue</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td class="py-3 text-textmuted" colspan="6">No applications found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($applications)
        <div class="ap-pagination">{{ $applications->links() }}</div>
    @endif
@endsection
