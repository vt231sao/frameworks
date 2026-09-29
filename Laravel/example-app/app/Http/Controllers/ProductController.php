<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    private array $products = [
        ['id' => '1', 'name' => 'Laptop', 'price' => 1500],
        ['id' => '2', 'name' => 'Mouse', 'price' => 50],
        ['id' => '3', 'name' => 'Headphones', 'price' => 500],
        ['id' => '4', 'name' => 'Mouse', 'price' => 100],
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

        $this->products[] = $newProduct;

        return response()->json(['data' => $newProduct], Response::HTTP_CREATED);
    }

    public function updateProduct(Request $request, string $id): JsonResponse
    {
        $productIndex = null;
        foreach ($this->products as $index => $product) {
            if ($product['id'] === $id) {
                $productIndex = $index;
                break;
            }
        }

        if ($productIndex === null) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);

        if (isset($requestData['name'])) {
            $this->products[$productIndex]['name'] = $requestData['name'];
        }
        if (isset($requestData['price'])) {
            $this->products[$productIndex]['price'] = $requestData['price'];
        }

        return response()->json([
            'message' => "Product $id updated",
            'data' => $this->products[$productIndex]
        ], Response::HTTP_OK);
    }

    public function deleteProduct(string $id): JsonResponse
    {
        $productIndex = null;
        foreach ($this->products as $index => $product) {
            if ($product['id'] === $id) {
                $productIndex = $index;
                break;
            }
        }

        if ($productIndex === null) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        unset($this->products[$productIndex]);
        $this->products = array_values($this->products);

        return response()->json([
            'message' => "Product $id deleted",
            'data' => $this->products
        ], Response::HTTP_OK);
    }
}
