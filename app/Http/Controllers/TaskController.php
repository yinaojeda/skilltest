<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Services\TaskAssignmentService;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $task = $request->query('title');
        $search = $request->query('q');

        $cacheKey = 'tasks_list_' . md5($task . '|' . $search);

        $tasks = Cache::tags('tasks')->remember($cacheKey, 60, function () use ($task, $search) {
            return Task::with('user', 'project')
                ->filterByStatus($task)
                ->searchByTitle($search)
                ->paginate(10);
        });

        return response()->json(['data' => $tasks]);
    }

    //Create Task
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'nullable|string',
            'status' => 'nullable|string',
            'date' => 'nullable|date',
            'project_id' => 'required|exists:projects,id',
            'assigned_id' => 'required|exists:users,id',
        ]);

        $task = Task::create($data);

        Cache::tags('tasks')->flush();
        return response()->json(['data' => $task], 201);
    }


    public function update(Request $request, Task $task, TaskAssignmentService $service)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'nullable|string',
            'status' => 'nullable|string',
            'date' => 'nullable|date',
            'project_id' => 'required|exists:projects,id',
            'assigned_id' => 'required|exists:users,id',
        ]);

        // Update the task fields
        $task->update($data);
        $service->assign($task, $data['assigned_id']);

        // Clear cache
        Cache::tags('tasks')->flush();

        return response()->json(['data' => $task]);
    }

     // Delete task
    public function destroy(Task $task)
    {
        $task->delete();

        // Clear all cached task lists
        Cache::tags('tasks')->flush();

        return response()->json(['message' => 'Task deleted']);
    }
}
