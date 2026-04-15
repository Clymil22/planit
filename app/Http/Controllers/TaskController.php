<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->organisation_id;
        
        $tasks = DB::table('tasks')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('projects.organisation_id', $orgId)
            ->select('tasks.*', 'projects.title as project_title')
            ->orderBy('tasks.created_at', 'desc')
            ->get();
        
        $projects = DB::table('projects')
            ->where('organisation_id', $orgId)
            ->get();
        
        return view('pages.tasks.index', compact('tasks', 'projects'));
    }

    public function create()
    {
        $orgId = auth()->user()->organisation_id;
        $projects = DB::table('projects')->where('organisation_id', $orgId)->get();
        $users = DB::table('users')->where('organisation_id', $orgId)->get();
        return view('pages.tasks.create', compact('projects', 'users'));
    }

    public function store(Request $request)
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
        
        if ($request->has('project_id')) {
            $orgId = $request->user()->organisation_id;
            $project = DB::table('projects')
                ->where('id', $request->project_id)
                ->where('organisation_id', $orgId)
                ->first();
            
            if (!$project) {
                return back()->with('error', 'Project not found.');
            }
        }
        
        DB::table('tasks')->insert([
            'project_id' => $request->project_id,
            'assigned_to' => $request->assigned_to,
            'created_by' => $userId,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 'todo',
            'priority' => $request->priority ?? 'normal',
            'due_date' => $request->due_date,
            'created_at' => now(),
        ]);
        
        return redirect()->route('tasks')->with('success', 'Task created successfully.');
    }

    public function show(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $task = DB::table('tasks')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('tasks.id', $id)
            ->where('projects.organisation_id', $orgId)
            ->select('tasks.*', 'projects.title as project_title')
            ->first();
        
        if (!$task) {
            abort(404);
        }
        
        return view('pages.tasks.show', compact('task'));
    }

    public function edit(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $task = DB::table('tasks')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('tasks.id', $id)
            ->where('projects.organisation_id', $orgId)
            ->select('tasks.*')
            ->first();
        
        if (!$task) {
            abort(404);
        }
        
        $projects = DB::table('projects')->where('organisation_id', $orgId)->get();
        $users = DB::table('users')->where('organisation_id', $orgId)->get();
        
        return view('pages.tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(Request $request, $id)
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
            abort(404);
        }

        $updateData = $request->only(['project_id', 'title', 'description', 'assigned_to', 'status', 'priority', 'due_date']);
        
        if (isset($updateData['status']) && $updateData['status'] === 'done' && $task->status !== 'done') {
            $updateData['completed_at'] = now();
        }

        DB::table('tasks')
            ->where('id', $id)
            ->update($updateData);
        
        return redirect()->route('tasks')->with('success', 'Task updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $task = DB::table('tasks')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('tasks.id', $id)
            ->where('projects.organisation_id', $orgId)
            ->select('tasks.*')
            ->first();
        
        if (!$task) {
            abort(404);
        }

        DB::table('tasks')->where('id', $id)->delete();
        
        return redirect()->route('tasks')->with('success', 'Task deleted successfully.');
    }
}
