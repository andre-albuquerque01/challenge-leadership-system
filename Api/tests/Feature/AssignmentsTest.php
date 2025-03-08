<?php

namespace Tests\Feature;

use App\Models\Assignments;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AssignmentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_assignments()
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

        $response1 = $this->getJson('/api/v1/assignments', [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(200);
    }
    public function test_show_assignments()
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
            'term_aceite' => 1,
        ]);

        $assigments = Assignments::factory()->create([
            'idMember' => $user1->idUser,
            'idLeader' => $user2->idUser
        ]);

        $loginResponse = $this->postJson('/api/v1/user/sessions', [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ]);
        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('data.token');

        $response1 = $this->getJson("/api/v1/assignments/{$assigments->idAssignment}", [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(200);
    }
    public function test_show_error_not_found_assignments()
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
            'term_aceite' => 1,
        ]);

        Assignments::factory()->create([
            'idMember' => $user1->idUser,
            'idLeader' => $user2->idUser
        ]);

        $loginResponse = $this->postJson('/api/v1/user/sessions', [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ]);
        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('data.token');

        $response1 = $this->getJson("/api/v1/assignments/123123", [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(404)
            ->assertJsonPath("message", "Assignment not found");
    }
    public function test_show_user_assignments()
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
            'term_aceite' => 1,
        ]);

        Assignments::factory()->create([
            'idMember' => $user1->idUser,
            'idLeader' => $user2->idUser
        ]);

        $loginResponse = $this->postJson('/api/v1/user/sessions', [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ]);
        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('data.token');

        $response1 = $this->getJson("/api/v1/showUser", [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(200);
    }
    public function test_create_assignments()
    {
        $user1 = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
            'email_verified_at' => now(),
            'role' => 'leader'
        ]);

        $user2 = User::factory()->create([
            'email' => 'john.doe2@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
        ]);


        $loginResponse = $this->postJson('/api/v1/user/sessions', [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ]);
        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('data.token');

        $response1 = $this->postJson("/api/v1/assignments", [
            'idMember' => $user2->idUser,
            'idLeader' => $user1->idUser
        ], [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(204);
    }
    public function test_create_error_not_leader_assignments()
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
            'term_aceite' => 1,
        ]);


        $loginResponse = $this->postJson('/api/v1/user/sessions', [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ]);
        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('data.token');

        $response1 = $this->postJson("/api/v1/assignments", [
            'idMember' => $user1->idUser,
            'idLeader' => $user2->idUser
        ], [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(401)
            ->assertJsonPath("message", "user not leader");
    }
    public function test_update_assignments()
    {
        $user1 = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
            'email_verified_at' => now(),
            'role' => 'leader'
        ]);

        $user2 = User::factory()->create([
            'email' => 'john.doe2@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
        ]);

        $assigments = Assignments::factory()->create([
            'idMember' => $user1->idUser,
            'idLeader' => $user2->idUser
        ]);

        $loginResponse = $this->postJson('/api/v1/user/sessions', [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ]);
        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('data.token');

        $response1 = $this->putJson("/api/v1/assignments/{$assigments->idAssignment}", [
            'idMember' => $user1->idUser,
            'idLeader' => $user2->idUser
        ], [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(204);
    }
    public function test_update_error_not_found_assignments()
    {
        $user1 = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
            'email_verified_at' => now(),
            'role' => 'leader'
        ]);

        $user2 = User::factory()->create([
            'email' => 'john.doe2@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
        ]);

        $loginResponse = $this->postJson('/api/v1/user/sessions', [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ]);
        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('data.token');

        $response1 = $this->putJson("/api/v1/assignments/14525", [
            'idMember' => $user1->idUser,
            'idLeader' => $user2->idUser
        ], [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(404)
            ->assertJsonPath("message", "Assignment not found");
    }
    public function test_delete_assignments()
    {
        $user1 = User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
            'email_verified_at' => now(),
            'role' => 'leader'
        ]);

        $user2 = User::factory()->create([
            'email' => 'john.doe2@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
        ]);

        $assigments = Assignments::factory()->create([
            'idMember' => $user1->idUser,
            'idLeader' => $user2->idUser
        ]);

        $loginResponse = $this->postJson('/api/v1/user/sessions', [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ]);
        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('data.token');

        $response1 = $this->deleteJson("/api/v1/assignments/{$assigments->idAssignment}", [], [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(204);
    }
    public function test_delete_error_not_found_assignments()
    {
        User::factory()->create([
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
            'term_aceite' => 1,
            'email_verified_at' => now(),
        ]);

        $loginResponse = $this->postJson('/api/v1/user/sessions', [
            'email' => 'john.doe@example.com',
            'password' => 'strongPassword@1231254124',
        ]);
        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('data.token');

        $response1 = $this->deleteJson("/api/v1/assignments/14525", [], [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(404)
            ->assertJsonPath("message", "Assignment not found");
    }
}
