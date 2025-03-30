<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Application;
use App\Presentation\Http\Shared\ExceptionHandler;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->respond(function (Response|RedirectResponse|JsonResponse $response, Throwable $exception, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                $apiResponse = (new ExceptionHandler)->handle($request, $exception);

                if($apiResponse){
                    return $apiResponse;
                }

                return $exception;
            }

            return $response;
        });
    })->create();
