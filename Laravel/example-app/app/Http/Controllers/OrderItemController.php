<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        return response()->json(OrderItem::all(), 200);
    }

    public function store(Request $request)
    {
        $orderItem = OrderItem::create([
            'order_id' => $request->order_id,
            'inventory_item_id' => $request->inventory_item_id,
            'quantity' => $request->quantity,
        ]);

        return response()->json($orderItem, 201);
    }

    public function show(OrderItem $orderItem)
    {
        return response()->json($orderItem, 200);
    }

    public function update(Request $request, OrderItem $orderItem)
    {
        $orderItem->update([
            'order_id' => $request->order_id ?? $orderItem->order_id,
            'inventory_item_id' => $request->inventory_item_id ?? $orderItem->inventory_item_id,
            'quantity' => $request->quantity ?? $orderItem->quantity,
        ]);

        return response()->json($orderItem, 200);
    }

    public function destroy(OrderItem $orderItem)
    {
        $orderItem->delete();
        return response()->json(['message' => 'Позицію замовлення видалено'], 200);
    }
}
