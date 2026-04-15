<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->organisation_id;
        
        $clients = DB::table('clients')
            ->where('organisation_id', $orgId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('pages.clients.index', compact('clients'));
    }

    public function create()
    {
        $orgId = auth()->user()->organisation_id;
        $stores = DB::table('stores')->where('organisation_id', $orgId)->get();
        return view('pages.clients.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'store_id' => 'nullable|uuid',
        ]);

        $orgId = $request->user()->organisation_id;
        
        DB::table('clients')->insert([
            'organisation_id' => $orgId,
            'full_name' => $request->full_name,
            'company_name' => $request->company_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'notes' => $request->notes,
            'store_id' => $request->store_id,
            'created_at' => now(),
        ]);
        
        return redirect()->route('clients')->with('success', 'Client created successfully.');
    }

    public function show(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $client = DB::table('clients')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$client) {
            abort(404);
        }
        
        return view('pages.clients.show', compact('client'));
    }

    public function edit(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $client = DB::table('clients')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$client) {
            abort(404);
        }
        
        $stores = DB::table('stores')->where('organisation_id', $orgId)->get();
        
        return view('pages.clients.edit', compact('client', 'stores'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name' => 'sometimes|required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'store_id' => 'nullable|uuid',
        ]);

        $orgId = $request->user()->organisation_id;
        
        $client = DB::table('clients')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$client) {
            abort(404);
        }

        DB::table('clients')
            ->where('id', $id)
            ->update(array_merge(
                $request->only(['full_name', 'company_name', 'email', 'phone', 'address', 'notes', 'store_id']),
                ['updated_at' => now()]
            ));
        
        return redirect()->route('clients')->with('success', 'Client updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $client = DB::table('clients')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$client) {
            abort(404);
        }

        DB::table('clients')->where('id', $id)->delete();
        
        return redirect()->route('clients')->with('success', 'Client deleted successfully.');
    }
}
