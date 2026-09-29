<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/categories')]
class CategoryController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $categories = $em->getRepository(Category::class)->findAll();
        return $this->json($categories);
    }

    #[Route('', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $category = new Category();
        $category->setName($data['name']);
        if (isset($data['description'])) {
            $category->setDescription($data['description']);
        }

        $em->persist($category);
        $em->flush();

        return $this->json($category, 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $em): JsonResponse
    {
        $category = $em->getRepository(Category::class)->find($id);
        if (!$category) return $this->json(['message' => 'Не знайдено'], 404);
        return $this->json($category);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $category = $em->getRepository(Category::class)->find($id);
        if (!$category) return $this->json(['message' => 'Не знайдено'], 404);

        $data = json_decode($request->getContent(), true);
        if (isset($data['name'])) $category->setName($data['name']);
        if (isset($data['description'])) $category->setDescription($data['description']);

        $em->flush();
        return $this->json($category);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function destroy(int $id, EntityManagerInterface $em): JsonResponse
    {
        $category = $em->getRepository(Category::class)->find($id);
        if (!$category) return $this->json(['message' => 'Не знайдено'], 404);

        $em->remove($category);
        $em->flush();
        return $this->json(['message' => 'Категорію видалено']);
    }
}