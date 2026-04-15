<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of projects.
     */
    public function index(Request $request): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $query = DB::table('projects')
            ->where('organisation_id', $orgId)
            ->orderBy('created_at', 'desc');
        
        if ($request->has('store_id')) {
            $query->where('store_id', $request->store_id);
        }
        
        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $projects = $query->get();
        
        return response()->json($projects);
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'nullable|uuid',
            'store_id' => 'nullable|uuid',
            'status' => 'in:todo,in_progress,completed,cancelled',
            'progress' => 'integer|min:0|max:100',
        ]);

        $orgId = $request->user()->organisation_id;
        $userId = $request->user()->id;
        
        $projectId = DB::table('projects')->insertGetId([
            'organisation_id' => $orgId,
            'client_id' => $request->client_id,
            'store_id' => $request->store_id,
            'created_by' => $userId,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 'todo',
            'progress' => $request->progress ?? 0,
            'created_at' => now(),
            'updated_at' => now(),
        ], 'id');

        $project = DB::table('projects')->where('id', $projectId)->first();
        
        return response()->json($project, 201);
    }

    /**
     * Display the specified project.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $project = DB::table('projects')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }
        
        return response()->json($project);
    }

    /**
     * Get project tasks.
     */
    public function tasks(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $project = DB::table('projects')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }
        
        $tasks = DB::table('tasks')
            ->where('project_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($tasks);
    }

    /**
     * Update the specified project.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'nullable|uuid',
            'store_id' => 'nullable|uuid',
            'status' => 'in:todo,in_progress,completed,cancelled',
            'progress' => 'integer|min:0|max:100',
        ]);

        $orgId = $request->user()->organisation_id;
        
        $project = DB::table('projects')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        DB::table('projects')
            ->where('id', $id)
            ->update(array_merge(
                $request->only(['title', 'description', 'client_id', 'store_id', 'status', 'progress']),
                ['updated_at' => now()]
            ));

        $updatedProject = DB::table('projects')->where('id', $id)->first();
        
        return response()->json($updatedProject);
    }

    /**
     * Remove the specified project.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $project = DB::table('projects')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        DB::table('projects')->where('id', $id)->delete();
        DB::table('tasks')->where('project_id', $id)->delete();
        
        return response()->json(['message' => 'Project deleted successfully']);
    }
}
