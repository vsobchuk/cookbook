<?php

namespace Tests\Feature\Http;

use App\Events\UserFavoriteListChanged;
use App\Models\FavoriteMeal;
use App\Models\User;
use App\Services\MealService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class MealRemoveFavoriteControllerTest extends TestCase
{
    use RefreshDatabase;

    private ?User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->user = User::factory()->create();

        app(MealService::class)->favorite('1122', $this->user);
        app(MealService::class)->favorite('2233', $this->user);
    }

    public function testRequestAsGuest(): void
    {
        $this->delete(route('meals.favorite', ['mealId' => '1122']))
            ->assertRedirect(route('login'));
    }

    public function testRemoveMealFromFavorites(): void
    {
        $this->actingAs($this->user);

        $this->delete(route('meals.favorite', ['mealId' => '2233']))
            ->assertStatus(200);

        Event::assertDispatched(UserFavoriteListChanged::class);

        // trying to remove same record twice
        $this->delete(route('meals.favorite', ['mealId' => '2233']))
            ->assertStatus(200);

        Event::assertDispatched(UserFavoriteListChanged::class);

        $this->assertDatabaseCount(FavoriteMeal::class, 1);
        $this->assertDatabaseMissing(FavoriteMeal::class, ['meal_id' => '2233']);
    }
}
