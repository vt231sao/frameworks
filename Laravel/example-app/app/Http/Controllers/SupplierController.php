<?php
namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SupplierController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Supplier::query();

        if ($request->filled('id')) {
            $query->where('id', $request->query('id'));
        }
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->query('name') . '%');
        }
        if ($request->filled('contact_email')) {
            $query->where('contact_email', 'like', '%' . $request->query('contact_email') . '%');
        }
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->query('phone') . '%');
        }

        $page = max(1, (int)$request->query('page', 1));
        $itemsPerPage = max(1, (int)$request->query('itemsPerPage', 10));

        $suppliers = $query
            ->orderBy('id')
            ->skip(($page - 1) * $itemsPerPage)
            ->take($itemsPerPage)
            ->get();

        return response()->json($suppliers, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $supplier = Supplier::find($id);
        if (empty($supplier)) {
            return response()->json(['message' => 'Supplier not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($supplier, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['name']) || empty($data['contact_email'])) {
            return response()->json(['message' => 'Fields name, contact_email are required'], Response::HTTP_BAD_REQUEST);
        }

        $supplier = DB::transaction(function () use ($data) {
            $supplier = new Supplier();
            $supplier->fill($data);
            $supplier->save();
            return $supplier;
        });

        return new JsonResponse($supplier, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $supplier = Supplier::find($id);
        if (empty($supplier)) {
            return response()->json(['message' => 'Supplier not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $supplier->name = $data['name'];
        }
        if (isset($data['contact_email'])) {
            $supplier->contact_email = $data['contact_email'];
        }
        if (isset($data['phone'])) {
            $supplier->phone = $data['phone'];
        }
        $supplier->save();

        return new JsonResponse($supplier, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $supplier = Supplier::find($id);
        if (empty($supplier)) {
            return response()->json(['message' => 'Supplier not found'], Response::HTTP_NOT_FOUND);
        }
        $supplier->delete();
        return response()->json(['message' => 'Supplier deleted'], Response::HTTP_OK);
    }
}
