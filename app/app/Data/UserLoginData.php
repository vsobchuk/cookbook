<?php

namespace App\Data;

use Spatie\DataTransferObject\DataTransferObject;

class UserLoginData extends DataTransferObject
{
    public string $email;

    public string $password;

    public bool $remember;

    public string $ip;
}
