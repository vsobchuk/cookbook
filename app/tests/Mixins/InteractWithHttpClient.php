<?php

namespace Tests\Mixins;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

trait InteractWithHttpClient
{
    public function mockHttpClient(array $responses): void
    {
        $this->app->bind(Client::class, function () use ($responses) {
            $mock = new MockHandler($responses);

            $handler = HandlerStack::create($mock);

            return new Client(['handler' => $handler]);
        });
    }
}
