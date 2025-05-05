<?php
// app/Events/MessageReceived.php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('chat-room.' . $this->message->room_id); // Broadcast ke channel tertentu
    }

    public function broadcastWith()
    {
        return ['message' => $this->message]; // Data yang dibroadcast
    }
}
