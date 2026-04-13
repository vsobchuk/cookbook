<?php

namespace App\Listeners;

use App\Data\RecommendationMessageListMessageData;
use App\Events\UserFavoriteListChanged;
use App\MealDb\MealDbRepository;
use App\Services\MealService;
use App\Services\PubSubAdapter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProduceRecommendationListMessage implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private MealService $service,
        private MealDbRepository $repository,
        private PubSubAdapter $broker,
    ) {
    }

    public function handle(UserFavoriteListChanged $event): void
    {
        $ingredients = $this->service->favoriteMealIngredients($event->user, $this->repository);

        $message = new RecommendationMessageListMessageData([
            'event_id' => $this->broker->makeEventId(class_basename($this)),
            'user_id' => $event->user->id,
            'ingredients' => $ingredients,
        ]);

        $this->broker->message($message)->produce();
    }
}
