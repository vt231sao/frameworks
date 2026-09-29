<?php

namespace App\Controller;

use App\Entity\InventoryItem;
use App\Entity\Category;
use App\Entity\Supplier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/inventory-items')]
class InventoryItemController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $items = $em->getRepository(InventoryItem::class)->findAll();
        return $this->json($items);
    }

    #[Route('', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $category = $em->getRepository(Category::class)->find($data['category_id']);
        $supplier = $em->getRepository(Supplier::class)->find($data['supplier_id']);

        if (!$category || !$supplier) {
            return $this->json(['message' => 'Категорія або постачальник не знайдені'], 400);
        }

        $item = new InventoryItem();
        $item->setName($data['name']);
        $item->setPrice($data['price']);
        $item->setStockQuantity($data['stock_quantity']);
        $item->setCategory($category);
        $item->setSupplier($supplier);

        $em->persist($item);
        $em->flush();

        return $this->json($item, 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $em): JsonResponse
    {
        $item = $em->getRepository(InventoryItem::class)->find($id);
        if (!$item) return $this->json(['message' => 'Не знайдено'], 404);
        return $this->json($item);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $item = $em->getRepository(InventoryItem::class)->find($id);
        if (!$item) return $this->json(['message' => 'Не знайдено'], 404);

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) $item->setName($data['name']);
        if (isset($data['price'])) $item->setPrice($data['price']);
        if (isset($data['stock_quantity'])) $item->setStockQuantity($data['stock_quantity']);

        if (isset($data['category_id'])) {
            $category = $em->getRepository(Category::class)->find($data['category_id']);
            if ($category) $item->setCategory($category);
        }

        if (isset($data['supplier_id'])) {
            $supplier = $em->getRepository(Supplier::class)->find($data['supplier_id']);
            if ($supplier) $item->setSupplier($supplier);
        }

        $em->flush();
        return $this->json($item);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function destroy(int $id, EntityManagerInterface $em): JsonResponse
    {
        $item = $em->getRepository(InventoryItem::class)->find($id);
        if (!$item) return $this->json(['message' => 'Не знайдено'], 404);

        $em->remove($item);
        $em->flush();
        return $this->json(['message' => 'Товар видалено']);
    }
}