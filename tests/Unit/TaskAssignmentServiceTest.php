<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Services\TaskAssignmentService;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TaskAssignmentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_assignment_sends_notification()
    {
        Notification::fake();

        // Create related records first
        $project = Project::factory()->create();
        $user = User::factory()->create();

        // Then create task using those
        $task = Task::factory()->create([
            'project_id' => $project->id,
            'assigned_to' => $user->id
        ]);

        $service = new TaskAssignmentService();
        $service->assign($task, ['assigned_to' => $user->id]);

        Notification::assertSentTo($user, TaskAssignedNotification::class);
    }
}
