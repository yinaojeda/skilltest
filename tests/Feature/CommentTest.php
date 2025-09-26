<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_add_comment_to_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/tasks/{$task->id}/comments", [
                'body' => 'This is a test comment',
                'task_id' => $task->id,
                'user_id' => $user->id
            ])
            ->assertStatus(201)
            ->assertJsonFragment([
                'body' => 'This is a test comment'
            ]);
    }
}
