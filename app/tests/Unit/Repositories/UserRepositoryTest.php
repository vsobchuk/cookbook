<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Queries\UserQueryBuilder;
use App\Repositories\UserRepository;
use Mockery;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    public function testFind(): void
    {
        $userModel = User::factory()->make();
        $userModel->id = 1;

        $queryBuilder = Mockery::mock(UserQueryBuilder::class);
        $queryBuilder->shouldReceive('find')
            ->once()
            ->with($userModel->id)
            ->andReturn($userModel);

        $userInstance = Mockery::mock(User::class);
        $userInstance
            ->shouldReceive('query')
            ->andReturn($queryBuilder);

        $this->instance(User::class, $userInstance);

        $this->assertEquals($userModel, (new UserRepository())->find($userModel->id));
    }
}
