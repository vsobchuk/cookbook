<?php

namespace App\MealDb;

use App\MealDb\Data\MealItemData;
use App\MealDb\Transformers\FilterResultTransformer;
use App\MealDb\Transformers\SearchResultTransformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MealDbRepository
{
    public function __construct(private MealDbApiClient $client)
    {
    }

    public function find(string $mealId): ?MealItemData
    {
        $results = $this->client->find($mealId);

        return $this->transformMeals($results)->first();
    }

    public function search(string $searchQuery): Collection
    {
        $results = $this->client->search($searchQuery);

        return $this->transformMeals($results);
    }

    public function filterByIngredient(string $ingredient): Collection
    {
        $ingredient = Str::snake($ingredient);

        $results = $this->client->filterByIngredient($ingredient);

        return (new FilterResultTransformer())
            ->transform(collect($results));
    }

    private function transformMeals(array $list): Collection
    {
        return (new SearchResultTransformer())
            ->transform(collect($list));
    }
}
