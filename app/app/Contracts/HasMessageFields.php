<?php

namespace App\Contracts;

trait HasMessageFields
{
    public string $event_id;

    public string $event_producer = 'cookbook';
}
