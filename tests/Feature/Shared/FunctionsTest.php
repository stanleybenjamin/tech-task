<?php

namespace Tests\Feature\Shared;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FunctionsTest extends TestCase
{
    public function test_api_success_response(): void
    {
        $response = apiSuccess([
            'name' => 'John'
        ], 'Successful');

        $data = json_decode($response->getContent());
        $this->assertTrue($data->status);
        $this->assertEquals($data->data, (object)[
            'name' => 'John'
        ]);
        $this->assertEquals($data->message, 'Successful');
        $this->assertEquals($response->status(), 200);
    }

    public function test_api_error_response(): void
    {
        $response = apiError([], 'An error occurred');

        $data = json_decode($response->getContent());
        $this->assertFalse($data->status);
        $this->assertEmpty($data->data);
        $this->assertEquals($data->message, 'An error occurred');
        $this->assertEquals($response->status(), 400);
    }
}
