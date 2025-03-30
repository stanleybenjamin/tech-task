<?php

namespace App\Infrastructure\Repositories;

use App\Domain\User\Models\User;
use App\Application\User\DTOs\CreateUserDTO;
use App\Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

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


    /**
     *
     * @param int $per_page
     * @return LengthAwarePaginator
     */
    public function paginate(int $per_page): LengthAwarePaginator
    {
        return User::orderByDesc('created_at')
            ->paginate($per_page);
    }


    /**
     *
     * @param \App\Domain\User\Models\User $user
     * @return bool|null
     */
    public function delete(User $user): ?bool
    {
        return $user->delete();
    }
}
