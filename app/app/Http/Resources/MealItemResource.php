<?php

namespace App\Http\Resources;

use App\Data\MealItemResourceData;
use App\MealDb\Data\MealItemData;
use App\Models\User;
use Illuminate\Support\Collection;

class MealItemResource
{
    public static function collection(Collection $collection, User $user): Collection
    {
        $favoriteMeals = $user->favoriteMeals->pluck('meal_id');

        return $collection->map(function (MealItemData $item) use ($favoriteMeals) {
            return new MealItemResourceData(array_merge($item->toArray(), [
                'is_favorite' => $favoriteMeals->contains($item->id),
            ]));
        });
    }
}
