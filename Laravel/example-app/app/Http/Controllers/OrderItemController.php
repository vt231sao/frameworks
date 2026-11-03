<?php
namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class OrderItemController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $items = OrderItem::all();
        return response()->json($items, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $item = OrderItem::find($id);
        if (empty($item)) {
            return response()->json(['message' => 'Order Item not found'], Response::HTTP_NOT_FOUND);
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

        if (!isset($data['quantity']) || !isset($data['order_id']) || !isset($data['inventory_item_id'])) {
            return response()->json(
                ['message' => 'Fields quantity, order_id, inventory_item_id are required'],
                Response::HTTP_BAD_REQUEST
            );
        }

        if (empty(Order::find($data['order_id'])) || empty(InventoryItem::find($data['inventory_item_id']))) {
            return response()->json(['message' => 'Order or InventoryItem not found'], Response::HTTP_NOT_FOUND);
        }

        $orderItem = DB::transaction(function () use ($data) {
            $orderItem = new OrderItem();
            $orderItem->fill($data);
            $orderItem->save();
            return $orderItem;
        });

        return new JsonResponse($orderItem, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $item = OrderItem::find($id);
        if (empty($item)) {
            return response()->json(['message' => 'Order Item not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['order_id'])) {
            if (empty(Order::find($data['order_id']))) {
                return response()->json(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
            }
            $item->order_id = $data['order_id'];
        }
        if (isset($data['inventory_item_id'])) {
            if (empty(InventoryItem::find($data['inventory_item_id']))) {
                return response()->json(['message' => 'Inventory Item not found'], Response::HTTP_NOT_FOUND);
            }
            $item->inventory_item_id = $data['inventory_item_id'];
        }
        if (isset($data['quantity'])) {
            $item->quantity = $data['quantity'];
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
        $item = OrderItem::find($id);
        if (empty($item)) {
            return response()->json(['message' => 'Order Item not found'], Response::HTTP_NOT_FOUND);
        }
        $item->delete();
        return response()->json(['message' => 'Order Item deleted'], Response::HTTP_OK);
    }
}
