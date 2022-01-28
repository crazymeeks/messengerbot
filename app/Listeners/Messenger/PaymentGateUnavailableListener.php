<?php

namespace App\Listeners\Messenger;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Messenger\PaymentGateUnavailableEvent;
use FbMessengerBot\Reply\Message;

class PaymentGateUnavailableListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(PaymentGateUnavailableEvent $event)
    {
        $message = new Message();
        $message->handle($event->reply);
    }
}
