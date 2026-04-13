<?php

namespace Tests\Feature\Http;

use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\Mixins\InteractWithHttpClient;
use Tests\Mixins\InteractWithMealDbEntities;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;
    use InteractWithHttpClient;
    use InteractWithMealDbEntities;

    public function testSearchAsGuest(): void
    {
        $this->get('/search?q=example')
            ->assertRedirect(route('login'));
    }

    public function testSearchAsUser(): void
    {
        $this->actingAs(User::factory()->create());

        $this->mockHttpClient([
            new Response(200, [], json_encode(['meals' => $this->mealsDataProvider()])),
        ]);

        $meal = collect($this->mealsDataProvider())->first();
        $title = Arr::get($meal, 'strMeal');

        $this->get('/search?q=example')
            ->assertStatus(200)
            ->assertSee('example')
            ->assertSee($title);
    }
}
