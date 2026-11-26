@extends('layouts.master')

@section('title', 'Applications')
@section('page-title', 'Applications')

@php
    $badge = fn($s) => match ($s->value) {
        'submitted' => 'info',
        'under_evaluation' => 'warning',
        'approved' => 'success',
        'rejected' => 'danger',
        default => 'secondary',
    };
@endphp

@section('content')
    <div class="grid grid-cols-12 gap-x-6">
        <div class="md:col-span-4 col-span-12">
            <div class="box custom-box">
                <div class="box-body">
                    <small>Pending</small>
                    <div class="text-xl font-semibold">{{ $counts['pending'] }}</div>
                </div>
            </div>
        </div>

        <div class="md:col-span-4 col-span-12">
            <div class="box custom-box">
                <div class="box-body">
                    <small>Approved</small>
                    <div class="text-xl font-semibold">{{ $counts['approved'] }}</div>
                </div>
            </div>
        </div>

        <div class="md:col-span-4 col-span-12">
            <div class="box custom-box">
                <div class="box-body">
                    <small>Rejected</small>
                    <div class="text-xl font-semibold">{{ $counts['rejected'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="box custom-box">
        <div class="box-header flex justify-between">
            <div class="box-title">All Applications</div>
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('admin.applications.index') }}"
                    class="ti-btn ti-btn-light !py-1 !px-2 !text-[0.75rem]">All</a>
                @foreach(\App\Enums\ApplicationStatus::cases() as $st)
                    @continue($st === \App\Enums\ApplicationStatus::Draft)
                    <a href="{{ route('admin.applications.index', ['status' => $st->value]) }}"
                        class="ti-btn ti-btn-light !py-1 !px-2 !text-[0.75rem]">{{ $st->label() }}</a>
                @endforeach
            </div>
        </div>
        <div class="box-body">
            <div class="table-responsive">
                <table class="table whitespace-nowrap table-bordered min-w-full">
                    <thead>
                        <tr class="border-b border-defaultborder">
                            <th class="text-start">Applicant</th>
                            <th class="text-start">Scholarship</th>
                            <th class="text-start">Submitted</th>
                            <th class="text-start">Evaluator</th>
                            <th class="text-start">Status</th>
                            <th class="text-start">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $app)
                            <tr class="border-b border-defaultborder">
                                <th scope="row">{{ $app->applicant->user->name }}</th>
                                <td>{{ $app->scholarship->name }}</td>
                                <td>{{ $app->submitted_at?->format('M d, Y') ?? '-' }}</td>
                                <td>{{ $app->evaluator?->user->name ?? 'Unassigned' }}</td>
                                <td><span
                                        class="badge bg-{{ $badge($app->status) }}/10 text-{{ $badge($app->status) }}">{{ $app->status->label() }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.applications.show', $app) }}"
                                        class="text-primary text-[.875rem] leading-none"><i class="ri-eye-line"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No applications found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $applications->links() }}</div>
        </div>
    </div>
@endsection