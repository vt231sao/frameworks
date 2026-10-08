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
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $suppliers = Supplier::all();
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
        return new JsonResponse([], Response::HTTP_NOT_FOUND);
    }
}
