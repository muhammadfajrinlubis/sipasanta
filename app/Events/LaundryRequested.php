<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;

class LaundryRequested implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $laundry;

    public function __construct($laundry)
    {
        $this->laundry = $laundry;
    }

    public function broadcastOn()
    {
        return new Channel('laundry-channel');
    }

    public function broadcastAs()
    {
        return 'laundry-requested';
    }

    public function broadcastWith()
    {
        return [
            'id'            => $this->laundry->id,
            'nomr'          => $this->laundry->nomr,
            'tanggal'       => $this->laundry->tanggal,
            'ruangan' => optional($this->laundry->ruangan)->nama ?? 'Tidak diketahui',
        ];

    }
}
