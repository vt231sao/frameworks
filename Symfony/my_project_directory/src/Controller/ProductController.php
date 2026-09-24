<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/products')]
class ProductController extends AbstractController
{
    // Імітація бази даних для прикладу
    private array $products = [
        ['id' => '1', 'name' => 'Laptop', 'price' => 1500],
        ['id' => '2', 'name' => 'Mouse', 'price' => 50],
    ];

    private function getProductItemById(string $id): ?array
    {
        foreach ($this->products as $product) {
            if ($product['id'] === $id) return $product;
        }
        return null;
    }

    #[Route('', methods: ['GET'])]
    public function getProducts(): JsonResponse
    {
        return new JsonResponse(['data' => $this->products], Response::HTTP_OK);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function getProductItem(string $id): JsonResponse
    {
        $product = $this->getProductItemById($id);

        if (!$product) {
            return new JsonResponse(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['data' => $product], Response::HTTP_OK);
    }

    #[Route('', methods: ['POST'])]
    public function createProduct(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $newProduct = [
            'id' => (string) random_int(1, 1000),
            'name' => $requestData['name'] ?? 'Unknown',
            'price' => $requestData['price'] ?? 0,
        ];

        // TODO: insert to db

        return new JsonResponse(['data' => $newProduct], Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function updateProduct(Request $request, string $id): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        // TODO: update in db

        return new JsonResponse(['message' => "Product $id updated", 'data' => $requestData], Response::HTTP_OK);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function deleteProduct(string $id): JsonResponse
    {
        // TODO: delete from db

        return new JsonResponse(['message' => "Product $id deleted"], Response::HTTP_OK);
    }
}