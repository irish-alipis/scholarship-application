@extends('layouts.master')

@section('title', 'Applicant Dashboard')
@section('page-title', 'My Dashboard')

@section('content')
    @unless ($profileComplete)
        <div class="app-flash app-flash-error" role="alert">
            Please complete your profile (GWA, annual family income and year level) before you apply.
        </div>
    @endunless

    <div class="grid grid-cols-12 gap-x-6">
        @foreach ($cards as $card)
            <div class="col-span-12 md:col-span-6 xl:col-span-3">
                <div class="box">
                    <div class="box-body">
                        <p class="mb-1 text-[0.8125rem] text-textmuted">{{ $card['label'] }}</p>
                        <h4 class="mb-0 font-semibold text-[1.5rem]">{{ $card['value'] }}</h4>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12 xl:col-span-8">
            <div class="box">
                <div class="box-header">
                    <div class="box-title">My applications</div>
                </div>
                <div class="box-body">
                    <table class="table whitespace-nowrap min-w-full">
                        <thead>
                            <tr class="border-b border-defaultborder">
                                <th class="py-2 text-start">Scholarship</th>
                                <th class="py-2 text-start">Status</th>
                                <th class="py-2 text-start">Submitted</th>
                                <th class="py-2 text-start">Eligibility</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($applications as $application)
                                <tr class="border-b border-defaultborder">
                                    <td class="py-2">{{ $application->scholarship->name }}</td>
                                    <td class="py-2">{{ \Illuminate\Support\Str::headline($application->status->name) }}</td>
                                    <td class="py-2">{{ $application->submitted_at?->format('M d, Y') ?? '-' }}</td>
                                    <td class="py-2">{{ $application->eligibility_result ? \Illuminate\Support\Str::headline($application->eligibility_result->name) : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="py-3 text-textmuted" colspan="4">You have no applications yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-span-12 xl:col-span-4">
            <div class="box">
                <div class="box-header">
                    <div class="box-title">Latest notifications</div>
                </div>
                <div class="box-body">
                    @forelse ($notifications as $notification)
                        <div class="mb-3">
                            <div class="font-semibold text-[0.875rem]">{{ $notification->title }}</div>
                            <div class="text-[0.8125rem]">{{ $notification->message }}</div>
                            <div class="text-[0.75rem] text-textmuted">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                    @empty
                        <p class="mb-0 text-textmuted">No notifications yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
