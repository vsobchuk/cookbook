<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\MealFavoriteController;
use App\Models\User;
use App\Services\MealService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class MealFavoriteControllerTest extends TestCase
{
    public function testInvoke(): void
    {
        $mealId = '1122';

        $user = Mockery::mock(User::class);

        $service = Mockery::mock(MealService::class);
        $service->shouldReceive('favorite')
            ->once();

        $request = Mockery::mock(Request::class);
        $request->shouldReceive('user')
            ->andReturn($user);

        $response = call_user_func(new MealFavoriteController(), $mealId, $request, $service);

        $this->assertInstanceOf(JsonResponse::class, $response);
    }
}
