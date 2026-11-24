<?php

namespace App\Services;

use App\Enums\Action;
use App\Enums\EntityType;
use App\Models\AuditLog;
use App\Models\User;

class AuditLogger
{
    public function log(
        User $user,
        Action $action,
        EntityType $entityType,
        ?int $entityId,
        ?string $details = null
    ): AuditLog {
        return AuditLog::create([
            'user_id' => $user->id,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'details' => $details,
        ]);
    }
}