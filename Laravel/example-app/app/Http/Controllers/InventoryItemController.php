<?php
namespace App\Http\Controllers;

use App\Models\InventoryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class InventoryItemController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $items = InventoryItem::all();
        return response()->json($items, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $item = InventoryItem::find($id);
        if (empty($item)) {
            return response()->json(['message' => 'Item not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($item, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $item = DB::transaction(function () use ($data) {
            $item = new InventoryItem();
            $item->fill($data);
            $item->save();
            return $item;
        });

        return new JsonResponse($item, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $item = InventoryItem::find($id);
        if (empty($item)) {
            return response()->json(['message' => 'Item not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $item->name = $data['name'];
        }
        if (isset($data['price'])) {
            $item->price = $data['price'];
        }
        if (isset($data['stock_quantity'])) {
            $item->stock_quantity = $data['stock_quantity'];
        }
        if (isset($data['category_id'])) {
            $item->category_id = $data['category_id'];
        }
        if (isset($data['supplier_id'])) {
            $item->supplier_id = $data['supplier_id'];
        }
        $item->save();

        return new JsonResponse($item, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $item = InventoryItem::find($id);
        if (empty($item)) {
            return response()->json(['message' => 'Item not found'], Response::HTTP_NOT_FOUND);
        }
        $item->delete();
        return new JsonResponse([], Response::HTTP_NOT_FOUND);
    }
}
