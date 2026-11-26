@extends('layouts.master')

@section('title', 'Audit Logs')
@section('page-title', 'Audit Logs')

@section('content')
    <div class="box custom-box">
        <div class="box-header">
            <div class="box-title">Activity Log</div>
        </div>
        <div class="box-body">
            <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="mb-4">
                <div class="grid grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-2 col-span-6">
                        <label class="form-label">Action</label>
                        <select name="action" class="form-control">
                            <option value="">All</option>
                            @foreach(\App\Enums\Action::cases() as $a)
                                <option value="{{ $a->value }}" @selected(request('action') === $a->value)>{{ $a->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2 col-span-6">
                        <label class="form-label">Entity</label>
                        <select name="entity_type" class="form-control">
                            <option value="">All</option>
                            @foreach(\App\Enums\EntityType::cases() as $e)
                                <option value="{{ $e->value }}" @selected(request('entity_type') === $e->value)>{{ $e->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2 col-span-6">
                        <label class="form-label">User</label>
                        <select name="user_id" class="form-control">
                            <option value="">All</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" @selected((int) request('user_id') === $u->id)>{{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-2 col-span-6">
                        <label class="form-label">From</label>
                        <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                    </div>
                    <div class="md:col-span-2 col-span-6">
                        <label class="form-label">To</label>
                        <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                    </div>
                    <div class="md:col-span-2 col-span-12 flex gap-2">
                        <button type="submit" class="ti-btn ti-btn-primary w-full">Filter</button>
                        <a href="{{ route('admin.audit-logs.index') }}" class="ti-btn ti-btn-light">Reset</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table whitespace-nowrap table-bordered min-w-full">
                    <thead>
                        <tr class="border-b border-defaultborder">
                            <th class="text-start">When</th>
                            <th class="text-start">Who</th>
                            <th class="text-start">Action</th>
                            <th class="text-start">Entity</th>
                            <th class="text-start">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr class="border-b border-defaultborder">
                                <td>{{ $log->created_at->format('M d, Y h:i A') }}</td>
                                <td>{{ $log->user?->name ?? 'Deleted user' }}</td>
                                <td><span class="badge bg-primary/10 text-primary">{{ $log->action->label() }}</span></td>
                                <td>{{ $log->entity_type->label() }} #{{ $log->entity_id }}</td>
                                <td class="whitespace-normal">{{ $log->details }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No audit logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $logs->links() }}</div>
        </div>
    </div>
@endsection