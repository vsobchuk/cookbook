<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Queries\UserQueryBuilder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Mockery;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testNewEloquentBuilder(): void
    {
        $dbQueryBuilder = Mockery::mock(Builder::class);

        $model = new User();

        $this->assertInstanceOf(UserQueryBuilder::class, $model->newEloquentBuilder($dbQueryBuilder));
    }

    public function testFavoriteMeals(): void
    {
        $model = new User();

        $this->assertInstanceOf(HasMany::class, $model->favoriteMeals());
    }

    public function testRecommendedMeals(): void
    {
        $model = new User();

        $this->assertInstanceOf(HasMany::class, $model->recommendedMeals());
    }
}
