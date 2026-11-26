@extends('layouts.master')

@section('title', 'Search')
@section('page-title', 'Search')

@push('styles')
    @include('applicant.partials.styles')
@endpush

@section('content')
    <form method="GET" action="{{ route('applicant.search') }}" class="ap-filters">
        <input type="search" name="q" value="{{ $term }}" class="form-control" placeholder="Search scholarships, applications and notifications..." autofocus>
        <button type="submit" class="ti-btn ti-btn-primary-full">Search</button>
    </form>

    @if ($term === '')
        <div class="ap-note ap-note-info">Type a scholarship name, an application status such as "approved", or a word from a notification.</div>
    @elseif ($tooShort)
        <div class="ap-note ap-note-info">Type at least 2 characters to search.</div>
    @else
        <p class="ap-muted">Results for "{{ $term }}"</p>

        <div class="box">
            <div class="box-header">
                <div class="box-title">Scholarships ({{ $scholarships->count() }})</div>
                <a href="{{ route('applicant.scholarships.index', ['q' => $term]) }}" class="text-primary text-[0.8125rem]">See all</a>
            </div>
            <div class="box-body">
                @forelse ($scholarships as $scholarship)
                    <div class="ap-row">
                        <span style="color:inherit;"><a href="{{ route('applicant.scholarships.show', $scholarship) }}" class="underline">{{ $scholarship->name }}</a>
                            <span class="ap-muted">&middot; deadline {{ $scholarship->deadline->format('M d, Y') }}</span></span>
                        @include('applicant.partials.availability-badge', ['scholarship' => $scholarship])
                    </div>
                @empty
                    <p class="mb-0 text-textmuted">No scholarships found.</p>
                @endforelse
            </div>
        </div>

        <div class="box">
            <div class="box-header">
                <div class="box-title">My applications ({{ $applications->count() }})</div>
                <a href="{{ route('applicant.applications.index', ['q' => $term]) }}" class="text-primary text-[0.8125rem]">See all</a>
            </div>
            <div class="box-body">
                @forelse ($applications as $application)
                    <div class="ap-row">
                        <span style="color:inherit;"><a href="{{ route('applicant.applications.show', $application) }}" class="underline">{{ $application->scholarship->name }}</a></span>
                        @include('applicant.partials.status-badge', ['status' => $application->status])
                    </div>
                @empty
                    <p class="mb-0 text-textmuted">No applications found.</p>
                @endforelse
            </div>
        </div>

        <div class="box">
            <div class="box-header">
                <div class="box-title">Notifications ({{ $notifications->count() }})</div>
                <a href="{{ route('applicant.notifications.index', ['q' => $term]) }}" class="text-primary text-[0.8125rem]">See all</a>
            </div>
            <div class="box-body">
                @forelse ($notifications as $notification)
                    <div class="ap-row">
                        <span style="color:inherit;">
                            <span class="font-semibold">{{ $notification->title }}</span><br>
                            <span class="text-[0.8125rem]">{{ \Illuminate\Support\Str::limit($notification->message, 110) }}</span>
                        </span>
                        <span class="ap-muted">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="mb-0 text-textmuted">No notifications found.</p>
                @endforelse
            </div>
        </div>
    @endif
@endsection
