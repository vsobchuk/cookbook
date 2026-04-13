<?php

namespace App\MealDb;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class MealDbApiClient
{
    public function __construct(private Client $client)
    {
    }

    public function find(string $id): array
    {
        $response = $this->request('GET', 'lookup.php', [
            'query' => [
                'i' => $id,
            ],
        ]);

        return $response['meals'] ?? [];
    }

    public function search(string $searchQuery): array
    {
        $response = $this->request('GET', 'search.php', [
            'query' => [
                's' => $searchQuery,
            ],
        ]);

        return $response['meals'] ?? [];
    }

    public function filterByIngredient(string $ingredientName): array
    {
        $response = $this->request('GET', 'filter.php', [
            'query' => [
                'i' => $ingredientName,
            ],
        ]);

        return $response['meals'] ?? [];
    }

    private function request(string $method, string $path, array $options = []): array
    {
        try {
            $response = $this->client->request($method, $this->baseUri().$path, $options);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException|RequestException $e) {
            \Log::error(class_basename($this), [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'body' => $e->getResponse()->getBody()->getContents(),
            ]);
        }

        return [];
    }

    private function baseUri(): string
    {
        return 'https://www.themealdb.com/api/json/v1/1/';
    }
}
