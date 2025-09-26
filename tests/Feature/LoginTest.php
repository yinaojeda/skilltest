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
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('secret123')
        ]);

        $this->postJson('/sanctum/token', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'device_name' => 'testing'
        ])->assertStatus(200);
    }
}
