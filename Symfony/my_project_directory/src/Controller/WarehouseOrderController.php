<?php

namespace App\Controller;

use App\Entity\WarehouseOrder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/orders')]
class WarehouseOrderController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $orders = $em->getRepository(WarehouseOrder::class)->findAll();
        return $this->json($orders);
    }

    #[Route('', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $order = new WarehouseOrder();
        $order->setCustomerName($data['customer_name']);
        $order->setStatus($data['status'] ?? 'pending');

        $em->persist($order);
        $em->flush();

        return $this->json($order, 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $em): JsonResponse
    {
        $order = $em->getRepository(WarehouseOrder::class)->find($id);
        if (!$order) return $this->json(['message' => 'Не знайдено'], 404);
        return $this->json($order);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $order = $em->getRepository(WarehouseOrder::class)->find($id);
        if (!$order) return $this->json(['message' => 'Не знайдено'], 404);

        $data = json_decode($request->getContent(), true);
        if (isset($data['customer_name'])) $order->setCustomerName($data['customer_name']);
        if (isset($data['status'])) $order->setStatus($data['status']);

        $em->flush();
        return $this->json($order);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function destroy(int $id, EntityManagerInterface $em): JsonResponse
    {
        $order = $em->getRepository(WarehouseOrder::class)->find($id);
        if (!$order) return $this->json(['message' => 'Не знайдено'], 404);

        $em->remove($order);
        $em->flush();
        return $this->json(['message' => 'Замовлення видалено']);
    }
}