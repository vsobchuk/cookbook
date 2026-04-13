<?php

namespace Tests\Unit\Listeners;

use App\Events\UserFavoriteListChanged;
use App\Listeners\ProduceRecommendationListMessage;
use App\MealDb\MealDbRepository;
use App\Models\User;
use App\Services\MealService;
use App\Services\PubSubAdapter;
use Mockery;
use Tests\TestCase;

class ProduceRecommendationListMessageTest extends TestCase
{
    public function testHandle(): void
    {
        $repository = Mockery::mock(MealDbRepository::class);

        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')
            ->with('id')
            ->andReturn(1);

        $service = Mockery::mock(MealService::class);
        $service->shouldReceive('favoriteMealIngredients')
            ->once()
            ->andReturn([]);

        $broker = Mockery::mock(PubSubAdapter::class);
        $broker->shouldReceive('message')
            ->once()
            ->andReturnSelf();
        $broker->shouldReceive('produce')
            ->once();
        $broker->shouldReceive('makeEventId')
            ->once()
            ->andReturn('::event-id::');

        $event = new UserFavoriteListChanged($user);

        $listener = new ProduceRecommendationListMessage($service, $repository, $broker);

        $listener->handle($event);

        $this->assertTrue(true);
    }
}
