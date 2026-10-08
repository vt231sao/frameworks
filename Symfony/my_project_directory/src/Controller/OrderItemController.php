<?php
namespace App\Controller;

use App\Entity\OrderItem;
use App\Entity\WarehouseOrder;
use App\Entity\InventoryItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/order-items', name: 'api_order_items_')]
class OrderItemController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @return JsonResponse
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $items = $this->entityManager->getRepository(OrderItem::class)->findAll();
        return $this->json($items, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        /** @var OrderItem $orderItem */
        $orderItem = $this->entityManager->getRepository(OrderItem::class)->findOneBy(['id' => $id]);

        if (empty($orderItem)) {
            return $this->json(['message' => 'Order item not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($orderItem, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('', name: 'store', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $order = $this->entityManager->getRepository(WarehouseOrder::class)->findOneBy(['id' => $data['order_id']]);
        $inventoryItem = $this->entityManager->getRepository(InventoryItem::class)->findOneBy(['id' => $data['inventory_item_id']]);

        if (empty($order) || empty($inventoryItem)) {
            return $this->json(['message' => 'Order or InventoryItem not found'], Response::HTTP_NOT_FOUND);
        }

        $orderItem = new OrderItem();
        $orderItem->setQuantity($data['quantity']);
        $orderItem->setWarehouseOrder($order);
        $orderItem->setInventoryItem($inventoryItem);

        $this->entityManager->persist($orderItem);
        $this->entityManager->flush();

        return $this->json($orderItem, Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(string $id, Request $request): JsonResponse
    {
        /** @var OrderItem $orderItem */
        $orderItem = $this->entityManager->getRepository(OrderItem::class)->findOneBy(['id' => $id]);

        if (empty($orderItem)) {
            return $this->json(['message' => 'Order item not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['quantity'])) {
            $orderItem->setQuantity($data['quantity']);
        }

        $this->entityManager->flush();
        return $this->json($orderItem, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'destroy', methods: ['DELETE'])]
    public function destroy(string $id): JsonResponse
    {
        /** @var OrderItem $orderItem */
        $orderItem = $this->entityManager->getRepository(OrderItem::class)->findOneBy(['id' => $id]);

        if (empty($orderItem)) {
            return $this->json(['message' => 'Order item not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($orderItem);
        $this->entityManager->flush();
        return $this->json([], Response::HTTP_NOT_FOUND);
    }
}