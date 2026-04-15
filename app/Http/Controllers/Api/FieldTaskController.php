<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class FieldTaskController extends Controller
{
    /**
     * Display a listing of field tasks.
     */
    public function index(Request $request): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $query = DB::table('field_tasks')
            ->where('organisation_id', $orgId)
            ->orderBy('created_at', 'desc');
        
        if ($request->has('store_id')) {
            $query->where('store_id', $request->store_id);
        }
        
        if ($request->has('assigned_to')) {
            $query->where('assigned_to', $request->assigned_to);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('order_id')) {
            $query->where('order_id', $request->order_id);
        }
        
        $fieldTasks = $query->get();
        
        return response()->json($fieldTasks);
    }

    /**
     * Store a newly created field task.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'store_id' => 'nullable|uuid',
            'order_id' => 'nullable|uuid',
            'assigned_to' => 'nullable|integer',
            'type' => 'in:delivery,pickup,service',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,in_progress,completed,cancelled',
            'destination_address' => 'nullable|string',
            'destination_lat' => 'nullable|numeric',
            'destination_lng' => 'nullable|numeric',
            'scheduled_at' => 'nullable|date',
        ]);

        $orgId = $request->user()->organisation_id;
        
        $fieldTaskId = DB::table('field_tasks')->insertGetId([
            'organisation_id' => $orgId,
            'store_id' => $request->store_id,
            'order_id' => $request->order_id,
            'assigned_to' => $request->assigned_to,
            'type' => $request->type ?? 'delivery',
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 'pending',
            'destination_address' => $request->destination_address,
            'destination_lat' => $request->destination_lat,
            'destination_lng' => $request->destination_lng,
            'scheduled_at' => $request->scheduled_at,
            'created_at' => now(),
        ], 'id');

        $fieldTask = DB::table('field_tasks')->where('id', $fieldTaskId)->first();
        
        return response()->json($fieldTask, 201);
    }

    /**
     * Display the specified field task.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $fieldTask = DB::table('field_tasks')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$fieldTask) {
            return response()->json(['message' => 'Field task not found'], 404);
        }
        
        return response()->json($fieldTask);
    }

    /**
     * Update the specified field task.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'store_id' => 'nullable|uuid',
            'order_id' => 'nullable|uuid',
            'assigned_to' => 'nullable|integer',
            'type' => 'in:delivery,pickup,service',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'in:pending,in_progress,completed,cancelled',
            'destination_address' => 'nullable|string',
            'destination_lat' => 'nullable|numeric',
            'destination_lng' => 'nullable|numeric',
            'scheduled_at' => 'nullable|date',
        ]);

        $orgId = $request->user()->organisation_id;
        
        $fieldTask = DB::table('field_tasks')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$fieldTask) {
            return response()->json(['message' => 'Field task not found'], 404);
        }

        $updateData = $request->only(['store_id', 'order_id', 'assigned_to', 'type', 'title', 'description', 'status', 'destination_address', 'destination_lat', 'destination_lng', 'scheduled_at']);
        
        // Set started_at when status is in_progress
        if (isset($updateData['status']) && $updateData['status'] === 'in_progress' && $fieldTask->status !== 'in_progress') {
            $updateData['started_at'] = now();
        }
        
        // Set completed_at when status is completed
        if (isset($updateData['status']) && $updateData['status'] === 'completed' && $fieldTask->status !== 'completed') {
            $updateData['completed_at'] = now();
        }

        DB::table('field_tasks')
            ->where('id', $id)
            ->update($updateData);

        $updatedFieldTask = DB::table('field_tasks')->where('id', $id)->first();
        
        return response()->json($updatedFieldTask);
    }

    /**
     * Remove the specified field task.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $fieldTask = DB::table('field_tasks')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$fieldTask) {
            return response()->json(['message' => 'Field task not found'], 404);
        }

        DB::table('field_tasks')->where('id', $id)->delete();
        
        return response()->json(['message' => 'Field task deleted successfully']);
    }
}
