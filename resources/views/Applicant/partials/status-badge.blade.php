{{-- Pass: $status (an ApplicationStatus) --}}
@php
    $badgeColor = match ($status) {
        \App\Enums\ApplicationStatus::Draft, \App\Enums\ApplicationStatus::Cancelled => 'grey',
        \App\Enums\ApplicationStatus::Submitted => 'blue',
        \App\Enums\ApplicationStatus::UnderEvaluation => 'amber',
        \App\Enums\ApplicationStatus::Approved => 'green',
        \App\Enums\ApplicationStatus::Rejected => 'red',
    };
@endphp
<span class="ap-badge ap-badge-{{ $badgeColor }}">{{ \Illuminate\Support\Str::headline($status->name) }}</span>
