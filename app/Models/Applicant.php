<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use InvalidArgumentException;

class Applicant extends Model
{
    protected $fillable = [
        'user_id', 'student_no', 'course', 'address', 'birth_date',
        'gwa', 'annual_family_income', 'year_level',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'gwa' => 'float',
            'annual_family_income' => 'float',
            'year_level' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    /** GWA uses the 1.00 (best) to 5.00 scale. Sets the value; the caller saves. */
    public function setGwa(float $gwa): void
    {
        if ($gwa < 1.0 || $gwa > 5.0) {
            throw new InvalidArgumentException('GWA must be between 1.00 and 5.00.');
        }

        $this->gwa = round($gwa, 2);
    }

    public function setAnnualFamilyIncome(float $income): void
    {
        if ($income < 0) {
            throw new InvalidArgumentException('Annual family income cannot be negative.');
        }

        $this->annual_family_income = round($income, 2);
    }

    public function setYearLevel(int $yearLevel): void
    {
        if ($yearLevel < 1 || $yearLevel > 6) {
            throw new InvalidArgumentException('Year level must be between 1 and 6.');
        }

        $this->year_level = $yearLevel;
    }

    /**
     * True when this applicant already has a live application (draft, submitted,
     * under evaluation or approved) for the scholarship. Rejected and cancelled ones do not count.
     * Pass $except to ignore the application that is being submitted right now.
     */
    public function hasActiveApplicationFor(Scholarship $scholarship, ?Application $except = null): bool
    {
        return $this->applications()
            ->where('scholarship_id', $scholarship->id)
            ->when($except, fn ($query) => $query->where('id', '!=', $except->id))
            ->whereNotIn('status', [ApplicationStatus::Rejected->value, ApplicationStatus::Cancelled->value])
            ->exists();
    }
}