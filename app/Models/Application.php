<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use App\Enums\EligibilityResult;
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

    // TODO (Applicant/Evaluator/Admin owners): submit(), assignTo(), startEvaluation(),
    // approve(), reject(), cancel(), isActive(), isEditable(), hasCompleteDocuments(),
    // canTransitionTo(), changeStatus() (private), takeSnapshot() (private)
}