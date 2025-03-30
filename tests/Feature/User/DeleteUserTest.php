<?php

namespace Tests\Feature\User;

use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_delete_a_user(): void
    {
        $user = User::factory()->create();

        $this->deleteJson(route('users.destroy', $user))
            ->assertUnauthorized();
    }

    public function test_non_admin_cannot_delete_a_user(): void
    {
        $user = User::factory()->create();
        $non_admin = User::factory()->create();

        Passport::actingAs($non_admin);
        $this->deleteJson(route('users.destroy', $user))
            ->assertForbidden();
    }

    public function test_admin_can_delete_a_user(): void
    {
        $user = User::factory()->create();

        $admin = User::factory()->admin()->create();

        Passport::actingAs($admin);
        $this->deleteJson(route('users.destroy', $user))
            ->assertNoContent();

        $this->assertDatabaseMissing('users', [
            'email' => $user->email,
            'phone' => $user->phone,
            'name' => $user->name,
            'surname' => $user->surname
        ]);

        $this->assertDatabaseCount('users', 1);
    }
}
