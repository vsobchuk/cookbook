<?php

namespace Tests\Feature\MealDb;

use App\MealDb\Data\MealFilterItemData;
use App\MealDb\Data\MealItemData;
use App\MealDb\MealDbApiClient;
use App\MealDb\MealDbRepository;
use Illuminate\Support\Collection;
use Mockery;
use Mockery\MockInterface;
use Tests\Mixins\InteractWithMealDbEntities;
use Tests\TestCase;

class MealDbRepositoryTest extends TestCase
{
    use InteractWithMealDbEntities;

    public function testFind(): void
    {
        $this->instance(
            MealDbApiClient::class,
            Mockery::mock(MealDbApiClient::class, function (MockInterface $mock): void {
                $mock->shouldReceive('find')
                    ->once()
                    ->andReturn(array_slice($this->mealsDataProvider(), 0, 1));
            }),
        );

        $repository = $this->app->make(MealDbRepository::class);

        $result = $repository->find('::id::');

        $this->assertInstanceOf(MealItemData::class, $result);
    }

    public function testSearch(): void
    {
        $this->instance(
            MealDbApiClient::class,
            Mockery::mock(MealDbApiClient::class, function (MockInterface $mock): void {
                $mock->shouldReceive('search')
                    ->once()
                    ->andReturn($this->mealsDataProvider());
            }),
        );

        $repository = $this->app->make(MealDbRepository::class);

        $result = $repository->search('example');

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertInstanceOf(MealItemData::class, $result->first());
    }

    public function testFilterByIngredient(): void
    {
        $this->instance(
            MealDbApiClient::class,
            Mockery::mock(MealDbApiClient::class, function (MockInterface $mock): void {
                $mock->shouldReceive('filterByIngredient')
                    ->once()
                    ->andReturn($this->mealsDataProvider());
            }),
        );

        $repository = $this->app->make(MealDbRepository::class);

        $result = $repository->filterByIngredient('example');

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertInstanceOf(MealFilterItemData::class, $result->first());
    }
}
