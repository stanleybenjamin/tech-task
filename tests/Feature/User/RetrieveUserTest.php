<?php

namespace Tests\Feature\User;

use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class RetrieveUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_view_users_list(): void
    {
        $this->getJson(route('users.index'))
            ->assertUnauthorized();
    }

    public function test_non_admin_cannot_view_users_list(): void
    {
        $user = User::factory()->create();

        Passport::actingAs($user);
        $this->getJson(route('users.index'))
            ->assertForbidden();
    }

    public function test_admin_can_view_users_list(): void
    {
        $admin = User::factory()->admin()->create();

        User::factory(45)->create();

        Passport::actingAs($admin);
        $this->getJson(route('users.index'))
            ->assertOk()
            ->assertJsonCount(20, 'data');
    }

    public function test_guest_cannot_view_a_users_info(): void
    {
        $user = User::factory()->create();

        $this->getJson(route('users.show', $user))
            ->assertUnauthorized();
    }

    public function test_non_admin_cannot_view_a_users_info(): void
    {
        $user = User::factory()->create();
        $non_admin_user = User::factory()->create();

        Passport::actingAs($non_admin_user);
        $this->getJson(route('users.show', $user))
            ->assertForbidden();
    }

    public function test_admin_can_view_a_users_info(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);

        $this->getJson(route('users.show', $user))
            ->assertOk()
            ->assertJson([
                'data' => [
                    'name' => $user->name,
                    'surname' => $user->surname,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'gender' => $user->gender,
                    'country' => $user->country,
                    'is_admin' => false
                ]
            ]);
    }
}
