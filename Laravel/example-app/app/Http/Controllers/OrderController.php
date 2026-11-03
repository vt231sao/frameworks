<?php
namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Order::query();

        if ($request->filled('id')) {
            $query->where('id', $request->query('id'));
        }
        if ($request->filled('customer_name')) {
            $query->where('customer_name', 'like', '%' . $request->query('customer_name') . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $page = max(1, (int)$request->query('page', 1));
        $itemsPerPage = max(1, (int)$request->query('itemsPerPage', 10));

        $orders = $query
            ->orderBy('id')
            ->skip(($page - 1) * $itemsPerPage)
            ->take($itemsPerPage)
            ->get();

        return response()->json($orders, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $order = Order::find($id);
        if (empty($order)) {
            return response()->json(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($order, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['customer_name'])) {
            return response()->json(['message' => 'Field customer_name is required'], Response::HTTP_BAD_REQUEST);
        }

        $order = DB::transaction(function () use ($data) {
            $order = new Order();
            $order->fill($data);
            if (!isset($data['status'])) {
                $order->status = 'pending';
            }
            $order->save();
            return $order;
        });

        return new JsonResponse($order, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $order = Order::find($id);
        if (empty($order)) {
            return response()->json(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['customer_name'])) {
            $order->customer_name = $data['customer_name'];
        }
        if (isset($data['status'])) {
            $order->status = $data['status'];
        }
        $order->save();

        return new JsonResponse($order, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $order = Order::find($id);
        if (empty($order)) {
            return response()->json(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }
        $order->delete();
        return response()->json(['message' => 'Order deleted'], Response::HTTP_OK);
    }
}
