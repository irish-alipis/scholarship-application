<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AccountStatus;
use App\Enums\Action;
use App\Enums\EntityType;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogger;

class EvaluatorRequestController extends Controller
{

    public function approve(User $user)
    {
        abort_unless($user->status === AccountStatus::Pending, 404);

        $user->approve();
        \App\Models\Evaluator::firstOrCreate(
            ['user_id' => $user->id],
            ['employee_no' => 'EMP-' . str_pad($user->id, 4, '0', STR_PAD_LEFT)]
        );

        app(AuditLogger::class)->log(auth()->user(), Action::Approved, EntityType::User, $user->id, 'Approved evaluator ' . $user->email);

        return back()->with('success', $user->name . ' was approved.');
    }

    public function reject(User $user)
    {
        abort_unless($user->status === AccountStatus::Pending, 404);

        $user->reject();

        app(AuditLogger::class)->log(auth()->user(), Action::Rejected, EntityType::User, $user->id, 'Rejected evaluator ' . $user->email);

        return back()->with('success', $user->name . ' was rejected.');
    }
}
