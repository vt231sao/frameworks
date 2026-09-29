<?php

namespace App\Controller;

use App\Entity\Supplier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/suppliers')]
class SupplierController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $suppliers = $em->getRepository(Supplier::class)->findAll();
        return $this->json($suppliers);
    }

    #[Route('', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $supplier = new Supplier();
        $supplier->setName($data['name']);
        $supplier->setContactEmail($data['contact_email']);
        if (isset($data['phone'])) {
            $supplier->setPhone($data['phone']);
        }

        $em->persist($supplier);
        $em->flush();

        return $this->json($supplier, 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $em): JsonResponse
    {
        $supplier = $em->getRepository(Supplier::class)->find($id);
        if (!$supplier) return $this->json(['message' => 'Не знайдено'], 404);
        return $this->json($supplier);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $supplier = $em->getRepository(Supplier::class)->find($id);
        if (!$supplier) return $this->json(['message' => 'Не знайдено'], 404);

        $data = json_decode($request->getContent(), true);
        if (isset($data['name'])) $supplier->setName($data['name']);
        if (isset($data['contact_email'])) $supplier->setContactEmail($data['contact_email']);
        if (isset($data['phone'])) $supplier->setPhone($data['phone']);

        $em->flush();
        return $this->json($supplier);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function destroy(int $id, EntityManagerInterface $em): JsonResponse
    {
        $supplier = $em->getRepository(Supplier::class)->find($id);
        if (!$supplier) return $this->json(['message' => 'Не знайдено'], 404);

        $em->remove($supplier);
        $em->flush();
        return $this->json(['message' => 'Постачальника видалено']);
    }
}