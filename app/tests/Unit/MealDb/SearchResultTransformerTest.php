<?php

namespace Tests\Unit\MealDb;

use App\MealDb\Data\MealIngredientData;
use App\MealDb\Data\MealItemData;
use App\MealDb\Transformers\SearchResultTransformer;
use PHPUnit\Framework\TestCase;
use Tests\Mixins\InteractWithMealDbEntities;

class SearchResultTransformerTest extends TestCase
{
    use InteractWithMealDbEntities;

    public function testSearchResultTransformerClass(): void
    {
        $transformer = new SearchResultTransformer();

        $results = $transformer->transform(collect($this->mealsDataProvider()));

        $this->assertEquals(2, $results->count());

        /** @var MealItemData $meal1 */
        $meal1 = $results->first();
        $this->assertInstanceOf(MealItemData::class, $meal1);
        $this->assertInstanceOf(MealIngredientData::class, $meal1->ingredients[0]);
        $this->assertEquals('53061', $meal1->id);
        $this->assertNotEmpty($meal1->title);
        $this->assertNotEmpty($meal1->imgUrl);
        $this->assertEquals(4, count($meal1->ingredients));

        $this->assertNotEmpty($meal1->ingredients[0]->name);
        $this->assertNotEmpty($meal1->ingredients[0]->measure);

        /** @var MealItemData $meal2 */
        $meal2 = $results->last();
        $this->assertEquals('Corba', $meal2->title);
        $this->assertEquals(13, count($meal2->ingredients));
    }
}
