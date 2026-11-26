<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Action;
use App\Enums\EntityType;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'action' => ['nullable', Rule::enum(Action::class)],
            'entity_type' => ['nullable', Rule::enum(EntityType::class)],
            'user_id' => ['nullable', 'exists:users,id'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        $logs = AuditLog::with('user')
            ->when($request->action, fn ($q, $v) => $q->where('action', $v))
            ->when($request->entity_type, fn ($q, $v) => $q->where('entity_type', $v))
            ->when($request->user_id, fn ($q, $v) => $q->where('user_id', $v))
            ->when($request->from, fn ($q, $v) => $q->where('created_at', '>=', $v . ' 00:00:00'))
            ->when($request->to, fn ($q, $v) => $q->where('created_at', '<=', $v . ' 23:59:59'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $users = User::orderBy('name')->get(['id', 'name']);

        return view('Admin.audit-logs.index', compact('logs', 'users'));
    }
}