<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganisationModuleController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->organisation_id;
        
        $modules = DB::table('organisation_modules')
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$modules) {
            DB::table('organisation_modules')->insert([
                'organisation_id' => $orgId,
                'finance' => true,
                'inventory' => false,
                'reports' => true,
                'messaging' => true,
                'gps' => false,
                'pos' => false,
            ]);
            
            $modules = DB::table('organisation_modules')
                ->where('organisation_id', $orgId)
                ->first();
        }
        
        return view('pages.modules.index', compact('modules'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'finance' => 'boolean',
            'inventory' => 'boolean',
            'reports' => 'boolean',
            'messaging' => 'boolean',
            'gps' => 'boolean',
            'pos' => 'boolean',
        ]);

        $orgId = $request->user()->organisation_id;
        
        $modules = DB::table('organisation_modules')
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$modules) {
            return back()->with('error', 'Organisation modules not found.');
        }

        DB::table('organisation_modules')
            ->where('organisation_id', $orgId)
            ->update($request->only(['finance', 'inventory', 'reports', 'messaging', 'gps', 'pos']));
        
        return back()->with('success', 'Modules updated successfully.');
    }
}
