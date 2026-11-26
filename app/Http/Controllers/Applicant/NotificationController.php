<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/** Status notifications for the signed-in applicant. */
class NotificationController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'filter' => ['nullable', 'in:all,unread,read'],
        ]);

        $user = $request->user();
        $filter = $filters['filter'] ?? 'all';

        $notifications = $user->appNotifications()
            ->with('application.scholarship')
            ->when($filters['q'] ?? null, function ($query, $term) {
                $query->where(function ($query) use ($term) {
                    $query->where('title', 'like', "%{$term}%")->orWhere('message', 'like', "%{$term}%");
                });
            })
            ->when($filter === 'unread', fn ($query) => $query->where('is_read', false))
            ->when($filter === 'read', fn ($query) => $query->where('is_read', true))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('applicant.notifications.index', [
            'notifications' => $notifications,
            'filters' => $filters,
            'filter' => $filter,
            'unreadCount' => $user->appNotifications()->where('is_read', false)->count(),
        ]);
    }

    public function markAsRead(Request $request, Notification $notification): RedirectResponse
    {
        $this->ownOrFail($request, $notification)->markAsRead();

        return back();
    }

    public function markAsUnread(Request $request, Notification $notification): RedirectResponse
    {
        $this->ownOrFail($request, $notification)->markAsUnread();

        return back();
    }

    public function markAllAsRead(Request $request): RedirectResponse
    {
        $request->user()->appNotifications()->where('is_read', false)->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return back()->with('success', 'All notifications were marked as read.');
    }

    private function ownOrFail(Request $request, Notification $notification): Notification
    {
        abort_unless($notification->user_id === $request->user()->id, 403);

        return $notification;
    }
}
