<?php

namespace App\Support;

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

final class AppNotifier
{
    public static function admins(): EloquentCollection
    {
        return User::query()
            ->whereIn('role', ['admin', 'super_admin'])
            ->get();
    }

    public static function adminsDatabase(string $title, ?string $body = null, string $status = 'info'): void
    {
        self::database(self::admins(), $title, $body, $status);
    }

    public static function database(mixed $recipients, string $title, ?string $body = null, string $status = 'info'): void
    {
        foreach (self::normalizeRecipients($recipients) as $recipient) {
            $notification = Notification::make()->title($title);

            if (filled($body)) {
                $notification->body($body);
            }

            match ($status) {
                'success' => $notification->success(),
                'warning' => $notification->warning(),
                'danger' => $notification->danger(),
                default => $notification->info(),
            };

            $notification->sendToDatabase($recipient);
        }
    }

    /**
     * @return array<int, User>
     */
    private static function normalizeRecipients(mixed $recipients): array
    {
        if ($recipients instanceof User) {
            return [$recipients];
        }

        if ($recipients instanceof EloquentCollection || $recipients instanceof Collection) {
            $recipients = $recipients->all();
        }

        if (! is_iterable($recipients)) {
            return [];
        }

        $users = [];

        foreach ($recipients as $recipient) {
            if ($recipient instanceof User) {
                $users[$recipient->getKey()] = $recipient;
            }
        }

        return array_values($users);
    }
}
