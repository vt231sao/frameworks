<?php

namespace App\Controller;

use App\Entity\OrderItem;
use App\Entity\WarehouseOrder;
use App\Entity\InventoryItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/order-items')]
class OrderItemController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $orderItems = $em->getRepository(OrderItem::class)->findAll();
        return $this->json($orderItems);
    }

    #[Route('', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $order = $em->getRepository(WarehouseOrder::class)->find($data['order_id']);
        $inventoryItem = $em->getRepository(InventoryItem::class)->find($data['inventory_item_id']);

        if (!$order || !$inventoryItem) {
            return $this->json(['message' => 'Замовлення або товар не знайдено'], 400);
        }

        $orderItem = new OrderItem();
        $orderItem->setQuantity($data['quantity']);
        $orderItem->setWarehouseOrder($order);
        $orderItem->setInventoryItem($inventoryItem);

        $em->persist($orderItem);
        $em->flush();

        return $this->json($orderItem, 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $em): JsonResponse
    {
        $orderItem = $em->getRepository(OrderItem::class)->find($id);
        if (!$orderItem) return $this->json(['message' => 'Не знайдено'], 404);
        return $this->json($orderItem);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $orderItem = $em->getRepository(OrderItem::class)->find($id);
        if (!$orderItem) return $this->json(['message' => 'Не знайдено'], 404);

        $data = json_decode($request->getContent(), true);
        if (isset($data['quantity'])) $orderItem->setQuantity($data['quantity']);

        $em->flush();
        return $this->json($orderItem);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function destroy(int $id, EntityManagerInterface $em): JsonResponse
    {
        $orderItem = $em->getRepository(OrderItem::class)->find($id);
        if (!$orderItem) return $this->json(['message' => 'Не знайдено'], 404);

        $em->remove($orderItem);
        $em->flush();
        return $this->json(['message' => 'Позицію замовлення видалено']);
    }
}