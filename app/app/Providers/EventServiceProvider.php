<?php

namespace App\Providers;

use App\Events\UserFavoriteListChanged;
use App\Events\UserRecommendationsShouldBeRefreshed;
use App\Listeners\ConsumeRecommendationListMessage;
use App\Listeners\ProduceRecommendationListMessage;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserFavoriteListChanged::class => [
            ProduceRecommendationListMessage::class,
        ],
        UserRecommendationsShouldBeRefreshed::class => [
            ConsumeRecommendationListMessage::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
