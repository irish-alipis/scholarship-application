{{-- Pass: $report (EligibilityReport) --}}
@if ($report->isEligible())
    <div class="ap-note ap-note-ok">You meet every requirement of this scholarship.</div>
@else
    <div class="ap-note ap-note-bad">
        You do not meet every requirement yet:
        <ul style="margin:.35rem 0 0;padding-left:1.1rem;">
            @foreach ($report->getReasons() as $reason)
                <li>{{ $reason }}</li>
            @endforeach
        </ul>
    </div>
@endif
