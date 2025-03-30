<?php

namespace App\Presentation\Http\Api\Shared;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Domain\User\Services\UserService;
use App\Presentation\Http\Shared\Controller;
use Illuminate\Validation\ValidationException;
use App\Presentation\Http\Resources\User\UserResource;

class AuthController extends Controller
{
    public function __construct(private UserService $service)
    {
    }


    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255'
        ]);

        $user = $this->service->findByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Incorrect email/password combinations'
            ]);
        }

        $token = $user->createToken('api access token')->accessToken;

        return apiSuccess([
            'user' => UserResource::make($user),
            'token' => $token
        ]);
    }
}
