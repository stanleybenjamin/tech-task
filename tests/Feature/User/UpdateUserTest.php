<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class UpdateUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_update_a_users_info(): void
    {
        $user = User::factory()->create();

        $this->putJson(route('users.update', $user))
            ->assertUnauthorized();
    }

    public function test_non_admin_cannot_update_a_users_info(): void
    {
        $user = User::factory()->create();
        $non_admin = User::factory()->create();

        Passport::actingAs($non_admin);

        $this->putJson(route('users.update', $user))
            ->assertForbidden();
    }

    public function test_admin_can_update_a_users_info(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $this->putJson(route('users.update', $user), [
            'name' => 'Jane',
            'surname' => 'Doe',
            'email' => 'janedoe@gmail.com',
            'gender' => 'female',
            'country' => 'Italy'
        ])
            ->assertAccepted()
            ->assertJson([
                'data' => [
                    'name' => 'Jane',
                    'surname' => 'Doe',
                    'email' => 'janedoe@gmail.com',
                    'gender' => 'female',
                    'country' => 'Italy'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Jane',
            'surname' => 'Doe',
            'email' => 'janedoe@gmail.com',
            'gender' => 'female',
            'country' => 'Italy'
        ]);
    }


    public function test_admin_can_update_a_users_password(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $this->putJson(route('users.update', $user), [
            'password' => 'password',
            'password_confirmation' => 'password'
        ])
            ->assertAccepted();
    }
}
