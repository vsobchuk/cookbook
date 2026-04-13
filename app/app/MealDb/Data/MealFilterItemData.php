<?php

namespace App\MealDb\Data;

use Spatie\DataTransferObject\DataTransferObject;

class MealFilterItemData extends DataTransferObject
{
    public string $id;

    public string $title;

    public ?string $imgUrl;
}
