<?php

// app/Events/ChatRoomUpdated.php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\TrMessages;

class ChatRoomUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $roomId;
    public $receiverId;

    public function __construct(TrMessages $message, $receiverId)
    {
        $this->message = $message;
        $this->roomId = $message->room_id;
        $this->receiverId = $receiverId;
    }

    public function broadcastOn()
    {
        return new Channel('chatlist.' . $this->receiverId);
    }

    public function broadcastAs()
    {
        return 'room_updated';
    }

    // app/Events/ChatRoomUpdated.php
    public function broadcastWith()
    {
        return [
            'room_id' => $this->roomId,
            'message' => $this->message->message,
            'sender_id' => $this->message->sender_id,
            'created_at' => $this->message->created_at->diffForHumans(),
        ];
    }

}
