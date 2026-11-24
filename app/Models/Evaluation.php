<?php

namespace App\Models;

use App\Enums\Decision;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    // score, decision, rejection_reason and is_finalized change only through setScore() / finalize().
    protected $fillable = ['application_id', 'evaluator_id', 'remarks'];

    protected $attributes = ['is_finalized' => false];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'decision' => Decision::class,
            'is_finalized' => 'boolean',
            'evaluated_at' => 'datetime',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(Evaluator::class);
    }

    // TODO (Evaluator owner): setScore(), finalize(), isFinalized()
}