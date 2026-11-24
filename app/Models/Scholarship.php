<?php

namespace App\Models;

use App\Enums\ScholarshipStatus;
use App\Enums\ScholarshipType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scholarship extends Model
{
    protected $fillable = [
        'name', 'description', 'type', 'slots', 'start_date', 'deadline', 'status',
    ];

    protected $attributes = ['status' => 'draft'];

    protected function casts(): array
    {
        return [
            'type' => ScholarshipType::class,
            'status' => ScholarshipStatus::class,
            'slots' => 'integer',
            'start_date' => 'datetime',
            'deadline' => 'datetime',
        ];
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(ScholarshipRequirement::class);
    }

    public function requiredDocuments(): HasMany
    {
        return $this->hasMany(RequiredDocument::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    // TODO (Admin owner): isOpen(), hasAvailableSlots(), open(), close(), deactivate()
}