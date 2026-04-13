<?php

namespace App\Data;

use App\Contracts\HasMessageFields;
use App\Contracts\SerializableMessage;
use Spatie\DataTransferObject\DataTransferObject;

class RecommendationMessageListMessageData extends DataTransferObject implements SerializableMessage
{
    use HasMessageFields;

    public int $user_id;

    /** @var string[] */
    public array $ingredients;
}
