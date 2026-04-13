<?php

namespace Tests\Unit\Services;

use App\MealDb\MealDbRepository;
use App\MealDb\Transformers\FilterResultTransformer;
use App\MealDb\Transformers\SearchResultTransformer;
use App\Models\FavoriteMeal;
use App\Models\User;
use App\Services\MealService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Mockery;
use Tests\Mixins\InteractWithMealDbEntities;
use Tests\TestCase;

class MealServiceTest extends TestCase
{
    use DatabaseTransactions;
    use InteractWithMealDbEntities;

    private ?MealService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new MealService();
    }

    public function testFavorite(): void
    {
        Event::fake();

        $favoriteMeal = new FavoriteMeal();

        $relationMock = Mockery::mock(HasMany::class);
        $relationMock->shouldReceive('firstOrCreate')
            ->once()
            ->andReturn($favoriteMeal);

        $userMock = Mockery::mock(User::class);
        $userMock->shouldReceive('favoriteMeals')
            ->once()
            ->andReturn($relationMock);

        $this->service->favorite('::id::', $userMock);
    }

    public function testRemoveFavorite(): void
    {
        Event::fake();

        $builder = Mockery::mock(Builder::class);
        $builder->shouldReceive('delete')
            ->andReturn(1);

        $relationMock = Mockery::mock(HasMany::class);
        $relationMock->shouldReceive('where')
            ->once()
            ->andReturn($builder);

        $userMock = Mockery::mock(User::class);
        $userMock->shouldReceive('favoriteMeals')
            ->once()
            ->andReturn($relationMock);

        $this->service->removeFavorite('::id::', $userMock);
    }

    public function testFavoriteMealIngredients(): void
    {
        $favoriteMeals = collect($this->favoriteMealsDataProvider());

        $meals = (new SearchResultTransformer())
            ->transform(collect($this->mealsDataProvider()));

        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')
            ->with('favoriteMeals')
            ->andReturn($favoriteMeals);

        $repository = Mockery::mock(MealDbRepository::class);
        $repository->shouldReceive('find')
            ->twice()
            ->andReturn($meals->first(), $meals->last());

        $ingredients = $this->service->favoriteMealIngredients($user, $repository);
        $unique = array_keys(array_flip($ingredients));

        $this->assertNotEmpty($ingredients);
        $this->assertEquals($unique, $ingredients);
    }

    public function testResolveUserRecommendations(): void
    {
        $ingredients = ['Sea Salt', 'Fish'];
        $favoriteMeals = collect($this->favoriteMealsDataProvider())->slice(1);
        $meals = (new FilterResultTransformer())
            ->transform(collect($this->mealsDataProvider()));

        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')
            ->with('favoriteMeals')
            ->andReturn($favoriteMeals);

        $repository = Mockery::mock(MealDbRepository::class);
        $repository->shouldReceive('filterByIngredient')
            ->twice()
            ->andReturn($meals->slice(0, 1), $meals->slice(1));

        $recommendations = $this->service->resolveUserRecommendations($user, $ingredients, $repository);

        $this->assertCount(1, $recommendations);
        $this->assertNotEquals(
            $favoriteMeals->pluck('meal_id')->all(),
            $recommendations->pluck('id')->map(fn (string $id) => (int) $id)->all(),
        );
    }

    public function testSyncUserRecommendations(): void
    {
        $relation = Mockery::mock(HasMany::class);
        $relation->shouldReceive('whereNotIn')
            ->once()
            ->andReturnSelf();
        $relation->shouldReceive('delete')
            ->once();
        $relation->shouldReceive('updateOrCreate')
            ->once();

        $user = Mockery::mock(User::class);
        $user->shouldReceive('recommendedMeals')
            ->twice()
            ->andReturn($relation);

        $recommendations = (new FilterResultTransformer())
            ->transform(collect($this->mealsDataProvider()))->slice(1);

        $this->service->syncUserRecommendations($user, $recommendations);

        $this->assertTrue(true);
    }

    private function favoriteMealsDataProvider(): array
    {
        return [
            new FavoriteMeal([
                'user_id' => 1,
                'meal_id' => 53061,
            ]),
            new FavoriteMeal([
                'user_id' => 1,
                'meal_id' => 52977,
            ]),
        ];
    }
}
