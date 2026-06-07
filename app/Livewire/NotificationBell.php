<?php

namespace App\Livewire;

use Livewire\Component;

class NotificationBell extends Component
{
    /**
     * Mark a notification as read and navigate to its target page.
     */
    public function open(string $id)
    {
        $notification = auth()->user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
            $url = $notification->data['url'] ?? null;
            if ($url) {
                return redirect($url);
            }
        }
    }

    public function markAllRead(): void
    {
        auth()->user()->unreadNotifications->markAsRead();
    }

    public function render()
    {
        $user = auth()->user();

        return view('livewire.notification-bell', [
            'unreadCount' => $user->unreadNotifications()->count(),
            'notifications' => $user->notifications()->latest()->take(8)->get(),
        ]);
    }
}
