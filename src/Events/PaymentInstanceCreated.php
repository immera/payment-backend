<?php

namespace Immera\Payment\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Immera\Payment\Models\PaymentInstance;

class PaymentInstanceCreated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    private $payment_instance;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(PaymentInstance $pi)
    {
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
