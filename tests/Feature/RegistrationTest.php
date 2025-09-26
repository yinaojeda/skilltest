<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration()
    {
        $payload = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'secret123'
        ];

        $this->postJson('/api/register', $payload)
             ->assertStatus(201)
             ->assertJsonStructure([
                 'data' => ['id', 'email'],
                 'token'
             ]);
    }
}
