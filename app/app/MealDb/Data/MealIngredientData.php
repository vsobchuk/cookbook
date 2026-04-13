<?php

namespace App\MealDb\Data;

use Spatie\DataTransferObject\DataTransferObject;

class MealIngredientData extends DataTransferObject
{
    public string $name;

    public string $measure;
}
