<?php

namespace App\Domain\User\Services;

use App\Application\User\DTOs\CreateUserDTO;
use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Http\UploadedFile;

class UserService
{
    public function __construct(
        public UserRepositoryInterface $repository
    ){}

    /**
     * 
     * @param \App\Application\User\DTOs\CreateUserDTO $dto
     * @return User
     */
    public function createUser(CreateUserDTO $dto): User
    {
        if ($dto->selfie) {
            $dto->selfie = $this->uploadProfilePhoto($dto->selfie);
        }
        return $this->repository->create($dto);
    }

    public function uploadProfilePhoto(UploadedFile $photo): ?string
    {
        /** @var string|false */
        $path = $photo->store('profile-photos', 'public');

        if (!$path) {
            return null;
        }

        return $path;
    }
}