@extends('layouts.master')

@section('title', 'Reports')
@section('page-title', 'Reports')

@section('content')
    <div class="grid grid-cols-12 gap-x-6">
        <div class="md:col-span-4 col-span-12">
            <div class="box custom-box">
                <div class="box-body">
                    <small>Total Applicants</small>
                    <div class="text-xl font-semibold">{{ $totals['applicants'] }}</div>
                </div>
            </div>
        </div>
        <div class="md:col-span-4 col-span-12">
            <div class="box custom-box">
                <div class="box-body">
                    <small>Total Evaluators</small>
                    <div class="text-xl font-semibold">{{ $totals['evaluators'] }}</div>
                </div>
            </div>
        </div>
        <div class="md:col-span-4 col-span-12">
            <div class="box custom-box">
                <div class="box-body">
                    <small>Submitted Applications</small>
                    <div class="text-xl font-semibold">{{ $totals['submitted'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6">
        <div class="xl:col-span-4 col-span-12">
            <div class="box custom-box">
                <div class="box-header">
                    <div class="box-title">Applications by Status</div>
                </div>
                <div class="box-body">
                    <table class="table whitespace-nowrap table-bordered min-w-full">
                        <tbody>
                            @foreach(\App\Enums\ApplicationStatus::cases() as $st)
                                <tr class="border-b border-defaultborder">
                                    <th scope="row">{{ $st->label() }}</th>
                                    <td>{{ $statusCounts[$st->value] ?? 0 }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 col-span-12">
            <div class="box custom-box">
                <div class="box-header">
                    <div class="box-title">Applications by Scholarship</div>
                </div>
                <div class="box-body">
                    <table class="table whitespace-nowrap table-bordered min-w-full">
                        <tbody>
                            @forelse($byScholarship as $s)
                                <tr class="border-b border-defaultborder">
                                    <th scope="row">{{ $s->name }}</th>
                                    <td>{{ $s->applications_count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center">No scholarships yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 col-span-12">
            <div class="box custom-box">
                <div class="box-header">
                    <div class="box-title">Evaluation Statistics</div>
                </div>
                <div class="box-body">
                    <table class="table whitespace-nowrap table-bordered min-w-full">
                        <tbody>
                            <tr class="border-b border-defaultborder">
                                <th scope="row">Total evaluations</th>
                                <td>{{ $evaluationStats['total'] }}</td>
                            </tr>
                            <tr class="border-b border-defaultborder">
                                <th scope="row">Finalized</th>
                                <td>{{ $evaluationStats['finalized'] }}</td>
                            </tr>
                            <tr class="border-b border-defaultborder">
                                <th scope="row">Average score</th>
                                <td>{{ $evaluationStats['average_score'] }}</td>
                            </tr>
                            <tr class="border-b border-defaultborder">
                                <th scope="row">Approved</th>
                                <td>{{ $evaluationStats['approved'] }}</td>
                            </tr>
                            <tr class="border-b border-defaultborder">
                                <th scope="row">Rejected</th>
                                <td>{{ $evaluationStats['rejected'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="box custom-box">
        <div class="box-header flex justify-between">
            <div class="box-title">{{ $title }}</div>
            <a href="{{ route('admin.reports.export', request()->query()) }}"
                class="ti-btn ti-btn-success !py-1 !px-2 !text-[0.75rem]">Download CSV</a>
        </div>
        <div class="box-body">
            <form method="GET" action="{{ route('admin.reports.index') }}" class="mb-4">
                <div class="grid grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-3 col-span-12">
                        <label class="form-label">Report</label>
                        <select name="type" class="form-control">
                            <option value="application" @selected(request('type') !== 'evaluation')>Application Report
                            </option>
                            <option value="evaluation" @selected(request('type') === 'evaluation')>Evaluation Report</option>
                        </select>
                    </div>
                    <div class="md:col-span-3 col-span-12">
                        <label class="form-label">From</label>
                        <input type="date" name="from" class="form-control" value="{{ $from->format('Y-m-d') }}">
                    </div>
                    <div class="md:col-span-3 col-span-12">
                        <label class="form-label">To</label>
                        <input type="date" name="to" class="form-control" value="{{ $to->format('Y-m-d') }}">
                    </div>
                    <div class="md:col-span-3 col-span-12">
                        <button type="submit" class="ti-btn ti-btn-primary w-full">Generate</button>
                    </div>
                </div>
            </form>

            <small class="text-muted">{{ $from->format('M d, Y') }} to {{ $to->format('M d, Y') }}: {{ count($rows) }}
                record(s)</small>

            <div class="table-responsive mt-2">
                <table class="table whitespace-nowrap table-bordered min-w-full">
                    @if(count($rows))
                        <thead>
                            <tr class="border-b border-defaultborder">
                                @foreach(array_keys($rows[0]) as $heading)
                                    <th class="text-start">{{ $heading }}</th>
                                @endforeach
                            </tr>
                        </thead>
                    @endif
                    <tbody>
                        @forelse($rows as $row)
                            <tr class="border-b border-defaultborder">
                                @foreach($row as $value)
                                    <td>{{ $value }}</td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td class="text-center">No records in this date range.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection