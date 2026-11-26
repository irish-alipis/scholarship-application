@extends('layouts.master')

@section('title', 'Users')
@section('page-title', 'Users')

@section('content')
    <div class="grid grid-cols-12 gap-x-6">
        <div class="xl:col-span-8 col-span-12">
            <div class="box custom-box">
                <div class="box-header flex justify-between">
                    <div class="box-title">All Users</div>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.users.index') }}"
                            class="ti-btn ti-btn-light !py-1 !px-2 !text-[0.75rem]">All</a>
                        <a href="{{ route('admin.users.index', ['role' => 'applicant']) }}"
                            class="ti-btn ti-btn-light !py-1 !px-2 !text-[0.75rem]">Applicants</a>
                        <a href="{{ route('admin.users.index', ['role' => 'evaluator']) }}"
                            class="ti-btn ti-btn-light !py-1 !px-2 !text-[0.75rem]">Evaluators</a>
                        <a href="{{ route('admin.users.create') }}"
                            class="ti-btn ti-btn-primary !py-1 !px-2 !text-[0.75rem]">Add User</a>
                    </div>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table whitespace-nowrap table-bordered min-w-full">
                            <thead>
                                <tr class="border-b border-defaultborder">
                                    <th class="text-start">Name</th>
                                    <th class="text-start">Email</th>
                                    <th class="text-start">Role</th>
                                    <th class="text-start">Registered</th>
                                    <th class="text-start">Status</th>
                                    <th class="text-start">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr class="border-b border-defaultborder">
                                        <th scope="row">{{ $user->name }}</th>
                                        <td>{{ $user->email }}</td>
                                        <td><span
                                                class="badge bg-primary/10 text-primary">{{ ucfirst($user->role->value) }}</span>
                                        </td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @if($user->id !== auth()->id())
                                                <form method="POST" action="{{ route('admin.users.status', $user) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status" class="form-control !py-1 !text-[.75rem]"
                                                        onchange="this.form.submit()">
                                                        @foreach(['active', 'suspended', 'deactivated'] as $s)
                                                            <option value="{{ $s }}" @selected($user->status->value === $s)>
                                                                {{ ucfirst($s) }}</option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            @else
                                                <span class="badge bg-success/10 text-success">{{ ucfirst($user->status->value) }}
                                                    (you)</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                                class="text-info text-[.875rem] leading-none"><i class="ri-edit-line"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No users found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $users->links() }}</div>
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 col-span-12">
            <div class="box custom-box">

                <div class="box-header flex justify-between">
                    <div class="box-title order-first md:order-none">Pending Evaluator Requests</div>
                    <span class="badge bg-warning/10 text-warning">{{ $pendingRequests->count() }}</span>
                </div>
                <div class="box-body">
                    @forelse($pendingRequests as $req)
                        <div class="border-b border-defaultborder pb-3 mb-3">
                            <div class="font-semibold">{{ $req->name }}</div>
                            <small class="text-muted">{{ $req->email }}</small><br>
                            <small class="text-muted">Requested {{ $req->created_at->format('M d, Y') }}</small>
                            <div class="flex gap-2 mt-2">
                                <form method="POST" action="{{ route('admin.evaluator-requests.approve', $req) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="ti-btn ti-btn-success !py-1 !px-2 !text-[0.75rem]">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.evaluator-requests.reject', $req) }}"
                                    data-confirm="Are you sure you want to reject this request?">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="ti-btn ti-btn-danger !py-1 !px-2 !text-[0.75rem]">Reject</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted mb-0">No pending requests.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection