<?php

namespace App\Repository;

use App\Entity\InventoryItem;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractFilterRepository<InventoryItem>
 */
class InventoryItemRepository extends AbstractFilterRepository
{
    public const FIELDS = [
        "id"             => ["field" => "id", "type" => "EQUAL"],
        "name"           => ["field" => "name", "type" => "LIKE"],
        "price"          => ["field" => "price", "type" => "BETWEEN"],
        "stock_quantity" => ["field" => "stockQuantity", "type" => "BETWEEN"],
        "category_id"    => ["field" => "category", "type" => "EQUAL"],
        "supplier_id"    => ["field" => "supplier", "type" => "EQUAL"]
    ];

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InventoryItem::class);
    }
}
