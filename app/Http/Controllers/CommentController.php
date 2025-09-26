<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;

class CommentController extends Controller
{
     public function index(Request $request)
    {
        $comment = $request->query('body');
        $search = $request->query('q');

        $cacheKey = 'comments_list_' . md5($comment . '|' . $search);

        $comments = Cache::tags('comments')->remember($cacheKey, 60, function () use ($comment, $search) {
            return Comment::with('user', 'task')
                ->filterByStatus($comment)
                ->searchByTitle($search)
                ->paginate(10);
        });

        return response()->json(['data' => $comments]);
    }

    // Create comment
    public function store(Request $request)
    {
        $data = $request->validate([
            'body' => 'required|string|max:255',
            'task_id' => 'required|exists:tasks,id',
            'user_id' => 'required|exists:users,id',
        ]);
        $comment = Comment::create($data);

        // Clear all cached comment lists
        Cache::tags('comments')->flush();
        return response()->json(['data' => $comment], 201);
    }
    // Update comment
    public function update(Request $request, Comment $comment)
    {
        $data = $request->validate([
            'body' => 'sometimes|string|max:255',
            'task_id' => 'sometimes|exists:tasks,id',
            'user_id' => 'sometimes|exists:users,id',
        ]); 
        $comment->update($data);    

        // Clear all cached comment lists
        Cache::tags('comments')->flush();
        return response()->json(['data' => $comment]);
    }

    // Delete comment
    public function destroy(Comment $comment)
    {
        $comment->delete(); 
        
        // Clear all cached comment lists
        Cache::tags('comments')->flush();
        return response()->json(null, 204);
    }
}
