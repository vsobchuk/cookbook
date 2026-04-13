<?php

namespace App\Contracts;

interface SerializableMessage
{
    public function toArray(): array;
}
