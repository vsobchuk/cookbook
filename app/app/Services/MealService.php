<?php

namespace App\Services;

use App\Events\UserFavoriteListChanged;
use App\MealDb\Data\MealFilterItemData;
use App\MealDb\Data\MealIngredientData;
use App\MealDb\MealDbRepository;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class MealService
{
    public function favorite(string $mealId, User $user): void
    {
        $user->favoriteMeals()->firstOrCreate([
            'meal_id' => $mealId,
        ]);

        event(new UserFavoriteListChanged($user));
    }

    public function removeFavorite(string $mealId, User $user): void
    {
        $user->favoriteMeals()
            ->where('meal_id', $mealId)
            ->delete();

        event(new UserFavoriteListChanged($user));
    }

    public function favoriteMealIngredients(User $user, MealDbRepository $repository): array
    {
        $favoriteMealIds = $user->favoriteMeals->pluck('meal_id');

        $ingredients = collect();

        $favoriteMealIds->each(function (int $mealId) use ($ingredients, $repository): void {
            $meal = $repository->find((string) $mealId);

            if ($meal) {
                collect($meal->ingredients)->each(
                    fn (MealIngredientData $ingredient) => $ingredients->push($ingredient->name),
                );
            }
        });

        return $ingredients->unique()->all();
    }

    public function resolveUserRecommendations(User $user, array $ingredients, MealDbRepository $repository): Collection
    {
        $recommendations = collect();
        $favoriteMeals = $user->favoriteMeals->pluck('meal_id');

        foreach ($ingredients as $ingredient) {
            $filteredMeals = $repository->filterByIngredient($ingredient)
                ->filter(fn (MealFilterItemData $meal) => $favoriteMeals->doesntContain($meal->id));

            $recommendations = $recommendations->concat($filteredMeals);
        }

        return $recommendations;
    }

    public function syncUserRecommendations(User $user, Collection $recommendations): void
    {
        DB::transaction(function () use ($user, $recommendations): void {
            $user->recommendedMeals()
                ->whereNotIn('meal_id', $recommendations->pluck('id')->all())
                ->delete();

            $recommendations->each(function (MealFilterItemData $meal) use ($user): void {
                $user->recommendedMeals()->updateOrCreate([
                    'meal_id' => $meal->id,
                ]);
            });
        });
    }
}
