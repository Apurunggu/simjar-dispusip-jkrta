<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;
use App\Models\Role;

class NotificationHelper
{
    /**
     * Create a notification for a specific user
     */
    public static function notify($userId, $title, $message, $type = 'general', $icon = 'bi-bell', $color = 'info', $actionUrl = null, $relatedId = null, $relatedType = null)
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

    /**
     * Create notification for all users with specific role
     */
    public static function notifyRole($roleName, $title, $message, $type = 'general', $icon = 'bi-bell', $color = 'info', $actionUrl = null, $exceptUserId = null)
    {
        $role = Role::where('name', $roleName)->first();
        if (!$role) return null;

        $query = User::where('role_id', $role->id);
        
        if ($exceptUserId) {
            $query->where('id', '!=', $exceptUserId);
        }

        $users = $query->get();

        foreach ($users as $user) {
            self::notify($user->id, $title, $message, $type, $icon, $color, $actionUrl);
        }

        return count($users);
    }

    /**
     * Create notification for multiple specific users
     */
    public static function notifyUsers($userIds, $title, $message, $type = 'general', $icon = 'bi-bell', $color = 'info', $actionUrl = null)
    {
        $count = 0;
        foreach ($userIds as $userId) {
            self::notify($userId, $title, $message, $type, $icon, $color, $actionUrl);
            $count++;
        }
        return $count;
    }

    /**
     * Create notification for all admin/super_admin users
     */
    public static function notifyAdmins($title, $message, $type = 'general', $icon = 'bi-bell', $color = 'info', $actionUrl = null)
    {
        $superAdmins = User::whereHas('role', function ($q) {
            $q->whereIn('name', ['super_admin', 'admin_cabang']);
        })->get();

        foreach ($superAdmins as $user) {
            self::notify($user->id, $title, $message, $type, $icon, $color, $actionUrl);
        }

        return count($superAdmins);
    }

    /**
     * Create notification for all users except specific user
     */
    public static function notifyAllExcept($title, $message, $exceptUserId = null, $type = 'general', $icon = 'bi-bell', $color = 'info', $actionUrl = null)
    {
        $query = User::query();
        
        if ($exceptUserId) {
            $query->where('id', '!=', $exceptUserId);
        }

        $users = $query->get();

        foreach ($users as $user) {
            self::notify($user->id, $title, $message, $type, $icon, $color, $actionUrl);
        }

        return count($users);
    }

    /**
     * Notification types and their icons/colors
     */
    public static function getNotificationConfig($type)
    {
        $configs = [
            'barang_masuk' => [
                'icon' => 'bi-box-seam',
                'color' => 'primary',
            ],
            'perangkat_jaringan' => [
                'icon' => 'bi-router',
                'color' => 'info',
            ],
            'distribusi' => [
                'icon' => 'bi-truck',
                'color' => 'success',
            ],
            'laporan' => [
                'icon' => 'bi-file-earmark-pdf',
                'color' => 'warning',
            ],
            'user_management' => [
                'icon' => 'bi-people',
                'color' => 'secondary',
            ],
            'general' => [
                'icon' => 'bi-bell',
                'color' => 'info',
            ],
            'success' => [
                'icon' => 'bi-check-circle',
                'color' => 'success',
            ],
            'error' => [
                'icon' => 'bi-exclamation-circle',
                'color' => 'danger',
            ],
            'warning' => [
                'icon' => 'bi-exclamation-triangle',
                'color' => 'warning',
            ],
        ];

        return $configs[$type] ?? $configs['general'];
    }
}
