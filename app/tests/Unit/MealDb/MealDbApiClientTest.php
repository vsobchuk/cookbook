<?php

namespace Tests\Unit\MealDb;

use App\MealDb\MealDbApiClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class MealDbApiClientTest extends TestCase
{
    public function testFind(): void
    {
        $responses = [
            new Response(200, [], json_encode(['meals' => [true]])),
        ];

        $client = new MealDbApiClient($this->mockHttpClient($responses));

        $result = $client->find('::id::');

        $this->assertEquals([true], $result);
    }

    public function testSearch(): void
    {
        $responses = [
            new Response(200, [], json_encode(['meals' => [true]])),
        ];

        $client = new MealDbApiClient($this->mockHttpClient($responses));

        $result = $client->search('example');

        $this->assertEquals([true], $result);
    }

    public function testFilterByIngredient(): void
    {
        $responses = [
            new Response(200, [], json_encode(['meals' => [true]])),
        ];

        $client = new MealDbApiClient($this->mockHttpClient($responses));

        $result = $client->filterByIngredient('example');

        $this->assertEquals([true], $result);
    }

    private function mockHttpClient(array $responses): Client
    {
        $mock = new MockHandler($responses);

        $handler = HandlerStack::create($mock);

        return new Client(['handler' => $handler]);
    }
}
