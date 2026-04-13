<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\MealRemoveFavoriteController;
use App\Models\User;
use App\Services\MealService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class MealRemoveFavoriteControllerTest extends TestCase
{
    public function testInvoke(): void
    {
        $mealId = '1122';

        $user = Mockery::mock(User::class);

        $service = Mockery::mock(MealService::class);
        $service->shouldReceive('removeFavorite')
            ->once();

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('user')
            ->andReturn($user);

        $response = call_user_func(new MealRemoveFavoriteController(), $mealId, $request, $service);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
