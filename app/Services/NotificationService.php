<?php

namespace App\Services;

use App\Models\AppNotification;
use App\Models\User;

class NotificationService
{
    /**
     * Send a notification to one specific user.
     */
    public static function send(
        int $userId,
        string $type,
        string $title,
        string $body,
        string $icon = 'fas fa-bell',
        string $iconColor = 'text-primary',
        ?string $actionUrl = null,
        ?array $data = null
    ): AppNotification {
        return AppNotification::create([
            'user_id'    => $userId,
            'type'       => $type,
            'title'      => $title,
            'body'       => $body,
            'icon'       => $icon,
            'icon_color' => $iconColor,
            'action_url' => $actionUrl,
            'data'       => $data,
        ]);
    }

    /**
     * Send a notification to every user who holds any of the given Spatie roles.
     */
    public static function sendToRoles(
        array $roles,
        string $type,
        string $title,
        string $body,
        string $icon = 'fas fa-bell',
        string $iconColor = 'text-primary',
        ?string $actionUrl = null,
        ?array $data = null,
        ?int $excludeUserId = null
    ): void {
        User::whereHas('roles', fn ($q) => $q->whereIn('name', $roles))
            ->when($excludeUserId, fn ($q) => $q->where('id', '!=', $excludeUserId))
            ->each(function (User $user) use ($type, $title, $body, $icon, $iconColor, $actionUrl, $data) {
                static::send($user->id, $type, $title, $body, $icon, $iconColor, $actionUrl, $data);
            });
    }
}
