{{-- Pass: $scholarship. Shows Open, Closed or Not yet open. --}}
<span class="ap-badge ap-badge-{{ \App\Support\ScholarshipAvailability::color($scholarship) }}">{{ \App\Support\ScholarshipAvailability::label($scholarship) }}</span>
