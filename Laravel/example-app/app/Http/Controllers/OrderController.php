<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::all(), 200);
    }

    public function store(Request $request)
    {
        $order = Order::create([
            'customer_name' => $request->customer_name,
            'status' => $request->status ?? 'pending',
        ]);

        return response()->json($order, 201);
    }

    public function show(Order $order)
    {
        return response()->json($order, 200);
    }

    public function update(Request $request, Order $order)
    {
        $order->update([
            'customer_name' => $request->customer_name ?? $order->customer_name,
            'status' => $request->status ?? $order->status,
        ]);

        return response()->json($order, 200);
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Замовлення видалено'], 200);
    }
}
