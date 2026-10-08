<?php
namespace App\Controller;

use App\Entity\Supplier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/suppliers', name: 'api_suppliers_')]
class SupplierController extends AbstractController
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
        $suppliers = $this->entityManager->getRepository(Supplier::class)->findAll();
        return $this->json($suppliers, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        /** @var Supplier $supplier */
        $supplier = $this->entityManager->getRepository(Supplier::class)->findOneBy(['id' => $id]);

        if (empty($supplier)) {
            return $this->json(['message' => 'Supplier not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($supplier, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('', name: 'store', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $supplier = new Supplier();
        $supplier->setName($data['name']);
        $supplier->setContactEmail($data['contact_email']);
        if (isset($data['phone'])) {
            $supplier->setPhone($data['phone']);
        }

        $this->entityManager->persist($supplier);
        $this->entityManager->flush();

        return $this->json($supplier, Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(string $id, Request $request): JsonResponse
    {
        /** @var Supplier $supplier */
        $supplier = $this->entityManager->getRepository(Supplier::class)->findOneBy(['id' => $id]);

        if (empty($supplier)) {
            return $this->json(['message' => 'Supplier not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $supplier->setName($data['name']);
        }
        if (isset($data['contact_email'])) {
            $supplier->setContactEmail($data['contact_email']);
        }
        if (isset($data['phone'])) {
            $supplier->setPhone($data['phone']);
        }

        $this->entityManager->flush();
        return $this->json($supplier, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'destroy', methods: ['DELETE'])]
    public function destroy(string $id): JsonResponse
    {
        /** @var Supplier $supplier */
        $supplier = $this->entityManager->getRepository(Supplier::class)->findOneBy(['id' => $id]);

        if (empty($supplier)) {
            return $this->json(['message' => 'Supplier not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($supplier);
        $this->entityManager->flush();
        return $this->json([], Response::HTTP_NOT_FOUND);
    }
}