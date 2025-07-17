<?php

namespace Botble\Announcementadmin\Providers;

use Botble\Announcementadmin\Listeners\SendMailsAfterCustomerRegistered;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendMailsAfterCustomerRegistered::class,
        ],
    ];
}
