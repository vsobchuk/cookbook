<?php

namespace App\Repositories;

use App\Models\User;
use App\Queries\UserQueryBuilder;

class UserRepository
{
    public function find(int|string $userId): ?User
    {
        return $this->query()->find($userId);
    }

    private function query(): UserQueryBuilder
    {
        return app(User::class)->query();
    }
}
