<?php

namespace App\Presentation\Http\Api\Shared;

use App\Domain\Shared\Services\CountryService;
use App\Presentation\Http\Shared\Controller;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    public function __construct(
        private CountryService $service
    ){}

    /**
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return apiSuccess($this->service->all());
    }
}
