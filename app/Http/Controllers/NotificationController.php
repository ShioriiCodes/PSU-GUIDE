<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function latest(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['unread_count' => 0]);
        }

        $unread = $user->unreadNotifications()->latest()->get();
        $count = $unread->count();
        $latest = $unread->first();

        $payload = [
            'unread_count' => $count,
        ];
        if ($latest) {
            $payload['id'] = $latest->id;
            $payload['title'] = $latest->data['title'] ?? 'Notification';
            $payload['message'] = $latest->data['message'] ?? '';
            $payload['url'] = $latest->data['url'] ?? null;
        }

        return response()->json($payload);
    }

    public function unreadByAnnouncement(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['items' => []]);
        }

        $items = [];
        foreach ($user->unreadNotifications()->latest()->get() as $n) {
            $annId = $n->data['announcement_id'] ?? null;
            if (!$annId) {
                continue;
            }
            $items[$annId] = ($items[$annId] ?? 0) + 1;
        }

        $result = [];
        foreach ($items as $announcementId => $count) {
            $result[] = ['announcement_id' => (int)$announcementId, 'count' => $count];
        }

        return response()->json(['items' => $result]);
    }

    public function markRead(Request $request, string $id)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($id === 'all') {
            $user->unreadNotifications->markAsRead();
            return response()->json(['message' => 'All notifications marked as read.']);
        }

        $notification = $user->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }

        return response()->json(['message' => 'Notification marked as read.']);
    }

    public function markReadByAnnouncement(Request $request, int $announcementId)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user->unreadNotifications()
            ->where('data->announcement_id', $announcementId)
            ->update(['read_at' => now()]);

        return response()->json(['message' => 'Announcement notifications marked as read.']);
    }
}


