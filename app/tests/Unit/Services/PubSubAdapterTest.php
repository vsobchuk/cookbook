<?php

namespace Tests\Unit\Services;

use App\Contracts\SerializableMessage;
use App\Data\RecommendationMessageListMessageData;
use App\Services\PubSubAdapter;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;
use Tests\TestCase;

class PubSubAdapterTest extends TestCase
{
    private ?PubSubAdapter $service;

    private ?SerializableMessage $message;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new PubSubAdapter();

        $this->message = new RecommendationMessageListMessageData([
            'event_id' => '::event_id::',
            'user_id' => 1,
            'ingredients' => [],
        ]);
    }

    public function testMessage(): void
    {
        $this->assertEquals($this->service, $this->service->message($this->message));
    }

    public function testTopic(): void
    {
        $this->assertEquals($this->service, $this->service->topic('topic'));
    }

    public function testProduce(): void
    {
        Kafka::fake();

        $this->service
            ->message($this->message)
            ->produce();

        $brokerMessage = new Message(body: $this->message);

        Kafka::assertPublished($brokerMessage);
    }

    public function testMakeEventId(): void
    {
        $eventId = $this->service->makeEventId('APP');

        $this->assertStringStartsWith('APP', $eventId);
        $this->assertStringEndsNotWith('APP', $eventId);
        $this->assertGreaterThan(10, strlen($eventId));
    }
}
