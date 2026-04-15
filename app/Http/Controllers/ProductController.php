<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->organisation_id;
        
        $products = DB::table('products')
            ->where('organisation_id', $orgId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('pages.products.index', compact('products'));
    }

    public function create()
    {
        $orgId = auth()->user()->organisation_id;
        $stores = DB::table('stores')->where('organisation_id', $orgId)->get();
        return view('pages.products.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'store_id' => 'nullable|uuid',
        ]);

        $orgId = $request->user()->organisation_id;
        
        DB::table('products')->insert([
            'organisation_id' => $orgId,
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'store_id' => $request->store_id,
            'created_at' => now(),
        ]);
        
        return redirect()->route('products')->with('success', 'Product created successfully.');
    }

    public function show(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $product = DB::table('products')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$product) {
            abort(404);
        }
        
        return view('pages.products.show', compact('product'));
    }

    public function edit(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $product = DB::table('products')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$product) {
            abort(404);
        }
        
        $stores = DB::table('stores')->where('organisation_id', $orgId)->get();
        
        return view('pages.products.edit', compact('product', 'stores'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'stock' => 'sometimes|required|integer|min:0',
            'store_id' => 'nullable|uuid',
        ]);

        $orgId = $request->user()->organisation_id;
        
        $product = DB::table('products')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$product) {
            abort(404);
        }

        DB::table('products')
            ->where('id', $id)
            ->update(array_merge(
                $request->only(['name', 'sku', 'description', 'price', 'stock', 'store_id']),
                ['updated_at' => now()]
            ));
        
        return redirect()->route('products')->with('success', 'Product updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $product = DB::table('products')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$product) {
            abort(404);
        }

        DB::table('products')->where('id', $id)->delete();
        
        return redirect()->route('products')->with('success', 'Product deleted successfully.');
    }
}
