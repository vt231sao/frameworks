<?php
namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/categories', name: 'api_categories_')]
class CategoryController extends AbstractController
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
        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        return $this->json($categories, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(string $id): JsonResponse
    {
        /** @var Category $category */
        $category = $this->entityManager->getRepository(Category::class)->findOneBy(['id' => $id]);

        if (empty($category)) {
            return $this->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($category, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('', name: 'store', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $category = new Category();
        $category->setName($data['name']);
        if (isset($data['description'])) {
            $category->setDescription($data['description']);
        }

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return $this->json($category, Response::HTTP_CREATED);
    }

    /**
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'update', methods: ['PUT'])]
    public function update(string $id, Request $request): JsonResponse
    {
        /** @var Category $category */
        $category = $this->entityManager->getRepository(Category::class)->findOneBy(['id' => $id]);

        if (empty($category)) {
            return $this->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $category->setName($data['name']);
        }
        if (isset($data['description'])) {
            $category->setDescription($data['description']);
        }

        $this->entityManager->flush();
        return $this->json($category, Response::HTTP_OK);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    #[Route('/{id}', name: 'destroy', methods: ['DELETE'])]
    public function destroy(string $id): JsonResponse
    {
        /** @var Category $category */
        $category = $this->entityManager->getRepository(Category::class)->findOneBy(['id' => $id]);

        if (empty($category)) {
            return $this->json(['message' => 'Category not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($category);
        $this->entityManager->flush();
        return $this->json([], Response::HTTP_NOT_FOUND);
    }
}