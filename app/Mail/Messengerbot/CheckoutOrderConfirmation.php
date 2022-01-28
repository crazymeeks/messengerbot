<?php

namespace App\Mail\Messengerbot;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CheckoutOrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\stdClass $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->view('frontend.messengerbot.marketing.order-confirmation')
                    ->subject('Order Confirmation')
                    ->with([
                        'firstname' => $this->data->firstname,
                        'payment_url' => $this->data->payment_url
                    ]);
    }
}
