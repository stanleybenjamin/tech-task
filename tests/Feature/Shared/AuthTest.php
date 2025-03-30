<?php

namespace Tests\Feature\Shared;

use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_authenticated_user_cannot_login(): void
    {
        $user = User::factory()->create();

        Passport::actingAs($user);
        $this->postJson(route('login'))
            ->assertRedirect();
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create();

        $this->postJson(route('login'), [
            'email' => $user->email,
            'password' => 'password'
        ])
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'user',
                    'token'
                ]
            ]);

        $this->assertGuest('api');
    }


    public function guest_user_cannot_log_out(): void
    {
        $user = User::factory()->create();

        Passport::actingAs($user);

        $this->postJson(route('logout'))
            ->assertOk();
    }
}
