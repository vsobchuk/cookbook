<?php

namespace App\MealDb\Transformers;

use App\Contracts\Transformable;
use App\MealDb\Data\MealIngredientData;
use App\MealDb\Data\MealItemData;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class SearchResultTransformer implements Transformable
{
    public function transform(Collection $items): Collection
    {
        return $items->map(function (array $item) {
            $ingredients = $this->resolveIngredients($item);

            return new MealItemData([
                'id' => Arr::get($item, 'idMeal'),
                'title' => Arr::get($item, 'strMeal'),
                'drink' => Arr::get($item, 'strDrinkAlternate'),
                'category' => Arr::get($item, 'strCategory'),
                'country' => Arr::get($item, 'strArea'),
                'instruction' => Arr::get($item, 'strInstructions'),
                'imgUrl' => Arr::get($item, 'strMealThumb'),
                'sourceUrl' => Arr::get($item, 'strSource'),
                'ingredients' => $ingredients,
            ]);
        });
    }

    public function resolveIngredients(array $item): array
    {
        $measureKey = 'strMeasure';
        $ingredientKey = 'strIngredient';
        $results = [];

        $i = 1;
        while ($ingredient = $item[$ingredientKey.$i] ?? null) {
            $ingredient = trim($ingredient);
            $measure = trim($item[$measureKey.$i] ?? '');

            if ($ingredient) {
                $results[] = new MealIngredientData([
                    'name' => $ingredient,
                    'measure' => $measure,
                ]);
            }

            $i++;
        }

        return $results;
    }
}
