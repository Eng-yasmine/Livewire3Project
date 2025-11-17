<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SystemMaintenanceEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * رسالة الصيانة
     */
    public $message;

    /**
     * حالة الصيانة (info, warning, error, success)
     */
    public $status;

    /**
     * Create a new event instance.
     */
    public function __construct($message = 'System maintenance in progress', $status = 'info')
    {
        $this->message = $message;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // قناة عامة - يمكن لأي شخص الاستماع
        return [
            new Channel('system-maintenance'),
        ];
        
        // إذا أردت قناة خاصة (تتطلب مصادقة):
        // return [
        //     new PrivateChannel('system-maintenance'),
        // ];
    }

    /**
     * البيانات التي سيتم إرسالها مع الحدث
     */
    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
            'status' => $this->status,
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    /**
     * اسم الحدث (اختياري)
     * إذا استخدمت هذا، في JavaScript استخدم: .listen('.maintenance.update', ...)
     */
    public function broadcastAs(): string
    {
        return 'maintenance.update';
    }
}
