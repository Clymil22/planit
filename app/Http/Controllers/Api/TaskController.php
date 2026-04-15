<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of tasks.
     */
    public function index(Request $request): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $query = DB::table('tasks')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('projects.organisation_id', $orgId)
            ->select('tasks.*')
            ->orderBy('tasks.created_at', 'desc');
        
        if ($request->has('project_id')) {
            $query->where('tasks.project_id', $request->project_id);
        }
        
        if ($request->has('assigned_to')) {
            $query->where('tasks.assigned_to', $request->assigned_to);
        }
        
        if ($request->has('status')) {
            $query->where('tasks.status', $request->status);
        }
        
        $tasks = $query->get();
        
        return response()->json($tasks);
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'project_id' => 'nullable|uuid',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|integer',
            'status' => 'in:todo,in_progress,review,done,cancelled',
            'priority' => 'in:low,normal,high,urgent',
            'due_date' => 'nullable|date',
        ]);

        $userId = $request->user()->id;
        
        // Verify project belongs to user's organisation
        if ($request->has('project_id')) {
            $orgId = $request->user()->organisation_id;
            $project = DB::table('projects')
                ->where('id', $request->project_id)
                ->where('organisation_id', $orgId)
                ->first();
            
            if (!$project) {
                return response()->json(['message' => 'Project not found'], 404);
            }
        }
        
        $taskId = DB::table('tasks')->insertGetId([
            'project_id' => $request->project_id,
            'assigned_to' => $request->assigned_to,
            'created_by' => $userId,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 'todo',
            'priority' => $request->priority ?? 'normal',
            'due_date' => $request->due_date,
            'created_at' => now(),
        ], 'id');

        $task = DB::table('tasks')->where('id', $taskId)->first();
        
        return response()->json($task, 201);
    }

    /**
     * Display the specified task.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $task = DB::table('tasks')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('tasks.id', $id)
            ->where('projects.organisation_id', $orgId)
            ->select('tasks.*')
            ->first();
        
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }
        
        return response()->json($task);
    }

    /**
     * Update the specified task.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'project_id' => 'nullable|uuid',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|integer',
            'status' => 'in:todo,in_progress,review,done,cancelled',
            'priority' => 'in:low,normal,high,urgent',
            'due_date' => 'nullable|date',
        ]);

        $orgId = $request->user()->organisation_id;
        
        $task = DB::table('tasks')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('tasks.id', $id)
            ->where('projects.organisation_id', $orgId)
            ->select('tasks.*')
            ->first();
        
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $updateData = $request->only(['project_id', 'title', 'description', 'assigned_to', 'status', 'priority', 'due_date']);
        
        // Set completed_at when status is done
        if (isset($updateData['status']) && $updateData['status'] === 'done' && $task->status !== 'done') {
            $updateData['completed_at'] = now();
        }

        DB::table('tasks')
            ->where('id', $id)
            ->update($updateData);

        $updatedTask = DB::table('tasks')->where('id', $id)->first();
        
        return response()->json($updatedTask);
    }

    /**
     * Remove the specified task.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $task = DB::table('tasks')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('tasks.id', $id)
            ->where('projects.organisation_id', $orgId)
            ->select('tasks.*')
            ->first();
        
        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        DB::table('tasks')->where('id', $id)->delete();
        
        return response()->json(['message' => 'Task deleted successfully']);
    }
}
