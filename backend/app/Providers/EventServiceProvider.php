<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        \App\Events\OrderBeforeEvent::class => [
            \App\Listeners\OrderBeforeListener::class,
        ],
        \App\Events\OrderAfterEvent::class => [
            \App\Listeners\CouponAfterOrderCreateListener::class,
            \App\Listeners\OrderAfterListener::class,
        ],
        \App\Events\OrderEventWithMP::class => [
            \App\Listeners\OrderWithMPListener::class,
        ],
        \App\Events\ContactEvent::class => [
            \App\Listeners\ContactListener::class,
        ],
        \App\Events\InvoiceEvent::class => [
            \App\Listeners\InvoiceConfirmListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

    }
}
