<?php
 
namespace App\Events;
 
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
 
class ChatMessageRecieved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
 
    protected $message;
    protected $request;
 
    /*  Create a new event instance.*/
    public function __construct($request) {
        $this->request = $request;
    }
    /*  イベントをブロードキャストすべき、チャンネルの取得*/
    public function broadcastOn() {
         return new Channel('chat');
    }
    /*  ブロードキャストするデータを取得    */
    public function broadcastWith() {
         return [
            'message' => $this->request['message'],
            'send' => $this->request['send'],
            'recieve' => $this->request['recieve'],
        ];
    }
    /*  イベントブロードキャスト名*/
    public function broadcastAs() {
         return 'chat_event';
    }
}
?>