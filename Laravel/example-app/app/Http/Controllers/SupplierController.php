<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        return response()->json(Supplier::all(), 200);
    }

    public function store(Request $request)
    {
        $supplier = Supplier::create([
            'name' => $request->name,
            'contact_email' => $request->contact_email,
            'phone' => $request->phone,
        ]);

        return response()->json($supplier, 201);
    }

    public function show(Supplier $supplier)
    {
        return response()->json($supplier, 200);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $supplier->update([
            'name' => $request->name ?? $supplier->name,
            'contact_email' => $request->contact_email ?? $supplier->contact_email,
            'phone' => $request->phone ?? $supplier->phone,
        ]);

        return response()->json($supplier, 200);
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->json(['message' => 'Постачальника видалено'], 200);
    }
}
