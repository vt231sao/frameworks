<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryItemController extends Controller
{
    public function index()
    {
        return response()->json(InventoryItem::all(), 200);
    }

    public function store(Request $request)
    {
        $item = InventoryItem::create([
            'name' => $request->name,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category_id,
            'supplier_id' => $request->supplier_id,
        ]);

        return response()->json($item, 201);
    }

    public function show(InventoryItem $inventoryItem)
    {
        return response()->json($inventoryItem, 200);
    }

    public function update(Request $request, InventoryItem $inventoryItem)
    {
        $inventoryItem->update([
            'name' => $request->name ?? $inventoryItem->name,
            'price' => $request->price ?? $inventoryItem->price,
            'stock_quantity' => $request->stock_quantity ?? $inventoryItem->stock_quantity,
            'category_id' => $request->category_id ?? $inventoryItem->category_id,
            'supplier_id' => $request->supplier_id ?? $inventoryItem->supplier_id,
        ]);

        return response()->json($inventoryItem, 200);
    }

    public function destroy(InventoryItem $inventoryItem)
    {
        $inventoryItem->delete();
        return response()->json(['message' => 'Товар видалено'], 200);
    }
}
