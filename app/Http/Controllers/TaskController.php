<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Services\TaskAssignmentService;

class TaskController extends Controller
{
    public function update(Request $req, Task $task, TaskAssignmentService $service)
    {
        $user = $req->user();
        if (!$user->isManager() && $user->id !== $task->assigned_to) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $task = $service->assign($task, $req->only(['title', 'description', 'status', 'due_date', 'assigned_to']));
        return response()->json(['data' => $task]);
    }
}
