<?php

namespace App\Services;

use App\Enums\Action;
use App\Enums\EntityType;
use App\Models\Application;
use App\Models\Evaluator;
use App\Models\User;

class ApplicationService
{
    public function __construct(private AuditLogger $logger)
    {
    }

    public function assignEvaluator(Application $application, Evaluator $evaluator, User $admin): void
    {
        $wasAssigned = $application->assigned_evaluator_id !== null;

        $application->assignTo($evaluator);

        $this->logger->log(
            $admin,
            $wasAssigned ? Action::Reassigned : Action::Assigned,
            EntityType::Application,
            $application->id,
            'Evaluator: ' . $evaluator->user->name
        );
    }
}