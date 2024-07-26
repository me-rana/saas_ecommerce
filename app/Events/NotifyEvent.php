<?php

namespace App\Events;

use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NotifyEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $name;
    public $time;
    /**
     * Create a new event instance.
     */
    public function __construct($name)
    {
        //
        $time = Carbon::now();
        $this->name = $name;
        $this->time = $time;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */

     public function broadcastWith(): array
     {
         return [
             'name' => $this->name,
             'time' => $this->time,
         ];
     }

    public function broadcastOn(): array
    {
        return [
            new Channel('NotifyChannel'),
        ];
    }
}
