<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/products', name: 'products_')]
class ProductController extends AbstractController
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

    #[Route('', name: 'index', methods: ['GET'])]
    public function getProducts(): JsonResponse
    {
        return new JsonResponse(['data' => $this->products], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function getProductItem(string $id): JsonResponse
    {
        $product = $this->getProductItemById($id);

        if (!$product) {
            return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['data' => $product], Response::HTTP_OK);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function createProduct(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $newProduct = [
            'id' => (string) random_int(1, 1000),
            'name' => $requestData['name'] ?? 'Unknown',
            'price' => $requestData['price'] ?? 0,
        ];

        $this->products[] = $newProduct;

        return new JsonResponse(['data' => $newProduct], Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update', methods: ['PUT'])]
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
            return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        $requestData = json_decode($request->getContent(), true);

        if (isset($requestData['name'])) {
            $this->products[$productIndex]['name'] = $requestData['name'];
        }
        if (isset($requestData['price'])) {
            $this->products[$productIndex]['price'] = $requestData['price'];
        }

        return new JsonResponse([
            'message' => "Product $id updated",
            'data' => $this->products[$productIndex]
        ], Response::HTTP_OK);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
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
            return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        unset($this->products[$productIndex]);

        $this->products = array_values($this->products);

        return new JsonResponse([
            'message' => "Product $id deleted",
            'data' => $this->products
        ], Response::HTTP_OK);
    }
}