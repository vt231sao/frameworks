<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = Category::query();

        if ($request->filled('id')) {
            $query->where('id', $request->query('id'));
        }
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->query('name') . '%');
        }
        if ($request->filled('description')) {
            $query->where('description', 'like', '%' . $request->query('description') . '%');
        }

        $page = max(1, (int)$request->query('page', 1));
        $itemsPerPage = max(1, (int)$request->query('itemsPerPage', 10));

        $categories = $query
            ->orderBy('id')
            ->skip(($page - 1) * $itemsPerPage)
            ->take($itemsPerPage)
            ->get();

        return response()->json($categories, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $category = Category::find($id);
        if (empty($category)) {
            return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($category, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['name'])) {
            return response()->json(['message' => 'Field name is required'], Response::HTTP_BAD_REQUEST);
        }

        $category = DB::transaction(function () use ($data) {
            $category = new Category();
            $category->fill($data);
            $category->save();
            return $category;
        });

        return new JsonResponse($category, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $category = Category::find($id);
        if (empty($category)) {
            return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $category->name = $data['name'];
        }
        if (isset($data['description'])) {
            $category->description = $data['description'];
        }
        $category->save();

        return new JsonResponse($category, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $category = Category::find($id);
        if (empty($category)) {
            return response()->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }
        $category->delete();
        return response()->json(['message' => 'Category deleted'], Response::HTTP_OK);
    }
}
