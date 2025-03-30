<?php

namespace App\Infrastructure\Repositories;

use App\Domain\User\Models\User;
use App\Application\User\DTOs\CreateUserDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     *
     * @param \App\Application\User\DTOs\CreateUserDTO $dto
     * @return User
     */
    public function create(CreateUserDTO $dto): User
    {
        return User::create($dto->toArray());
    }
}
