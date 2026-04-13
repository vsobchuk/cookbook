<?php

namespace Tests\Feature\MealDb;

use App\MealDb\MealDbApiClient;
use GuzzleHttp\Psr7\Response;
use Tests\Mixins\InteractWithHttpClient;
use Tests\Mixins\InteractWithMealDbEntities;
use Tests\TestCase;

class MealDbApiClientTest extends TestCase
{
    use InteractWithHttpClient;
    use InteractWithMealDbEntities;

    public function testFindRequest(): void
    {
        $meal = $this->mealsDataProvider()[0];

        $response = new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['meals' => [$meal]]),
        );

        $this->mockHttpClient([$response]);

        $client = $this->app->make(MealDbApiClient::class);

        $result = $client->find($meal['idMeal']);

        $this->assertEquals([$meal], $result);
    }

    public function testSearchRequest(): void
    {
        $this->mockHttpClient([
            new Response(200, ['Content-Type' => 'application/json'], json_encode(['meals' => $this->mealsDataProvider()])),
        ]);

        $client = $this->app->make(MealDbApiClient::class);

        $results = $client->search('example');

        $this->assertCount(count($this->mealsDataProvider()), $results);
    }

    public function testFilterByIngredient(): void
    {
        $this->mockHttpClient([
            new Response(200, ['Content-Type' => 'application/json'], json_encode(['meals' => $this->mealsDataProvider()])),
        ]);

        $client = $this->app->make(MealDbApiClient::class);

        $results = $client->filterByIngredient('example');

        $this->assertCount(count($this->mealsDataProvider()), $results);
    }

    public function testProcessingBadResponse(): void
    {
        $this->mockHttpClient([
            new Response(400, ['Content-Type' => 'text/html'], json_encode(['meals' => $this->mealsDataProvider()])),
        ]);

        $client = $this->app->make(MealDbApiClient::class);

        \Log::shouldReceive('error')
            ->once()
            ->withArgs(function (string $message) use ($client) {
                return str_contains($message, class_basename($client));
            });

        $results = $client->search('example');

        $this->assertEquals([], $results);
    }
}
