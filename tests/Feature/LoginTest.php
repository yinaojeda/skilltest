<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);


        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123', // Send the plain password
            'device_name' => 'testing'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'token',
            ]);
    }
}
