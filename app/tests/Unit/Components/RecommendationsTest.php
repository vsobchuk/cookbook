<?php

namespace Tests\Unit\Components;

use App\MealDb\MealDbRepository;
use App\View\Components\Recommendations;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Mockery;
use Tests\TestCase;

class RecommendationsTest extends TestCase
{
    public function testRender(): void
    {
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('user')
            ->andReturn(null);

        $repository = Mockery::mock(MealDbRepository::class);

        $component = new Recommendations($request, $repository);

        $this->assertInstanceOf(View::class, $component->render());
        $this->assertEquals('components.recommendations', $component->render()->name());
        $this->assertInstanceOf(Collection::class, $component->items);
        $this->assertCount(0, $component->items);
    }
}
