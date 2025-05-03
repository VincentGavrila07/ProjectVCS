<?php
// app/Events/MessageSent.php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\TrMessages;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $message;

    public function __construct(TrMessages $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new Channel('chatroom.' . $this->message->room_id);
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message->message,
            'sender_id' => $this->message->sender_id,
            'created_at' => $this->message->created_at->format('d M Y, H:i'),
            'sender_image' => $this->message->sender ? asset('storage/' . $this->message->sender->image) : null,
        ];
    }
}
