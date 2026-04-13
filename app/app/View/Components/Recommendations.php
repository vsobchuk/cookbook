<?php

namespace App\View\Components;

use App\Http\Resources\MealItemResource;
use App\MealDb\MealDbRepository;
use App\Models\RecommendedMeal;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class Recommendations extends Component
{
    public Collection $items;

    public function __construct(Request $request, MealDbRepository $repository)
    {
        $this->items = collect();

        if ($request->user()) {
            $recommendations = $request->user()->recommendedMeals()
                ->latest()
                ->take(6)
                ->get()
                ->map(fn (RecommendedMeal $meal) => $repository->find($meal->meal_id));

            $this->items = MealItemResource::collection($recommendations, $request->user());
        }
    }

    public function render(): View
    {
        return view('components.recommendations');
    }
}
