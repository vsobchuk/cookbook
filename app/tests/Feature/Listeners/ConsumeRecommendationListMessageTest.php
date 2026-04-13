<?php

namespace Tests\Feature\Listeners;

use App\Data\RecommendationMessageListMessageData;
use App\Events\UserRecommendationsShouldBeRefreshed;
use App\Listeners\ConsumeRecommendationListMessage;
use App\MealDb\Data\MealFilterItemData;
use App\MealDb\MealDbApiClient;
use App\MealDb\Transformers\FilterResultTransformer;
use App\Models\User;
use App\Services\MealService;
use Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Mixins\InteractWithMealDbEntities;
use Tests\TestCase;

class ConsumeRecommendationListMessageTest extends TestCase
{
    use RefreshDatabase;
    use InteractWithMealDbEntities;

    private ?User $user;

    private ?MealFilterItemData $favoriteMeal;

    private ?MealFilterItemData $recommendedMeal;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->user = User::factory()->create();

        $meals = (new FilterResultTransformer())
            ->transform(collect($this->mealsDataProvider()));

        $this->favoriteMeal = $meals->first();
        $this->recommendedMeal = $meals->last();

        $this->app->get(MealService::class)->favorite($this->favoriteMeal->id, $this->user);

        $mealDbClient = \Mockery::mock(MealDbApiClient::class);
        $mealDbClient->shouldReceive('filterByIngredient')
            ->andReturn($this->mealsDataProvider());

        $this->app->instance(MealDbApiClient::class, $mealDbClient);
    }

    public function testHandle(): void
    {
        $this->assertDatabaseHas('favorite_meals', ['meal_id' => $this->favoriteMeal->id]);
        $this->assertDatabaseCount('favorite_meals', 1);
        $this->assertDatabaseCount('recommended_meals', 0);

        $payload = new RecommendationMessageListMessageData([
            'event_id' => '::event-id::',
            'user_id' => $this->user->id,
            'ingredients' => ['Vegetable Oil', 'Salt'],
        ]);

        $event = new UserRecommendationsShouldBeRefreshed($payload);

        app(ConsumeRecommendationListMessage::class)->handle($event);

        $this->assertDatabaseHas('favorite_meals', ['meal_id' => $this->favoriteMeal->id]);
        $this->assertDatabaseCount('favorite_meals', 1);
        $this->assertDatabaseHas('recommended_meals', ['meal_id' => $this->recommendedMeal->id]);
        $this->assertDatabaseCount('recommended_meals', 1);
    }
}
