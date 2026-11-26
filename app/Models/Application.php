<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use App\Enums\EligibilityResult;
use App\Services\EligibilityReport;
use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    // Only the two owners are mass assignable.
    // status, eligibility and snapshot fields change only through methods.
    protected $fillable = ['applicant_id', 'scholarship_id'];

    protected $attributes = ['status' => 'draft'];

    protected function casts(): array
    {
        return [
            'status' => ApplicationStatus::class,
            'eligibility_result' => EligibilityResult::class,
            'gwa' => 'float',
            'annual_family_income' => 'float',
            'year_level' => 'integer',
            'assigned_at' => 'datetime',
            'submitted_at' => 'datetime',
            'decided_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(Applicant::class);
    }

    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function assignedEvaluator(): BelongsTo
    {
        return $this->belongsTo(Evaluator::class, 'assigned_evaluator_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ApplicationDocument::class);
    }

    public function evaluation(): HasOne
    {
        return $this->hasOne(Evaluation::class);
    }

    public function appNotifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    // ---- Applicant owner: status core ----------------------------------------------------
    // Still TODO for the other owners: assignTo(), startEvaluation(), approve(), reject().
    // They build on canTransitionTo() and changeStatus() below.

    /** Live means draft, submitted, under evaluation or approved. Rejected and cancelled are over. */
    public function isActive(): bool
    {
        return ! in_array($this->status, [ApplicationStatus::Rejected, ApplicationStatus::Cancelled], true);
    }

    /** BR-11: edit and cancel are allowed only while the application is Draft or Submitted. */
    public function isEditable(): bool
    {
        return in_array($this->status, [ApplicationStatus::Draft, ApplicationStatus::Submitted], true);
    }

    /** Required documents of the scholarship that this application has no file for yet. */
    public function missingDocuments(): \Illuminate\Support\Collection
    {
        $uploaded = $this->documents()->pluck('required_document_id');

        return $this->scholarship->requiredDocuments()
            ->where('is_required', true)
            ->whereNotIn('id', $uploaded)
            ->get();
    }

    public function hasCompleteDocuments(): bool
    {
        return $this->missingDocuments()->isEmpty();
    }

    /** The only place that decides which status may follow which. */
    public function canTransitionTo(ApplicationStatus $target): bool
    {
        $allowed = match ($this->status) {
            ApplicationStatus::Draft => [ApplicationStatus::Submitted, ApplicationStatus::Cancelled],
            ApplicationStatus::Submitted => [ApplicationStatus::UnderEvaluation, ApplicationStatus::Cancelled],
            ApplicationStatus::UnderEvaluation => [ApplicationStatus::Approved, ApplicationStatus::Rejected],
            default => [], // Approved, Rejected and Cancelled are final
        };

        return in_array($target, $allowed, true);
    }

    /** Private on purpose: the public methods are the only way a status can change. */
    private function changeStatus(ApplicationStatus $target): void
    {
        if (! $this->canTransitionTo($target)) {
            throw new DomainException(
                "An application cannot move from {$this->status->value} to {$target->value}."
            );
        }

        $this->status = $target;
    }

    /** Copy the applicant's current GWA, income and year level onto this application. */
    private function takeSnapshot(): void
    {
        $applicant = $this->applicant;

        $this->gwa = $applicant->gwa;
        $this->annual_family_income = $applicant->annual_family_income;
        $this->year_level = $applicant->year_level;
    }

    private function applyEligibility(EligibilityReport $report): void
    {
        $this->takeSnapshot();
        $this->eligibility_result = $report->getResult();
        $this->eligibility_reasons = $report->isEligible() ? null : implode("\n", $report->getReasons());
    }

    /** Draft to Submitted: snapshot the applicant, store the eligibility result, then change status. */
    public function submit(EligibilityReport $report): void
    {
        $this->changeStatus(ApplicationStatus::Submitted);
        $this->applyEligibility($report);
        $this->submitted_at = now();
        $this->save();
    }

    /** The applicant edited details of a submitted application: take the snapshot and result again. */
    public function refreshSnapshot(EligibilityReport $report): void
    {
        if (! $this->isEditable()) {
            throw new DomainException('This application can no longer be edited.');
        }

        $this->applyEligibility($report);
        $this->save();
    }

    /** Withdraw the application. Allowed from Draft and Submitted only. */
    public function cancel(): void
    {
        $this->changeStatus(ApplicationStatus::Cancelled);
        $this->cancelled_at = now();
        $this->save();
    }

    /** @return list<string> the reasons stored by the last eligibility check */
    public function eligibilityReasonList(): array
    {
        if (blank($this->eligibility_reasons)) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode("\n", $this->eligibility_reasons))));
    }
}