<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $search = $request->query('q');

        $cacheKey = 'projects_list_' . md5($status . '|' . $search);

        $projects = Cache::tags('projects')->remember($cacheKey, 60, function () use ($status, $search) {
            return Project::with('creator', 'tasks')
                ->filterByStatus($status)
                ->searchByTitle($search)
                ->paginate(10);
        });

        return response()->json(['data' => $projects]);
    }

    // Create project
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $project = Project::create($data);

        // Clear all cached project lists
        Cache::tags('projects')->flush();

        return response()->json(['data' => $project], 201);
    }

    // Update project
    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|string',
        ]);

        $project->update($data);

        // Clear all cached project lists
        Cache::tags('projects')->flush();

        return response()->json(['data' => $project]);
    }

    // Delete project
    public function destroy(Project $project)
    {
        $project->delete();

        // Clear all cached project lists
        Cache::tags('projects')->flush();

        return response()->json(['message' => 'Project deleted']);
    }
}
