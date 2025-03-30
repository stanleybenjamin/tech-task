<?php

namespace App\Domain\User\Repositories;

use App\Application\User\DTOs\CreateUserDTO;
use App\Domain\User\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    /**
     *
     * @param \App\Application\User\DTOs\CreateUserDTO $dto
     * @return User
     */
    public function create(CreateUserDTO $dto): User;

    /**
     *
     * @param int $per_page
     * @return void
     */
    public function paginate(int $per_page): LengthAwarePaginator;
}
