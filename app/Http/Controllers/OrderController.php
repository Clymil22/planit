<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->organisation_id;
        
        $orders = DB::table('orders')
            ->where('organisation_id', $orgId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('pages.orders.index', compact('orders'));
    }

    public function create()
    {
        $orgId = auth()->user()->organisation_id;
        $clients = DB::table('clients')->where('organisation_id', $orgId)->get();
        $stores = DB::table('stores')->where('organisation_id', $orgId)->get();
        $products = DB::table('products')->where('organisation_id', $orgId)->get();
        $users = DB::table('users')->where('organisation_id', $orgId)->get();
        return view('pages.orders.create', compact('clients', 'stores', 'products', 'users'));
    }

    public function store(Request $request)
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
        
        $subtotal = 0;
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }
        }
        
        $tax = $subtotal * 0.1;
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
        
        return redirect()->route('orders')->with('success', 'Order created successfully.');
    }

    public function show(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $order = DB::table('orders')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$order) {
            abort(404);
        }
        
        $items = DB::table('order_items')->where('order_id', $id)->get();
        
        return view('pages.orders.show', compact('order', 'items'));
    }

    public function edit(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $order = DB::table('orders')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$order) {
            abort(404);
        }
        
        $clients = DB::table('clients')->where('organisation_id', $orgId)->get();
        $stores = DB::table('stores')->where('organisation_id', $orgId)->get();
        $users = DB::table('users')->where('organisation_id', $orgId)->get();
        $items = DB::table('order_items')->where('order_id', $id)->get();
        
        return view('pages.orders.edit', compact('order', 'clients', 'stores', 'users', 'items'));
    }

    public function update(Request $request, $id)
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
            abort(404);
        }

        DB::table('orders')
            ->where('id', $id)
            ->update(array_merge(
                $request->only(['title', 'client_id', 'store_id', 'assigned_to', 'notes', 'status', 'priority', 'due_date']),
                ['updated_at' => now()]
            ));
        
        return redirect()->route('orders')->with('success', 'Order updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $orgId = $request->user()->organisation_id;
        
        $order = DB::table('orders')
            ->where('id', $id)
            ->where('organisation_id', $orgId)
            ->first();
        
        if (!$order) {
            abort(404);
        }

        DB::table('orders')->where('id', $id)->delete();
        DB::table('order_items')->where('order_id', $id)->delete();
        
        return redirect()->route('orders')->with('success', 'Order deleted successfully.');
    }
}
