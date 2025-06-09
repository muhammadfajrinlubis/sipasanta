<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PanicButtonPressed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ruangan;

    public function __construct($ruangan)
    {
        $this->ruangan = $ruangan;
    }

    public function broadcastOn()
    {
        return new Channel('panic-channel');
    }

    public function broadcastAs()
    {
        return 'panic-event';
    }
}
