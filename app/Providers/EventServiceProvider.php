<?php
declare(strict_types=1);

namespace App\Providers;

use App\Events\COVID19AmbulanceSaved;
use App\Events\COVID19CaseSaved;
use App\Listeners\COVID19SendAmbulanceSaved;
use App\Listeners\COVID19SendCaseSaved;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        COVID19AmbulanceSaved::class => [
            COVID19SendAmbulanceSaved::class,
        ],
        COVID19CaseSaved::class => [
            COVID19SendCaseSaved::class,
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

        //
    }
}
