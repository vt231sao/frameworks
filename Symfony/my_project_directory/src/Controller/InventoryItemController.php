<?php
namespace App\Controller;

use App\Entity\InventoryItem;
use App\Entity\Category;
use App\Entity\Supplier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/inventory-items', name: 'api_inventory_items_')]
class InventoryItemController extends AbstractController
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
        $items = $this->entityManager->getRepository(InventoryItem::class)->findAll();
        return $this->json($items, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        /** @var InventoryItem $item */
        $item = $this->entityManager->getRepository(InventoryItem::class)->findOneBy(['id' => $id]);

        if (empty($item)) {
            return $this->json(['message' => 'Item not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($item, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('', name: 'store', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $category = $this->entityManager->getRepository(Category::class)->findOneBy(['id' => $data['category_id']]);
        $supplier = $this->entityManager->getRepository(Supplier::class)->findOneBy(['id' => $data['supplier_id']]);

        if (empty($category) || empty($supplier)) {
            return $this->json(['message' => 'Category or Supplier not found'], Response::HTTP_NOT_FOUND);
        }

        $item = new InventoryItem();
        $item->setName($data['name']);
        $item->setPrice($data['price']);
        $item->setStockQuantity($data['stock_quantity']);
        $item->setCategory($category);
        $item->setSupplier($supplier);

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        return $this->json($item, Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(string $id, Request $request): JsonResponse
    {
        /** @var InventoryItem $item */
        $item = $this->entityManager->getRepository(InventoryItem::class)->findOneBy(['id' => $id]);

        if (empty($item)) {
            return $this->json(['message' => 'Item not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $item->setName($data['name']);
        }
        if (isset($data['price'])) {
            $item->setPrice($data['price']);
        }
        if (isset($data['stock_quantity'])) {
            $item->setStockQuantity($data['stock_quantity']);
        }

        if (isset($data['category_id'])) {
            $category = $this->entityManager->getRepository(Category::class)->findOneBy(['id' => $data['category_id']]);
            if (!empty($category)) {
                $item->setCategory($category);
            }
        }
        if (isset($data['supplier_id'])) {
            $supplier = $this->entityManager->getRepository(Supplier::class)->findOneBy(['id' => $data['supplier_id']]);
            if (!empty($supplier)) {
                $item->setSupplier($supplier);
            }
        }

        $this->entityManager->flush();
        return $this->json($item, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'destroy', methods: ['DELETE'])]
    public function destroy(string $id): JsonResponse
    {
        /** @var InventoryItem $item */
        $item = $this->entityManager->getRepository(InventoryItem::class)->findOneBy(['id' => $id]);

        if (empty($item)) {
            return $this->json(['message' => 'Item not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($item);
        $this->entityManager->flush();
        return $this->json([], Response::HTTP_NOT_FOUND);
    }
}