<?php

namespace Tests\Feature;

use App\Models\Messages;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_user_message(): void
    {
        $user1 = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
            'email_verified_at' => now(),
        ]);
        $user2 = User::factory()->create([
            'email' => 'john.doe2@example.com',
            'password' => 'strongPassword@1231254124',
        ]);

         Messages::factory()->create([
            'sender_id' => $user1->idUser,
            'receiver_id' => $user2->idUser,
        ]);

        $credentials = [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ];

        $login = $this->post('/api/v1/user/sessions', $credentials);
        $login->assertStatus(200);
        $token = $login->json('data.token');

        $response = $this->getJson("/api/v1/message/user/show", [
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(200);
    }
    public function test_show_message(): void
    {
        $user1 = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ]);
        $user2 = User::factory()->create([
            'email' => 'john.doe2@example.com',
            'password' => 'strongPassword@1231254124',
        ]);

        $message = Messages::factory()->create([
            'sender_id' => $user1->idUser,
            'receiver_id' => $user2->idUser,
        ]);

        $credentials = [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ];

        $login = $this->post('/api/v1/user/sessions', $credentials);
        $login->assertStatus(200);
        $token = $login->json('data.token');

        $response = $this->getJson("/api/v1/message/show/" . $message->idMessage, [
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(200);
    }
    public function test_show_not_found_message(): void
    {
        $user1 = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ]);
        $user2 = User::factory()->create([
            'email' => 'john.doe2@example.com',
            'password' => 'strongPassword@1231254124',
        ]);

        Messages::factory()->create([
            'sender_id' => $user1->idUser,
            'receiver_id' => $user2->idUser,
        ]);

        $credentials = [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ];

        $login = $this->post('/api/v1/user/sessions', $credentials);
        $login->assertStatus(200);
        $token = $login->json('data.token');

        $response = $this->getJson("/api/v1/message/show/123123", [
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(404);
    }
    public function test_store_message(): void
    {
        $user1 = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ]);
        $user2 = User::factory()->create([
            'email' => 'john.doe2@example.com',
            'password' => 'strongPassword@1231254124',
        ]);

        $credentials = [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ];

        $login = $this->post('/api/v1/user/sessions', $credentials);
        $login->assertStatus(200);
        $token = $login->json('data.token');

        $response = $this->postJson('/api/v1/message/send', [
            'sender_id' => $user1->idUser,
            'receiver_id' => $user2->idUser,
            'content' => 'Teste de envio de mensagem',
        ], [
            'Authorization' => 'Bearer ' . $token,
        ]);
        $response->assertStatus(204);
    }
}
