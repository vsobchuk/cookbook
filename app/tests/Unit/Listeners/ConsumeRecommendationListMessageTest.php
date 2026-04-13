<?php

namespace Tests\Unit\Listeners;

use App\Data\RecommendationMessageListMessageData;
use App\Events\UserRecommendationsShouldBeRefreshed;
use App\Listeners\ConsumeRecommendationListMessage;
use App\MealDb\MealDbRepository;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\MealService;
use Mockery;
use Tests\TestCase;

class ConsumeRecommendationListMessageTest extends TestCase
{
    public function testHandle(): void
    {
        $user = Mockery::mock(User::class);
        $mealRepository = Mockery::mock(MealDbRepository::class);

        $userRepository = Mockery::mock(UserRepository::class);
        $userRepository->shouldReceive('find')
            ->andReturn($user);

        $service = Mockery::mock(MealService::class);
        $service->shouldReceive('resolveUserRecommendations')
            ->once()
            ->andReturn(collect());
        $service->shouldReceive('syncUserRecommendations')
            ->once();

        $payload = new RecommendationMessageListMessageData([
            'event_id' => '::event-id::',
            'user_id' => 1,
            'ingredients' => [],
        ]);

        $event = new UserRecommendationsShouldBeRefreshed($payload);

        $listener = new ConsumeRecommendationListMessage($service, $mealRepository, $userRepository);

        $listener->handle($event);

        $this->assertTrue(true);
    }
}
