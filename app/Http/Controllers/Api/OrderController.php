<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $query = DB::table('orders')
            ->where('organisation_id', $orgId)
            ->orderBy('created_at', 'desc');
        
        if ($request->has('store_id')) {
            $query->where('store_id', $request->store_id);
        }
        
        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $orders = $query->get();
        
        return response()->json($orders);
    }

    /**
     * Store a newly created order.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'client_id' => 'nullable|uuid',
            'store_id' => 'nullable|uuid',
            'assigned_to' => 'nullable|integer',
            'notes' => 'nullable|string',
            'status' => 'in:new,processing,completed,cancelled',
            'priority' => 'in:low,normal,high,urgent',
            'due_date' => 'nullable|date',
            'items' => 'array',
            'items.*.name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $orgId = $request->user()->organisation_id;
        $userId = $request->user()->id;
        
        // Calculate totals
        $subtotal = 0;
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }
        }
        
        $tax = $subtotal * 0.1; // 10% tax
        $total = $subtotal + $tax;
        
        $orderId = DB::table('orders')->insertGetId([
            'organisation_id' => $orgId,
            'client_id' => $request->client_id,
            'store_id' => $request->store_id,
            'assigned_to' => $request->assigned_to,
            'created_by' => $userId,
            'title' => $request->title,
            'notes' => $request->notes,
            'status' => $request->status ?? 'new',
            'priority' => $request->priority ?? 'normal',
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'due_date' => $request->due_date,
            'created_at' => now(),
            'updated_at' => now(),
        ], 'id');

        // Insert order items
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                ]);
            }
        }

        $order = DB::table('orders')->where('id', $orderId)->first();
        
        return response()->json($order, 201);
    }

    /**
     * Display the specified order.
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $order = DB::table('orders')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        
        return response()->json($order);
    }

    /**
     * Get order items.
     */
    public function items(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $order = DB::table('orders')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        
        $items = DB::table('order_items')
            ->where('order_id', $id)
            ->get();
        
        return response()->json($items);
    }

    /**
     * Update the specified order.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'client_id' => 'nullable|uuid',
            'store_id' => 'nullable|uuid',
            'assigned_to' => 'nullable|integer',
            'notes' => 'nullable|string',
            'status' => 'in:new,processing,completed,cancelled',
            'priority' => 'in:low,normal,high,urgent',
            'due_date' => 'nullable|date',
        ]);

        $orgId = $request->user()->organisation_id;
        
        $order = DB::table('orders')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        DB::table('orders')
            ->where('id', $id)
            ->update(array_merge(
                $request->only(['title', 'client_id', 'store_id', 'assigned_to', 'notes', 'status', 'priority', 'due_date']),
                ['updated_at' => now()]
            ));

        $updatedOrder = DB::table('orders')->where('id', $id)->first();
        
        return response()->json($updatedOrder);
    }

    /**
     * Remove the specified order.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $orgId = $request->user()->organisation_id;
        
        $order = DB::table('orders')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        DB::table('orders')->where('id', $id)->delete();
        DB::table('order_items')->where('order_id', $id)->delete();
        
        return response()->json(['message' => 'Order deleted successfully']);
    }
}
