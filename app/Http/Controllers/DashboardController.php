<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->organisation_id;
        
        // Get stats for the dashboard
        $stats = [
            'total_orders' => DB::table('orders')
                ->where('organisation_id', $orgId)
                ->count(),
            'total_clients' => DB::table('clients')
                ->where('organisation_id', $orgId)
                ->count(),
            'active_projects' => DB::table('projects')
                ->where('organisation_id', $orgId)
                ->where('status', 'in_progress')
                ->count(),
            'pending_tasks' => DB::table('tasks')
                ->join('projects', 'tasks.project_id', '=', 'projects.id')
                ->where('projects.organisation_id', $orgId)
                ->where('tasks.status', 'todo')
                ->count(),
        ];

        // Get recent orders
        $recentOrders = DB::table('orders')
            ->where('organisation_id', $orgId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get recent tasks
        $recentTasks = DB::table('tasks')
            ->join('projects', 'tasks.project_id', '=', 'projects.id')
            ->where('projects.organisation_id', $orgId)
            ->select('tasks.*', 'projects.title as project_title')
            ->orderBy('tasks.created_at', 'desc')
            ->limit(5)
            ->get();

        return view('pages/dashboard/dashboard', compact('stats', 'recentOrders', 'recentTasks'));
    }
}
