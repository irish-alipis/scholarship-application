@extends('layouts.master')

@section('title', 'Student Profile')
@section('page-title', 'Student Profile')

@push('styles')
    @include('applicant.partials.styles')
@endpush

@section('content')
    @unless ($applicant)
        <div class="ap-note ap-note-info">Fill in your student details to start applying for scholarships.</div>
    @endunless

    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12 xl:col-span-8">
            <form method="POST" action="{{ route('applicant.profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="box">
                    <div class="box-header"><div class="box-title">Student details</div></div>
                    <div class="box-body">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label" for="student_no">Student number</label>
                                <input id="student_no" name="student_no" type="text" class="form-control" value="{{ old('student_no', $applicant?->student_no) }}" required>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label" for="course">Course</label>
                                <input id="course" name="course" type="text" class="form-control" value="{{ old('course', $applicant?->course) }}" required>
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label" for="birth_date">Birth date</label>
                                <input id="birth_date" name="birth_date" type="date" class="form-control" value="{{ old('birth_date', $applicant?->birth_date?->format('Y-m-d')) }}">
                            </div>
                            <div class="col-span-12 md:col-span-6">
                                <label class="form-label" for="address">Address</label>
                                <input id="address" name="address" type="text" class="form-control" value="{{ old('address', $applicant?->address) }}">
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label" for="gwa">GWA (1.00 to 5.00)</label>
                                <input id="gwa" name="gwa" type="number" step="0.01" min="1" max="5" class="form-control" value="{{ old('gwa', $applicant?->gwa) }}">
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label" for="year_level">Year level</label>
                                <input id="year_level" name="year_level" type="number" step="1" min="1" max="6" class="form-control" value="{{ old('year_level', $applicant?->year_level) }}">
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label" for="annual_family_income">Annual family income</label>
                                <input id="annual_family_income" name="annual_family_income" type="number" step="0.01" min="0" class="form-control" value="{{ old('annual_family_income', $applicant?->annual_family_income) }}">
                            </div>
                        </div>
                        <p class="ap-muted mt-3">Scholarship eligibility is checked against these details. Your name and email are on the <a href="{{ route('profile.edit') }}" class="underline">My Profile</a> page.</p>
                        <button type="submit" class="ti-btn ti-btn-primary-full">Save profile</button>
                    </div>
                </div>
            </form>

            <form method="POST" action="{{ route('applicant.profile.password') }}">
                @csrf
                @method('PUT')

                <div class="box">
                    <div class="box-header"><div class="box-title">Change password</div></div>
                    <div class="box-body">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label" for="current_password">Current password</label>
                                <input id="current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" required>
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label" for="password">New password</label>
                                <input id="password" name="password" type="password" class="form-control" autocomplete="new-password" required>
                            </div>
                            <div class="col-span-12 md:col-span-4">
                                <label class="form-label" for="password_confirmation">Confirm new password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" required>
                            </div>
                        </div>
                        <button type="submit" class="ti-btn ti-btn-primary-full mt-4">Change password</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-span-12 xl:col-span-4">
            <div class="box">
                <div class="box-header"><div class="box-title">Account status</div></div>
                <div class="box-body">
                    @php
                        $statusColor = match ($user->status) {
                            \App\Enums\AccountStatus::Active => 'green',
                            \App\Enums\AccountStatus::Pending => 'amber',
                            default => 'red',
                        };
                    @endphp
                    <div class="ap-row"><span>Name</span><span>{{ $user->name }}</span></div>
                    <div class="ap-row"><span>Email</span><span>{{ $user->email }}</span></div>
                    <div class="ap-row"><span>Role</span><span>{{ ucfirst($user->role->value) }}</span></div>
                    <div class="ap-row">
                        <span>Account status</span>
                        <span class="ap-badge ap-badge-{{ $statusColor }}">{{ \Illuminate\Support\Str::headline($user->status->name) }}</span>
                    </div>
                    <div class="ap-row"><span>Member since</span><span>{{ $user->created_at->format('M d, Y') }}</span></div>
                </div>
            </div>
        </div>
    </div>
@endsection
