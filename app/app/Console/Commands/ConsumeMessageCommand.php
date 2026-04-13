<?php

namespace App\Console\Commands;

use App\Data\RecommendationMessageListMessageData;
use App\Events\UserRecommendationsShouldBeRefreshed;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Junges\Kafka\Contracts\KafkaConsumerMessage;
use Junges\Kafka\Facades\Kafka;
use Throwable;

class ConsumeMessageCommand extends Command
{
    protected $signature = 'broker:consume';

    protected $description = 'Runs listener to message broker';

    const MESSAGE_PRODUCE_RECOMMENDATION_LIST = 'ProduceRecommendationListMessage';

    public function handle(): int
    {
        $consumer = Kafka::createConsumer()->subscribe('webapp');

        $consumer->withHandler(function (KafkaConsumerMessage $message): void {
            $this->handleEvent($message);
        });

        $consumer = $consumer->build();
        $consumer->consume();

        return 0;
    }

    public function extractMessageName(string $eventId): string
    {
        return Arr::first(explode('-', $eventId));
    }

    public function handleEvent(KafkaConsumerMessage $message): void
    {
        $eventId = Arr::get($message->getBody(), 'event_id');

        if (! $eventId) {
            return;
        }

        $messageName = $this->extractMessageName($eventId);

        try {
            switch ($messageName) {
                case self::MESSAGE_PRODUCE_RECOMMENDATION_LIST:
                    $this->info(sprintf('Handling %s event', $messageName));

                    $payload = new RecommendationMessageListMessageData($message->getBody());

                    event(new UserRecommendationsShouldBeRefreshed($payload));
                    break;
            }
        } catch (Throwable $e) {
            Log::error(class_basename($this), [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
                'body' => $message->getBody(),
                'trace' => $e->getTrace(),
            ]);

            $msg = sprintf('%s: %s:%s', $e->getMessage(), $e->getFile(), $e->getLine());
            $this->error('ERROR: '.$msg);
        }
    }
}
