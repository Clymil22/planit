<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->organisation_id;
        
        $stores = DB::table('stores')
            ->where('organisation_id', $orgId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('pages.stores.index', compact('stores'));
    }

    public function create()
    {
        return view('pages.stores.create');
    }

    public function store(Request $request)
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
        
        DB::table('stores')->insert([
            'organisation_id' => $orgId,
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_active' => $request->is_active ?? true,
            'created_at' => now(),
        ]);
        
        return redirect()->route('stores')->with('success', 'Store created successfully.');
    }

    public function show(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $store = DB::table('stores')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$store) {
            abort(404);
        }
        
        return view('pages.stores.show', compact('store'));
    }

    public function edit(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $store = DB::table('stores')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$store) {
            abort(404);
        }
        
        return view('pages.stores.edit', compact('store'));
    }

    public function update(Request $request, $id)
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
            abort(404);
        }

        DB::table('stores')
            ->where('id', $id)
            ->update(array_merge(
                $request->only(['name', 'address', 'phone', 'email', 'latitude', 'longitude', 'is_active']),
                ['updated_at' => now()]
            ));
        
        return redirect()->route('stores')->with('success', 'Store updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $store = DB::table('stores')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$store) {
            abort(404);
        }

        DB::table('stores')->where('id', $id)->delete();
        
        return redirect()->route('stores')->with('success', 'Store deleted successfully.');
    }
}
