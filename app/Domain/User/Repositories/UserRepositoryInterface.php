<?php

namespace App\Domain\User\Repositories;

use App\Application\User\DTOs\CreateUserDTO;
use App\Domain\User\Models\User;

interface UserRepositoryInterface
{
    /**
     * Summary of create
     * @param \App\Application\User\DTOs\CreateUserDTO $dto
     * @return User
     */
    public function create(CreateUserDTO $dto): User;
}