<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrganisationModuleController extends Controller
{
    /**
     * Display organisation modules.
     */
    public function index(Request $request): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $modules = DB::table('organisation_modules')
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$modules) {
            // Create default modules if not exist
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
        
        return response()->json($modules);
    }

    /**
     * Update organisation modules.
     */
    public function update(Request $request): JsonResponse
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
            return response()->json(['message' => 'Organisation modules not found'], 404);
        }

        DB::table('organisation_modules')
            ->where('organisation_id', $orgId)
            ->update($request->only(['finance', 'inventory', 'reports', 'messaging', 'gps', 'pos']));

        $updatedModules = DB::table('organisation_modules')
            ->where('organisation_id', $orgId)
            ->first();
        
        return response()->json($updatedModules);
    }
}
