<?php

namespace Tests\Feature;

use App\Jobs\SendRecoverPasswordEmailJob;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_registration()
    {
        $store = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => 'caUzinha@1857',
            'password_confirmation' => 'caUzinha@1857',
            'term_aceite' => 1,
            'cep' => '72316100',
            'address' => '72316100',
            'house' => '72316100',
            'neighborhood' => '72316100',
        ];

        $response = $this->post('/api/v1/user/register', $store);

        $response->assertStatus(201);
    }

    public function test_user_authentication()
    {
        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
            'email_verified_at' => now(),
        ]);

        $credentials = [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ];

        $response = $this->post('/api/v1/user/sessions', $credentials);
        $response->assertStatus(200);
    }

    public function test_show_user()
    {
        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
            'email_verified_at' => now(),
        ]);

        $credentials = [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ];

        $loginResponse = $this->postJson('/api/v1/user/sessions', $credentials);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('data.token');

        $response1 = $this->getJson('/api/v1/user/show', [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(200);
    }
    public function test_user_show_member()
    {
        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
            'email_verified_at' => now(),
        ]);

        $credentials = [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ];

        $loginResponse = $this->postJson('/api/v1/user/sessions', $credentials);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('data.token');

        $response1 = $this->getJson('/api/v1/user/showMember', [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(200);
    }
    public function test_user_show_leader()
    {
        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
            'email_verified_at' => now(),
        ]);

        $credentials = [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ];

        $loginResponse = $this->postJson('/api/v1/user/sessions', $credentials);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('data.token');

        $response1 = $this->getJson('/api/v1/user/showLeader', [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(200);
    }

    public function test_user_update()
    {
        User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
            'email_verified_at' => now(),
        ]);

        $credentials = [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ];

        $loginResponse = $this->postJson('/api/v1/user/sessions', $credentials);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('data.token');

        $response1 = $this->putJson('/api/v1/user/update',  [
            'email' => 'teste@example.com',
            'password' => 'strongPassword@1231254124',
        ], [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(200);
    }
    public function test_user_update_error()
    {
        User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
            'email_verified_at' => now(),
        ]);

        $credentials = [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ];

        $loginResponse = $this->postJson('/api/v1/user/sessions', $credentials);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('data.token');

        $response1 = $this->putJson('/api/v1/user/update',  [
            'email' => 'teste@example.com',
            'password' => 'strongPassword@12312541214',
        ], [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(401)
            ->assertJson(['message' => 'password incorrect']);
    }

    public function test_user_update_role_leader()
    {
        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
            'email_verified_at' => now(),
        ]);

        $credentials = [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ];

        $loginResponse = $this->postJson('/api/v1/user/sessions', $credentials);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('data.token');

        $response1 = $this->putJson("/api/v1/user/updateRole/{$user->idUser}",  [
            'role' => 'leader',
        ], [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(200);
    }

    public function test_user_update_role_member()
    {
        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
            'email_verified_at' => now(),
        ]);

        $credentials = [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ];

        $loginResponse = $this->postJson('/api/v1/user/sessions', $credentials);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('data.token');

        $response1 = $this->putJson("/api/v1/user/updateRole/{$user->idUser}",  [
            'role' => 'member',
        ], [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(200);
    }

    public function test_user_update_role_invalid()
    {
        $user = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
            'email_verified_at' => now(),
        ]);

        $credentials = [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ];

        $loginResponse = $this->postJson('/api/v1/user/sessions', $credentials);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('data.token');

        $response1 = $this->putJson("/api/v1/user/updateRole/{$user->idUser}",  [
            'role' => 'member1',
            'id'
        ], [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(422);
    }

    public function test_user_send_email_verify()
    {
        $user = User::factory()->create([
            'remember_token' => 'valid_token',
            'term_aceite' => 1,
        ]);
        $response = $this->postJson('/api/v1/user/reSendEmail', [
            "email" => $user->email,
        ]);

        $response->assertStatus(200);
    }
    public function test_user_send_email_not_found_user()
    {
        $user = User::factory()->create([
            'remember_token' => 'valid_token',
            'term_aceite' => 1,
        ]);
        $response = $this->postJson('/api/v1/user/reSendEmail', [
            "email" => 'clau@example.com',
        ]);

        $response->assertStatus(404);
    }

    public function test_verifies_email_successfully()
    {
        $user = User::factory()->create([
            'remember_token' => 'valid_token',
            'term_aceite' => 1,
        ]);
        $response = $this->getJson("/api/v1/user/verify/{$user->idUser}/{$user->remember_token}");

        $response->assertStatus(200);
    }

    public function test_returns_error_for_invalid_token()
    {
        $user = User::factory()->create([
            'remember_token' => 'valid_token',
            'term_aceite' => 1,
            'email_verified_at' => null,
        ]);
        $response = $this->getJson("/api/v1/user/verify/{$user->idUser}/invalid_token");

        $response->assertStatus(401);
    }

    public function test_returns_error_for_non_existent_user()
    {
        $response = $this->getJson("/api/v1/user/verify/1adew/invalid_token");
        $response->assertStatus(404);
    }

    public function test_user_send_token_recover()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'remember_token' => 'valid_token',
            'term_aceite' => 1,
        ]);
        $response = $this->postJson('/api/v1/user/sendTokenRecover', [
            "email" => $user->email,
        ]);

        $response->assertStatus(200)->assertJsonPath('data.message', 'send e-mail');

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'test@example.com',
        ]);
    }
    public function test_user_send_token_recover_not_found_user()
    {
        $response = $this->postJson('/api/v1/user/sendTokenRecover', [
            "email" => 'john@example.com',
        ]);
        $response->assertStatus(404);
    }

    public function test_reset_password()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('oldpassword'),
            'term_aceite' => 1,
        ]);

        $token = strtoupper(Str::random(60));
        DB::table('password_reset_tokens')->insert([
            'email' => 'test@example.com',
            'token' => $token,
            'created_at' => now(),
        ]);

        $passwordRecover = Hash::make('newpassword');
        $response = $this->putJson('/api/v1/user/resetPassword', [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => $passwordRecover,
            'password_confirmation' => $passwordRecover,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.message', 'success');

        // $this->assertTrue(Hash::check('newpassword', $user->fresh()->password));

        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'test@example.com',
        ]);
    }
}
