<?php

namespace App\Events;

use App\Data\RecommendationMessageListMessageData;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRecommendationsShouldBeRefreshed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public RecommendationMessageListMessageData $payload)
    {
    }
}
