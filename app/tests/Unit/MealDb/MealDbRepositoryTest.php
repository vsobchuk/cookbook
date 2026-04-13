<?php

namespace Tests\Unit\MealDb;

use App\MealDb\MealDbApiClient;
use App\MealDb\MealDbRepository;
use Mockery;
use PHPUnit\Framework\TestCase;

class MealDbRepositoryTest extends TestCase
{
    private ?MealDbApiClient $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = Mockery::mock(MealDbApiClient::class);
    }

    public function testFind(): void
    {
        $this->client
            ->shouldReceive('find')
            ->once()
            ->andReturn([]);

        $repository = new MealDbRepository($this->client);

        $repository->find('::id::');

        $this->assertTrue(true);
    }

    public function testSearch(): void
    {
        $this->client
            ->shouldReceive('search')
            ->once()
            ->andReturn([]);

        $repository = new MealDbRepository($this->client);

        $repository->search('example');

        $this->assertTrue(true);
    }

    public function testFilterByIngredient(): void
    {
        $this->client
            ->shouldReceive('filterByIngredient')
            ->once()
            ->withArgs(function ($arg) {
                $this->assertEquals('sea_salt', $arg);

                return true;
            })
            ->andReturn([]);

        $repository = new MealDbRepository($this->client);

        $repository->filterByIngredient('Sea Salt');
    }
}
