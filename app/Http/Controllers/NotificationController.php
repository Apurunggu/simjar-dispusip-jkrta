<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // Tampilkan semua notifikasi pengguna
    public function index()
    {
        $notifications = auth()->user()->notifikasi()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    // Tampilkan notifikasi di navbar (dropdown)
    public function dropdown()
    {
        $unreadCount = auth()->user()->notifikasi()->unread()->count();
        $notifications = auth()->user()->notifikasi()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    // Mark notifikasi sebagai read
    public function markAsRead($id)
    {
        $notification = Notification::find($id);

        if ($notification && $notification->user_id === auth()->id()) {
            $notification->markAsRead();
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 404);
    }

    // Mark semua notifikasi sebagai read
    public function markAllAsRead()
    {
        auth()->user()->notifikasi()
            ->unread()
            ->update(['read_at' => now()]);

        return response()->json(['status' => 'success']);
    }

    // Delete notifikasi
    public function delete($id)
    {
        $notification = Notification::find($id);

        if ($notification && $notification->user_id === auth()->id()) {
            $notification->delete();
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error'], 404);
    }

    // Delete semua notifikasi
    public function deleteAll()
    {
        auth()->user()->notifikasi()->delete();
        return response()->json(['status' => 'success']);
    }

    // Create notifikasi (untuk internal use)
    public static function createNotification($userId, $title, $message, $type = 'general', $icon = 'bi-bell', $color = 'info', $actionUrl = null, $relatedId = null, $relatedType = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'icon' => $icon,
            'color' => $color,
            'action_url' => $actionUrl,
            'related_id' => $relatedId,
            'related_type' => $relatedType,
        ]);
    }

    // Create notifikasi untuk multiple users (berdasarkan role)
    public static function createNotificationForRole($roleId, $title, $message, $type = 'general', $icon = 'bi-bell', $color = 'info', $actionUrl = null)
    {
        $users = \App\Models\User::where('role_id', $roleId)->get();

        foreach ($users as $user) {
            self::createNotification(
                $user->id,
                $title,
                $message,
                $type,
                $icon,
                $color,
                $actionUrl
            );
        }
    }

    // Create notifikasi untuk semua users dengan role tertentu (except current user)
    public static function createNotificationForRoleExcept($roleId, $title, $message, $exceptUserId = null, $type = 'general', $icon = 'bi-bell', $color = 'info', $actionUrl = null)
    {
        $query = \App\Models\User::where('role_id', $roleId);

        if ($exceptUserId) {
            $query->where('id', '!=', $exceptUserId);
        }

        $users = $query->get();

        foreach ($users as $user) {
            self::createNotification(
                $user->id,
                $title,
                $message,
                $type,
                $icon,
                $color,
                $actionUrl
            );
        }
    }
}
