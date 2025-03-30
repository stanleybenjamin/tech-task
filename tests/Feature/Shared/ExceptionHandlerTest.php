<?php

namespace Tests\Feature\Shared;

use App\Presentation\Http\Shared\ExceptionHandler;
use ErrorException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Tests\TestCase;

class ExceptionHandlerTest extends TestCase
{
    public function test_method_not_allowed_exception(): void
    {
        $response = (new ExceptionHandler)->handle(
            new Request(server: [
                'REQUEST_METHOD' => 'GET'
            ]),
            new MethodNotAllowedException(["POST"])
        );

        $this->assertEquals($response->status(), Response::HTTP_METHOD_NOT_ALLOWED);

        $this->assertFalse($response->getData()->status);
    }

    public function test_not_found_exception(): void
    {
        $response = (new ExceptionHandler)->handle(
            new Request(server: [
                'REQUEST_METHOD' => 'GET'
            ]),
            new NotFoundHttpException("Not Found")
        );

        $this->assertEquals($response->status(), Response::HTTP_NOT_FOUND);

        $this->assertFalse($response->getData()->status);
    }

    public function test_route_not_found_exception(): void
    {
        $response = (new ExceptionHandler)->handle(
            new Request(server: [
                'REQUEST_METHOD' => 'GET'
            ]),
            new RouteNotFoundException()
        );

        $this->assertEquals($response->status(), Response::HTTP_NOT_FOUND);

        $this->assertFalse($response->getData()->status);
    }

    public function test_unauthorized_exception(): void
    {
        $response = (new ExceptionHandler)->handle(
            new Request(server: [
                'REQUEST_METHOD' => 'POST'
            ]),
            new AuthorizationException('Forbidden')
        );

        $this->assertEquals($response->status(), Response::HTTP_FORBIDDEN);

        $this->assertFalse($response->getData()->status);
    }

    public function test_authentication_exception(): void
    {
        $response = (new ExceptionHandler)->handle(
            new Request(server: [
                'REQUEST_METHOD' => 'GET'
            ]),
            new AuthenticationException()
        );

        $this->assertEquals($response->status(), Response::HTTP_UNAUTHORIZED);

        $this->assertFalse($response->getData()->status);
    }

    public function test_model_not_found_exception(): void
    {
        $response = (new ExceptionHandler)->handle(
            new Request(server: [
                'REQUEST_METHOD' => 'GET'
            ]),
            new ModelNotFoundException()
        );

        $this->assertEquals($response->status(), Response::HTTP_NOT_FOUND);

        $this->assertFalse($response->getData()->status);
    }

    public function test_throttle_exception(): void
    {
        $response = (new ExceptionHandler)->handle(
            new Request(server: [
                'REQUEST_METHOD' => 'GET'
            ]),
            new ThrottleRequestsException
        );

        $this->assertEquals($response->status(), Response::HTTP_TOO_MANY_REQUESTS);

        $this->assertFalse($response->getData()->status);
    }

    public function test_bad_request_exception(): void
    {
        $response = (new ExceptionHandler)->handle(
            new Request(server: [
                'REQUEST_METHOD' => 'GET'
            ]),
            new BadRequestException()
        );

        $this->assertEquals($response->status(), Response::HTTP_BAD_REQUEST);

        $this->assertFalse($response->getData()->status);
    }


    public function test_validation_exception(): void
    {
        $response = (new ExceptionHandler)->handle(
            new Request(server: [
                'REQUEST_METHOD' => 'GET'
            ]),
            ValidationException::withMessages([
                'name' => ['The name field is required.']
            ])
        );

        $this->assertEquals($response->status(), Response::HTTP_UNPROCESSABLE_ENTITY);

        $this->assertFalse($response->getData()->status);

        $this->assertEquals(
            $response->getData()->data->name,
            ['The name field is required.']
        );
    }

    public function test_random_exception_with_status_code(): void
    {
        $response = (new ExceptionHandler)->handle(
            new Request(server: [
                'REQUEST_METHOD' => 'GET'
            ]),
            new NotAcceptableHttpException('Request not acceptable')
        );

        $this->assertEquals($response->status(), Response::HTTP_NOT_ACCEPTABLE);

        $this->assertFalse($response->getData()->status);
    }

    public function test_server_exception(): void
    {
        $response = (new ExceptionHandler)->handle(
            new Request(server: [
                'REQUEST_METHOD' => 'GET'
            ]),
            new ErrorException()
        );

        $this->assertEquals($response->status(), Response::HTTP_INTERNAL_SERVER_ERROR);

        $this->assertFalse($response->getData()->status);
    }
}
