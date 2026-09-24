<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    private array $products = [
        ['id' => '1', 'name' => 'Laptop', 'price' => 1500],
    ];

    private function getProductItemById(string $id): ?array
    {
        foreach ($this->products as $product) {
            if ($product['id'] === $id) return $product;
        }
        return null;
    }

    public function getProducts(): JsonResponse
    {
        return response()->json(['data' => $this->products], Response::HTTP_OK);
    }

    public function getProductItem(string $id): JsonResponse
    {
        $product = $this->getProductItemById($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json(['data' => $product], Response::HTTP_OK);
    }

    public function createProduct(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $newProduct = [
            'id' => (string) random_int(1, 1000),
            'name' => $requestData['name'] ?? 'Unknown',
            'price' => $requestData['price'] ?? 0,
        ];

        // TODO: insert to db

        return response()->json(['data' => $newProduct], Response::HTTP_CREATED);
    }

    public function updateProduct(Request $request, string $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);
        // TODO: update in db
        return response()->json(['message' => "Product $id updated", 'data' => $requestData], Response::HTTP_OK);
    }

    public function deleteProduct(string $id): JsonResponse
    {
        // TODO: delete from db
        return response()->json(['message' => "Product $id deleted"], Response::HTTP_OK);
    }
}
