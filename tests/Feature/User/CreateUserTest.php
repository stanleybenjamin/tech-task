<?php

namespace Tests\Feature\User;

use Laravel\Passport\Passport;
use Tests\TestCase;
use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateUserTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_guest_cannot_create_user(): void
    {
        $this->postJson(route('users.store'))
            ->assertUnauthorized();
    }

    public function test_non_admin_cannot_create_user(): void
    {
        $user = User::factory()->create();

        Passport::actingAs($user);
        $this->postJson(route('users.store'))
            ->assertForbidden();
    }

    public function test_required_fields_to_create_a_user(): void
    {
        $user = User::factory()->admin()
            ->create();

        Passport::actingAs($user);
        $this->postJson(route('users.store'))
            ->assertUnprocessable();
    }

    public function test_admin_can_create_a_user(): void
    {
        $user = User::factory()->admin()
            ->create();

        Passport::actingAs($user);
        $this->postJson(route('users.store'), [
                'name' => 'John',
                'surname' => 'Doe',
                'email' => 'johndoe@gmail.com',
                'phone' => $this->faker->phoneNumber(),
                'gender' => 'male',
                'country' => 'United States',
                'password' => 'password',
                'password_confirmation' => 'password'
            ])
            ->assertCreated();

        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'johndoe@gmail.com',
            'gender' => 'male',
            'country' => 'United States'
        ]);
    }
}
