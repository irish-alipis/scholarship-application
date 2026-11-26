<?php

namespace App\Models;

use App\Enums\ScholarshipStatus;
use App\Enums\ScholarshipType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scholarship extends Model
{
    protected $fillable = [
        'name',
        'description',
        'type',
        'slots',
        'start_date',
        'deadline',
        'status',
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

    public function isOpen(): bool
    {
        return $this->status === ScholarshipStatus::Open
            && now()->between($this->start_date, $this->deadline);
    }

    public function hasAvailableSlots(): bool
    {
        $taken = $this->applications()->where('status', 'approved')->count();

        return $taken < $this->slots;
    }

    public function open(): bool
    {
        if ($this->deadline->isPast()) {
            return false;
        }

        $this->update(['status' => ScholarshipStatus::Open]);

        return true;
        }

    public function close(): void
    {
        $this->update(['status' => ScholarshipStatus::Closed]);
    }

    public function deactivate(): void
    {
        $this->update(['status' => ScholarshipStatus::Inactive]);
    }
}