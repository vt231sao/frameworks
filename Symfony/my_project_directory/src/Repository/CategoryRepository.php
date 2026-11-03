<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractFilterRepository<Category>
 */
class CategoryRepository extends AbstractFilterRepository
{
    public const FIELDS = [
        "id"          => ["field" => "id", "type" => "EQUAL"],
        "name"        => ["field" => "name", "type" => "LIKE"],
        "description" => ["field" => "description", "type" => "LIKE"]
    ];

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }
}
