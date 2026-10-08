<?php
namespace App\Controller;

use App\Entity\WarehouseOrder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/orders', name: 'api_orders_')]
class WarehouseOrderController extends AbstractController
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
        $orders = $this->entityManager->getRepository(WarehouseOrder::class)->findAll();
        return $this->json($orders, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        /** @var WarehouseOrder $order */
        $order = $this->entityManager->getRepository(WarehouseOrder::class)->findOneBy(['id' => $id]);

        if (empty($order)) {
            return $this->json(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($order, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('', name: 'store', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $order = new WarehouseOrder();
        $order->setCustomerName($data['customer_name']);
        if (isset($data['status'])) {
            $order->setStatus($data['status']);
        } else {
            $order->setStatus('pending');
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $this->json($order, Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(string $id, Request $request): JsonResponse
    {
        /** @var WarehouseOrder $order */
        $order = $this->entityManager->getRepository(WarehouseOrder::class)->findOneBy(['id' => $id]);

        if (empty($order)) {
            return $this->json(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['customer_name'])) {
            $order->setCustomerName($data['customer_name']);
        }
        if (isset($data['status'])) {
            $order->setStatus($data['status']);
        }

        $this->entityManager->flush();
        return $this->json($order, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'destroy', methods: ['DELETE'])]
    public function destroy(string $id): JsonResponse
    {
        /** @var WarehouseOrder $order */
        $order = $this->entityManager->getRepository(WarehouseOrder::class)->findOneBy(['id' => $id]);

        if (empty($order)) {
            return $this->json(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($order);
        $this->entityManager->flush();
        return $this->json([], Response::HTTP_NOT_FOUND);
    }
}