<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\SearchController;
use App\MealDb\MealDbRepository;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    public function testInvoke(): void
    {
        $searchQuery = '::query::';

        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')
            ->with('favoriteMeals')
            ->andReturn(collect());

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('get')
            ->andReturn($searchQuery);
        $request->shouldReceive('user')
            ->andReturn($user);

        $repository = Mockery::mock(MealDbRepository::class);
        $repository->shouldReceive('search')
            ->andReturn(collect());

        $viewData = call_user_func(new SearchController(), $request, $repository);

        $this->assertEquals($searchQuery, $viewData['searchQuery']);
        $this->assertInstanceOf(Collection::class, $viewData['results']);
    }
}
