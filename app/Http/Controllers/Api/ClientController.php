<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function index(Request $request): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $query = DB::table('clients')
            ->where('organisation_id', $orgId)
            ->orderBy('created_at', 'desc');
        
        if ($request->has('store_id')) {
            $query->where('store_id', $request->store_id);
        }
        
        $clients = $query->get();
        
        return response()->json($clients);
    }

    /**
     * Store a newly created client.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'tags' => 'array',
            'notes' => 'nullable|string',
            'store_id' => 'nullable|uuid',
        ]);

        $orgId = $request->user()->organisation_id;
        
        $clientId = DB::table('clients')->insertGetId([
            'organisation_id' => $orgId,
            'full_name' => $request->full_name,
            'company_name' => $request->company_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'tags' => $request->tags ?? [],
            'notes' => $request->notes,
            'store_id' => $request->store_id,
            'created_at' => now(),
        ], 'id');

        $client = DB::table('clients')->where('id', $clientId)->first();
        
        return response()->json($client, 201);
    }

    /**
     * Display the specified client.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $client = DB::table('clients')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }
        
        return response()->json($client);
    }

    /**
     * Update the specified client.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'full_name' => 'sometimes|required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'tags' => 'array',
            'notes' => 'nullable|string',
            'store_id' => 'nullable|uuid',
        ]);

        $orgId = $request->user()->organisation_id;
        
        $client = DB::table('clients')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        DB::table('clients')
            ->where('id', $id)
            ->update(array_merge(
                $request->only(['full_name', 'company_name', 'email', 'phone', 'address', 'tags', 'notes', 'store_id']),
                ['updated_at' => now()]
            ));

        $updatedClient = DB::table('clients')->where('id', $id)->first();
        
        return response()->json($updatedClient);
    }

    /**
     * Remove the specified client.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $client = DB::table('clients')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$client) {
            return response()->json(['message' => 'Client not found'], 404);
        }

        DB::table('clients')->where('id', $id)->delete();
        
        return response()->json(['message' => 'Client deleted successfully']);
    }
}
