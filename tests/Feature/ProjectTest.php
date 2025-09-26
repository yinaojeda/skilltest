<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the environment before each test.
     *
     * We create a regular user and an admin user for testing purposes.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create a user factory if you haven't already, ensuring it can create 'admin' roles.
        // Assuming your User factory allows setting a 'role'
        User::factory()->create(['email' => 'user@example.com']);
        User::factory()->create(['email' => 'admin@example.com', 'role' => 'admin']);
    }

    /**
     * Test an admin can successfully create a project.
     *
     * @return void
     */
    public function test_admin_can_create_project()
    {
        // 1. Setup: Get the Admin user
        $admin = User::where('role', 'admin')->first();

        // 2. Execution: Send the request while acting as the authenticated admin user
        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/projects', [
            'title' => 'Project 1',
            'description' => 'Description here'
        ]);

        // 3. Assertions
        $response->assertStatus(201)
            ->assertJsonFragment([
                'title' => 'Project 1',
                'created_by' => $admin->id, // Verify the ID was saved
            ]);

        // Also assert the project exists in the database
        $this->assertDatabaseHas('projects', [
            'title' => 'Project 1',
            'description' => 'Description here',
            'created_by' => $admin->id, // Crucial check for the created_by field
        ]);
    }
}
