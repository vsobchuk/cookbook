<?php

namespace App\Http\Controllers;

use App\Services\MealService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MealFavoriteController
{
    public function __invoke(string $mealId, Request $request, MealService $service): JsonResponse
    {
        $service->favorite($mealId, $request->user());

        return response()->json();
    }
}
