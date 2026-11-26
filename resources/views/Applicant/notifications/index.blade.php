@extends('layouts.master')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@push('styles')
    @include('applicant.partials.styles')
@endpush

@section('content')
    <form method="GET" action="{{ route('applicant.notifications.index') }}" class="ap-filters">
        <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Search notifications...">

        <select name="filter" class="form-control">
            <option value="all" @selected($filter === 'all')>All</option>
            <option value="unread" @selected($filter === 'unread')>Unread ({{ $unreadCount }})</option>
            <option value="read" @selected($filter === 'read')>Read</option>
        </select>

        <button type="submit" class="ti-btn ti-btn-primary-full">Filter</button>
        <a href="{{ route('applicant.notifications.index') }}" class="ti-btn ti-btn-light">Reset</a>

        @if ($unreadCount > 0)
            <button type="submit" form="read-all-form" class="ti-btn ti-btn-light ms-auto">Mark all as read</button>
        @endif
    </form>
    <form id="read-all-form" method="POST" action="{{ route('applicant.notifications.read-all') }}">@csrf</form>

    <div class="box">
        <div class="box-body">
            @forelse ($notifications as $notification)
                <div class="ap-row" style="align-items:flex-start;">
                    <span style="color:inherit;">
                        <span class="font-semibold text-[0.875rem]">
                            {{ $notification->title }}
                            @unless ($notification->is_read)
                                <span class="ap-badge ap-badge-blue">New</span>
                            @endunless
                        </span><br>
                        <span class="text-[0.8125rem]">{{ $notification->message }}</span><br>
                        <span class="ap-muted">
                            {{ $notification->created_at->diffForHumans() }}
                            @if ($notification->application)
                                &middot; <a href="{{ route('applicant.applications.show', $notification->application) }}" class="underline">View application</a>
                            @endif
                        </span>
                    </span>
                    <span class="ap-actions">
                        @if ($notification->is_read)
                            <form method="POST" action="{{ route('applicant.notifications.unread', $notification) }}">
                                @csrf
                                <button type="submit" class="ti-btn ti-btn-sm ti-btn-light">Mark as unread</button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('applicant.notifications.read', $notification) }}">
                                @csrf
                                <button type="submit" class="ti-btn ti-btn-sm ti-btn-light">Mark as read</button>
                            </form>
                        @endif
                    </span>
                </div>
            @empty
                <p class="mb-0 text-textmuted">No notifications found.</p>
            @endforelse
        </div>
    </div>

    <div class="ap-pagination">{{ $notifications->links() }}</div>
@endsection
