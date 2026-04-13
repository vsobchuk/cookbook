<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface Transformable
{
    public function transform(Collection $collection): Collection;
}
