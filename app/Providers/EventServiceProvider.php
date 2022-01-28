<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\Messenger\AfterCheckoutEvent;
use App\Listeners\Messenger\AfterCheckoutListener;
use App\Events\Messenger\Buttons\PaynowEvent;
use App\Listeners\Messenger\Buttons\PaynowListener;
use App\Events\Messenger\UnsentOrderConfirmationEvent;
use App\Listeners\Messenger\UnsentOrderConfirmationListener;
use App\Events\Messenger\PaymentGateUnavailableEvent;
use App\Listeners\Messenger\PaymentGateUnavailableListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        AfterCheckoutEvent::class => [
            AfterCheckoutListener::class
        ],
        PaynowEvent::class => [
            PaynowListener::class
        ],
        UnsentOrderConfirmationListener::class => [
            UnsentOrderConfirmationEvent::class
        ],
        PaymentGateUnavailableEvent::class => [
            PaymentGateUnavailableListener::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
