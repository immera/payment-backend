<?php

namespace Adiechahk\PaymentBackend\Events;

use Adiechahk\PaymentBackend\Models\PaymentInstance;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentInstanceUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $payment_instance;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PaymentInstance $pi)
    {
        //
        $this->payment_instance = $pi;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
