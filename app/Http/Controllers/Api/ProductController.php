<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $query = DB::table('products')
            ->where('organisation_id', $orgId)
            ->orderBy('created_at', 'desc');
        
        if ($request->has('store_id')) {
            $query->where('store_id', $request->store_id);
        }
        
        $products = $query->get();
        
        return response()->json($products);
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request): JsonResponse
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
        
        $productId = DB::table('products')->insertGetId([
            'organisation_id' => $orgId,
            'name' => $request->name,
            'sku' => $request->sku,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'store_id' => $request->store_id,
            'created_at' => now(),
        ], 'id');

        $product = DB::table('products')->where('id', $productId)->first();
        
        return response()->json($product, 201);
    }

    /**
     * Display the specified product.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $product = DB::table('products')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        
        return response()->json($product);
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, string $id): JsonResponse
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
            return response()->json(['message' => 'Product not found'], 404);
        }

        DB::table('products')
            ->where('id', $id)
            ->update(array_merge(
                $request->only(['name', 'sku', 'description', 'price', 'stock', 'store_id']),
                ['updated_at' => now()]
            ));

        $updatedProduct = DB::table('products')->where('id', $id)->first();
        
        return response()->json($updatedProduct);
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $product = DB::table('products')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        DB::table('products')->where('id', $id)->delete();
        
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
