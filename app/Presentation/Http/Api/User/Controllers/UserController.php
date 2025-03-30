<?php

namespace App\Presentation\Http\Api\User\Controllers;

use App\Application\User\DTOs\CreateUserDTO;
use App\Domain\User\Models\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Services\UserService;
use App\Presentation\Http\Resources\User\UserResource;
use App\Presentation\Http\Shared\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function __construct(public UserRepositoryInterface $repository){}

    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('admin');

        $dto = CreateUserDTO::validateAndCreate($request->toArray());

        $user = (new UserService($this->repository))
            ->createUser($dto);

        return apiSuccess(
            UserResource::make($user),
            "User created successfully.",
            Response::HTTP_CREATED
        );
    }

    public function show(User $user): JsonResponse
    {
        Gate::authorize('admin');

        return apiSuccess(
            UserResource::make($user),
            'User retrieved successfully'
        );
    }
}
