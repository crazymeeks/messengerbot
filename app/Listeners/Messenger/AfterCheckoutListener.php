<?php

namespace App\Listeners\Messenger;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use FbMessengerBot\Reply\Message;
use App\Events\Messenger\AfterCheckoutEvent;

class AfterCheckoutListener
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
    public function handle(AfterCheckoutEvent $event)
    {
        $message = new Message();
        
        $message->handle($event->reply);

    }
}
