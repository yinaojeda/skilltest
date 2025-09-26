<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_project()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin, 'sanctum')
            ->postJson('/api/projects', [
                'title' => 'Project 1',
                'description' => 'Description here',
                'start_date' => now(),
                'end_date' => now()->addDays(5),
                'created_by' => $admin->id
            ])
            ->assertStatus(201);
    }
}
