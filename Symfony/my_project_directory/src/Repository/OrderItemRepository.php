<?php

namespace App\Repository;

use App\Entity\OrderItem;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractFilterRepository<OrderItem>
 */
class OrderItemRepository extends AbstractFilterRepository
{
    public const FIELDS = [
        "id"                => ["field" => "id", "type" => "EQUAL"],
        "quantity"          => ["field" => "quantity", "type" => "BETWEEN"],
        "order_id"          => ["field" => "warehouseOrder", "type" => "EQUAL"],
        "inventory_item_id" => ["field" => "inventoryItem", "type" => "EQUAL"]
    ];

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderItem::class);
    }
}
