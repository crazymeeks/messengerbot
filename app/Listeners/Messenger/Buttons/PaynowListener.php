<?php

namespace App\Listeners\Messenger\Buttons;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use FbMessengerBot\Reply\Message;
use App\Events\Messenger\Buttons\PaynowEvent;

class PaynowListener
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
    public function handle(PaynowEvent $event)
    {
        $message = new Message();
        
        $message->handle($event->reply);
    }
}
