<?php

namespace App\Domain\User\Services;

use App\Application\User\DTOs\CreateUserDTO;
use App\Application\User\DTOs\UpdateUserDTO;
use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function __construct(
        public UserRepositoryInterface $repository
    ){}

    /**
     * Paginated users list
     * @param int $per_page
     * @return LengthAwarePaginator
     */
    public function paginated(int $per_page): LengthAwarePaginator
    {
        return $this->repository->paginate($per_page);
    }

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

    /**
     *
     * @param \Illuminate\Http\UploadedFile $photo
     * @return bool|string|null
     */
    public function uploadProfilePhoto(UploadedFile $photo): ?string
    {
        /** @var string|false */
        $path = $photo->store('profile-photos', 'public');

        if (!$path) {
            return null;
        }

        return $path;
    }


    /**
     *
     * @param \App\Domain\User\Models\User $user
     * @return bool
     */
    public function deleteUser(User $user): bool
    {
        if (!$this->deleteProfilePhoto($user)) {
            return false;
        };

        return (bool)$this->repository->delete($user);
    }

    /**
     *
     * @param \App\Domain\User\Models\User $user
     * @return bool
     */
    public function deleteProfilePhoto(User $user): bool
    {
        if (!$user->profile_photo) {
            return true;
        }

        if (!Storage::disk('public')->exists($user->profile_photo)) {
            return true;
        }

        return Storage::disk('public')->delete($user->profile_photo);
    }

    /**
     *
     * @param \App\Domain\User\Models\User $user
     * @param \App\Application\User\DTOs\UpdateUserDTO $data
     * @return bool
     */
    public function update(User $user, UpdateUserDTO $data): bool
    {
        return $this->repository->update($user, $data->toArray());
    }


    /**
     *
     * @param \App\Domain\User\Models\User $user
     * @param string $password
     * @return bool
     */
    public function updatePassword(User $user, string $password): bool
    {
        return $this->repository->update($user, ['password' => $password]);
    }


    /**
     * 
     * @param \App\Domain\User\Models\User $user
     * @param \Illuminate\Http\UploadedFile $selfie
     * @return bool
     */
    public function updateSelfie(User $user, UploadedFile $selfie): bool
    {
        if (!$this->deleteProfilePhoto($user)) {
            return false;
        };

        $photo = $this->uploadProfilePhoto($selfie);

        if (!$photo) {
            return false;
        }

        return $this->repository->update($user, [
            'profile_photo' => $photo
        ]);
    }
}
