<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AccountStatus;
use App\Enums\Action;
use App\Enums\EntityType;
use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->whereNotIn('status', [
                AccountStatus::Pending->value,
                AccountStatus::Rejected->value,
            ])
            ->when($request->role, fn($q, $role) => $q->where('role', $role))
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        $pendingRequests = User::where('role', Role::Evaluator->value)
            ->where('status', AccountStatus::Pending->value)
            ->latest()
            ->get();

        return view('Admin.users.index', compact('users', 'pendingRequests'));
    }

    public function create()
    {
        return view('Admin.users.create');
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', Rule::enum(Role::class)],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['status'] = AccountStatus::Active;

        $user = User::create($data);
        app(AuditLogger::class)->log(auth()->user(), Action::Created, EntityType::User, $user->id, 'Created user ' . $user->email);

        if ($user->role === Role::Evaluator) {
            \App\Models\Evaluator::firstOrCreate(
                ['user_id' => $user->id],
                ['employee_no' => 'EMP-' . str_pad($user->id, 4, '0', STR_PAD_LEFT)]
            );
        }

        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        return view('Admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::enum(Role::class)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']); // blank, which keeps the old password
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $newRole = Role::from($data['role']);
        unset($data['role']);

        $user->update($data);

        if ($user->role !== $newRole) {
            $user->changeRole($newRole);
        }

        app(AuditLogger::class)->log(auth()->user(), Action::Updated, EntityType::User, $user->id, 'Updated user ' . $user->email);

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function updateStatus(Request $request, User $user)
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(['active', 'suspended', 'deactivated'])],
        ]);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own status.');
        }

        match ($data['status']) {
            'active' => $user->activate(),
            'suspended' => $user->suspend(),
            'deactivated' => $user->deactivate(),
        };

        if ($user->role === Role::Evaluator && $data['status'] === 'active') {
            \App\Models\Evaluator::firstOrCreate(
                ['user_id' => $user->id],
                ['employee_no' => 'EMP-' . str_pad($user->id, 4, '0', STR_PAD_LEFT)]
            );
        }

        app(AuditLogger::class)->log(
            auth()->user(),
            $data['status'] === 'deactivated' ? Action::Deactivated : Action::Updated,
            EntityType::User,
            $user->id,
            'Status of ' . $user->email . ' set to ' . $data['status']
        );

        return back()->with('success', 'Status updated.');
    }
}
