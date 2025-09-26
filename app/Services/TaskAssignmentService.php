<?php
namespace App\Services;

use Illuminate\Support\Facades\Validator;
use App\Models\Task;
use App\Models\User;
class TaskAssignmentService
{
    public function assign(Task $task, array $data): Task
    {
        $validator = Validator::make($data, [
            'title' => 'sometimes|string',
            'description' => 'sometimes|string|nullable',
            'status' => 'sometimes|in:pending,in-progress,done, completed',
            'due_date' => 'sometimes|date|nullable',
            'assigned_to' => 'nullable|exists:users,id',
        ]);
        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        $task->fill($validator->validated());
        $task->save();

        // If assigned, notify user via queued notification
        if ($task->assigned_to) {
            $assignee = User::find($task->assigned_to);
            $assignee->notify(new \App\Notifications\TaskAssignedNotification($task));
        }

        return $task;
    }
}
