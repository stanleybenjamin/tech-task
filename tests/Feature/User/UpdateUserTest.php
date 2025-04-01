<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Domain\User\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

        $this->putJson(route('users.update-password', $user), [
            'password' => 'password',
            'password_confirmation' => 'password'
        ])
            ->assertAccepted();
    }

    public function test_admin_can_update_a_users_selfie(): void
    {
        $user = User::factory()->create(['profile_photo' => null]);
        $admin = User::factory()->admin()->create();

        Storage::fake('public');

        Passport::actingAs($admin);

        $this->postJson(route('users.update-selfie', $user), [
            'selfie' => UploadedFile::fake()->image('selfie.png', 520, 520)
        ])
            ->assertAccepted();

        $this->assertNotNull($user->refresh()->profile_photo);

        Storage::disk('public')->assertCount('profile-photos', 1);
    }
}
