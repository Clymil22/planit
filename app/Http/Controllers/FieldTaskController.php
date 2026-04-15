<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FieldTaskController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->organisation_id;
        
        $fieldTasks = DB::table('field_tasks')
            ->where('organisation_id', $orgId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('pages.field-tasks.index', compact('fieldTasks'));
    }

    public function create()
    {
        $orgId = auth()->user()->organisation_id;
        $stores = DB::table('stores')->where('organisation_id', $orgId)->get();
        $orders = DB::table('orders')->where('organisation_id', $orgId)->get();
        $users = DB::table('users')->where('organisation_id', $orgId)->get();
        return view('pages.field-tasks.create', compact('stores', 'orders', 'users'));
    }

    public function store(Request $request)
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
        
        DB::table('field_tasks')->insert([
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
        ]);
        
        return redirect()->route('field-tasks')->with('success', 'Field task created successfully.');
    }

    public function show(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $fieldTask = DB::table('field_tasks')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$fieldTask) {
            abort(404);
        }
        
        return view('pages.field-tasks.show', compact('fieldTask'));
    }

    public function edit(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $fieldTask = DB::table('field_tasks')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$fieldTask) {
            abort(404);
        }
        
        $stores = DB::table('stores')->where('organisation_id', $orgId)->get();
        $orders = DB::table('orders')->where('organisation_id', $orgId)->get();
        $users = DB::table('users')->where('organisation_id', $orgId)->get();
        
        return view('pages.field-tasks.edit', compact('fieldTask', 'stores', 'orders', 'users'));
    }

    public function update(Request $request, $id)
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
            abort(404);
        }

        $updateData = $request->only(['store_id', 'order_id', 'assigned_to', 'type', 'title', 'description', 'status', 'destination_address', 'destination_lat', 'destination_lng', 'scheduled_at']);
        
        if (isset($updateData['status']) && $updateData['status'] === 'in_progress' && $fieldTask->status !== 'in_progress') {
            $updateData['started_at'] = now();
        }
        
        if (isset($updateData['status']) && $updateData['status'] === 'completed' && $fieldTask->status !== 'completed') {
            $updateData['completed_at'] = now();
        }

        DB::table('field_tasks')
            ->where('id', $id)
            ->update($updateData);
        
        return redirect()->route('field-tasks')->with('success', 'Field task updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $fieldTask = DB::table('field_tasks')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$fieldTask) {
            abort(404);
        }

        DB::table('field_tasks')->where('id', $id)->delete();
        
        return redirect()->route('field-tasks')->with('success', 'Field task deleted successfully.');
    }
}
