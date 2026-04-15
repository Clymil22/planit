<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->organisation_id;
        
        $projects = DB::table('projects')
            ->where('organisation_id', $orgId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('pages.projects.index', compact('projects'));
    }

    public function create()
    {
        $orgId = auth()->user()->organisation_id;
        $clients = DB::table('clients')->where('organisation_id', $orgId)->get();
        $stores = DB::table('stores')->where('organisation_id', $orgId)->get();
        $users = DB::table('users')->where('organisation_id', $orgId)->get();
        return view('pages.projects.create', compact('clients', 'stores', 'users'));
    }

    public function store(Request $request)
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
        
        DB::table('projects')->insert([
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
        ]);
        
        return redirect()->route('projects')->with('success', 'Project created successfully.');
    }

    public function show(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $project = DB::table('projects')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$project) {
            abort(404);
        }
        
        $tasks = DB::table('tasks')
            ->where('project_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('pages.projects.show', compact('project', 'tasks'));
    }

    public function edit(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $project = DB::table('projects')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$project) {
            abort(404);
        }
        
        $clients = DB::table('clients')->where('organisation_id', $orgId)->get();
        $stores = DB::table('stores')->where('organisation_id', $orgId)->get();
        
        return view('pages.projects.edit', compact('project', 'clients', 'stores'));
    }

    public function update(Request $request, $id)
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
            abort(404);
        }

        DB::table('projects')
            ->where('id', $id)
            ->update(array_merge(
                $request->only(['title', 'description', 'client_id', 'store_id', 'status', 'progress']),
                ['updated_at' => now()]
            ));
        
        return redirect()->route('projects')->with('success', 'Project updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $project = DB::table('projects')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$project) {
            abort(404);
        }

        DB::table('projects')->where('id', $id)->delete();
        DB::table('tasks')->where('project_id', $id)->delete();
        
        return redirect()->route('projects')->with('success', 'Project deleted successfully.');
    }
}
