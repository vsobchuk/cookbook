<?php

namespace Tests\Unit\Commands;

use App\Console\Commands\ConsumeMessageCommand;
use App\Events\UserRecommendationsShouldBeRefreshed;
use App\Services\PubSubAdapter;
use Illuminate\Support\Facades\Event;
use Junges\Kafka\Message\ConsumedMessage;
use Mockery;
use Tests\TestCase;

class ConsumeMessageCommandTest extends TestCase
{
    private ?ConsumeMessageCommand $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->command = Mockery::mock(ConsumeMessageCommand::class)->makePartial();

        $this->command->shouldReceive('error');
        $this->command->shouldReceive('info');
    }

    public function testHandleEvent(): void
    {
        Event::fake();

        $eventId = (new PubSubAdapter())
            ->makeEventId(ConsumeMessageCommand::MESSAGE_PRODUCE_RECOMMENDATION_LIST);

        $body = [
            'event_id' => $eventId,
            'user_id' => 1,
            'ingredients' => [],
        ];

        $message = new ConsumedMessage('topic', 1, [], $body, 'example', null, null);

        $this->command->handleEvent($message);

        Event::assertDispatched(UserRecommendationsShouldBeRefreshed::class);
    }

    public function testExtractMessageName(): void
    {
        $messageName = ConsumeMessageCommand::MESSAGE_PRODUCE_RECOMMENDATION_LIST;
        $eventId = "{$messageName}-1244-abcd-dcba-4421";

        $this->assertEquals($messageName, $this->command->extractMessageName($eventId));
    }
}
