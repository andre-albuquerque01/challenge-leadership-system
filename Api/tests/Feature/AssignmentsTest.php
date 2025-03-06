<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AssignmentsTest extends TestCase
{
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

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('data.token');

        $response1 = $this->getJson('/api/v1/assignments', [
            'Authorization' => "Bearer $token",
        ]);

        $response1->assertStatus(200);
    }
}
