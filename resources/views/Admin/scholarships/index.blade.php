@extends('layouts.master')

@section('title', 'Scholarships')
@section('page-title', 'Scholarships')

@section('content')
<div class="box custom-box">
    <div class="box-header flex justify-between">
        <div class="box-title">All Scholarships</div>
        <a href="{{ route('admin.scholarships.create') }}" class="ti-btn ti-btn-primary !py-1 !px-2 !text-[0.75rem]">Add Scholarship</a>
    </div>
    <div class="box-body">
        <div class="table-responsive">
            <table class="table whitespace-nowrap table-bordered min-w-full">
                <thead>
                    <tr class="border-b border-defaultborder">
                        <th class="text-start">Name</th>
                        <th class="text-start">Type</th>
                        <th class="text-start">Slots</th>
                        <th class="text-start">Application Period</th>
                        <th class="text-start">Status</th>
                        <th class="text-start">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scholarships as $s)
                        @php
                            $color = match($s->status->value) {
                                'open' => 'success',
                                'closed' => 'danger',
                                'inactive' => 'warning',
                                default => 'secondary',
                            };
                        @endphp
                        <tr class="border-b border-defaultborder">
                            <th scope="row">{{ $s->name }}</th>
                            <td>{{ $s->type }}</td>
                            <td>{{ $s->slots }}</td>
                            <td>{{ $s->start_date->format('M d, Y') }} - {{ $s->deadline->format('M d, Y') }}</td>
                            <td><span class="badge bg-{{ $color }}/10 text-{{ $color }}">{{ ucfirst($s->status->value) }}</span></td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.scholarships.show', $s) }}" class="text-primary text-[.875rem] leading-none"><i class="ri-eye-line"></i></a>
                                    <a href="{{ route('admin.scholarships.edit', $s) }}" class="text-info text-[.875rem] leading-none"><i class="ri-edit-line"></i></a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No scholarships yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $scholarships->links() }}</div>
    </div>
</div>
@endsection