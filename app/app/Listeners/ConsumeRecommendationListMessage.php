<?php

namespace App\Listeners;

use App\Events\UserRecommendationsShouldBeRefreshed;
use App\MealDb\MealDbRepository;
use App\Repositories\UserRepository;
use App\Services\MealService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ConsumeRecommendationListMessage implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private MealService $service,
        private MealDbRepository $mealRepository,
        private UserRepository $userRepository,
    ) {
    }

    public function handle(UserRecommendationsShouldBeRefreshed $event): void
    {
        $user = $this->userRepository->find($event->payload->user_id);

        if (! $user) {
            return;
        }

        $recommendations = $this->service->resolveUserRecommendations(
            $user,
            $event->payload->ingredients,
            $this->mealRepository,
        );

        $this->service->syncUserRecommendations($user, $recommendations);
    }
}
