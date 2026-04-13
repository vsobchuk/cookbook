<?php

namespace App\MealDb\Transformers;

use App\MealDb\Data\MealFilterItemData;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class FilterResultTransformer
{
    public function transform(Collection $items): Collection
    {
        return $items->map(function (array $item) {
            return new MealFilterItemData([
                'id' => Arr::get($item, 'idMeal'),
                'title' => Arr::get($item, 'strMeal'),
                'imgUrl' => Arr::get($item, 'strMealThumb'),
            ]);
        });
    }
}
