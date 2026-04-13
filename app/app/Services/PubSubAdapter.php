<?php

namespace App\Services;

use App\Contracts\SerializableMessage;
use Illuminate\Support\Str;
use Junges\Kafka\Facades\Kafka;
use Junges\Kafka\Message\Message;

class PubSubAdapter
{
    private $topic = 'webapp';

    private SerializableMessage $messageBody;

    public function message(SerializableMessage $message): static
    {
        $this->messageBody = $message;

        return $this;
    }

    public function topic(string $topic): static
    {
        $this->topic = $topic;

        return $this;
    }

    public function produce(): void
    {
        $message = new Message(
            body: $this->messageBody,
        );

        $producer = Kafka::publishOn($this->topic)
            ->withMessage($message);

        $producer->send();
    }

    public function makeEventId(string $subject): string
    {
        return $subject.'-'.Str::uuid();
    }
}
