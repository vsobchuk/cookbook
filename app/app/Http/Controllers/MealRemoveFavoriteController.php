<?php

namespace App\Http\Controllers;

use App\Services\MealService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MealRemoveFavoriteController
{
    public function __invoke(string $mealId, Request $request, MealService $service): JsonResponse
    {
        $service->removeFavorite($mealId, $request->user());

        return response()->json();
    }
}
