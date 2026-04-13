<?php

namespace Tests\Unit\Resources;

use App\Data\MealItemResourceData;
use App\Http\Resources\MealItemResource;
use App\MealDb\Data\MealItemData;
use App\Models\FavoriteMeal;
use App\Models\User;
use Mockery;
use PHPUnit\Framework\TestCase;

class MealItemResourceTest extends TestCase
{
    public function testCollectionTransforming(): void
    {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')
            ->with('favoriteMeals')
            ->andReturn(collect([
                new FavoriteMeal([
                    'user_id' => 1,
                    'meal_id' => 124,
                ]),
            ]));

        $source = collect([
            new MealItemData([
                'id' => '422',
                'title' => '',
                'category' => '',
                'country' => '',
                'instruction' => '',
            ]),
            new MealItemData([
                'id' => '124',
                'title' => '',
                'category' => '',
                'country' => '',
                'instruction' => '',
            ]),
        ]);

        $target = MealItemResource::collection($source, $user);

        $this->assertInstanceOf(MealItemResourceData::class, $target->first());
        $this->assertEquals(false, $target->first()->is_favorite);
        $this->assertEquals(true, $target->last()->is_favorite);
    }
}
