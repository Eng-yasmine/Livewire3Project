<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // قناة عامة للمحادثة
        return [
            new Channel('chat'),
        ];
    }

    /**
     * البيانات التي سيتم إرسالها مع الحدث
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'user_id' => $this->message->user_id,
            'username' => $this->message->username ?? 'مجهول',
            'message' => $this->message->message,
            'created_at' => $this->message->created_at->toDateTimeString(),
            'time' => $this->message->created_at->format('H:i'),
        ];
    }

    /**
     * اسم الحدث
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}
