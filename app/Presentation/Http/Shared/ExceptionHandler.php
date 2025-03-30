<?php

namespace App\Presentation\Http\Shared;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class ExceptionHandler
{
    public function handle(Request $request, Throwable $exception): JsonResponse
    {
        if ($exception instanceof MethodNotAllowedException) {
            return apiError([], strtoupper($request->getMethod()) . ' method is not allowed for this endpoint.',Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($exception instanceof NotFoundHttpException) {
            return apiError([], $exception->getMessage(), Response::HTTP_NOT_FOUND);
        }

         if($exception instanceof RouteNotFoundException){
            return apiError([], 'The given endpoint does not exist.', Response::HTTP_NOT_FOUND);
        }

        if($exception instanceof AuthorizationException){
            return apiError([], $exception->getMessage(), Response::HTTP_FORBIDDEN);
        }

        if($exception instanceof AuthenticationException){
            return apiError([], "Login to perform this action.", Response::HTTP_UNAUTHORIZED);
        }

        if($exception instanceof ModelNotFoundException){
            return apiError([], 'The requested resource does not exist.', Response::HTTP_NOT_FOUND);
        }

        if($exception instanceof ThrottleRequestsException){
            return apiError([], 'Max number of attempts exceeded.', Response::HTTP_TOO_MANY_REQUESTS);
        }

        if($exception instanceof BadRequestException){
            return apiError([], 'Bad request.', Response::HTTP_BAD_REQUEST);
        }

        if($exception instanceof ValidationException){
            return apiError($exception->errors(), $exception->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if(method_exists($exception, 'getStatusCode')){
            return apiError([], $exception->getMessage(), $exception->getStatusCode());
        }

        return apiError([], 'A technical error occurred.', Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
