<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    /**
     * Display a listing of stores.
     */
    public function index(Request $request): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $stores = DB::table('stores')
            ->where('organisation_id', $orgId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($stores);
    }

    /**
     * Store a newly created store.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);

        $orgId = $request->user()->organisation_id;
        
        $storeId = DB::table('stores')->insertGetId([
            'organisation_id' => $orgId,
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_active' => $request->is_active ?? true,
            'created_at' => now(),
        ], 'id');

        $store = DB::table('stores')->where('id', $storeId)->first();
        
        return response()->json($store, 201);
    }

    /**
     * Display the specified store.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $store = DB::table('stores')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$store) {
            return response()->json(['message' => 'Store not found'], 404);
        }
        
        return response()->json($store);
    }

    /**
     * Update the specified store.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_active' => 'boolean',
        ]);

        $orgId = $request->user()->organisation_id;
        
        $store = DB::table('stores')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$store) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        DB::table('stores')
            ->where('id', $id)
            ->update(array_merge(
                $request->only(['name', 'address', 'phone', 'email', 'latitude', 'longitude', 'is_active']),
                ['updated_at' => now()]
            ));

        $updatedStore = DB::table('stores')->where('id', $id)->first();
        
        return response()->json($updatedStore);
    }

    /**
     * Remove the specified store.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $store = DB::table('stores')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$store) {
            return response()->json(['message' => 'Store not found'], 404);
        }

        DB::table('stores')->where('id', $id)->delete();
        
        return response()->json(['message' => 'Store deleted successfully']);
    }
}
