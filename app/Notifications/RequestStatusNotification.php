<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

/**
 * Generic in-app notification used to inform an employee about the outcome of a
 * leave or permission request (approved at a stage, fully approved, or rejected).
 */
class RequestStatusNotification extends Notification
{
    /**
     * @param  string  $status  one of: approved | rejected | info
     */
    public function __construct(
        public string $title,
        public string $message,
        public string $url,
        public string $status = 'info',
    ) {}

    /**
     * Stored in the database and read by the in-app notification bell.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'url' => $this->url,
            'status' => $this->status,
        ];
    }
}
