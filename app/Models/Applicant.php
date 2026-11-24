<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    // TODO (Applicant owner): setGwa(), setAnnualFamilyIncome(), setYearLevel(), hasActiveApplicationFor()
}