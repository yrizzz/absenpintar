<?php

namespace App\Events;

use App\Models\AttendanceLog;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttendanceLogged implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $attendance;

    /**
     * Create a new event instance.
     */
    public function __construct(AttendanceLog $attendance)
    {
        $this->attendance = $attendance;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('dashboard.admin'),
            new PrivateChannel('dashboard.user.' . $this->attendance->user_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'AttendanceLogged';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->attendance->id,
            'user_id' => $this->attendance->user_id,
            'type' => $this->attendance->type,
        ];
    }
}
