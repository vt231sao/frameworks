<?php

namespace App\Repository;

use App\Entity\WarehouseOrder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractFilterRepository<WarehouseOrder>
 */
class WarehouseOrderRepository extends AbstractFilterRepository
{
    public const FIELDS = [
        "id"            => ["field" => "id", "type" => "EQUAL"],
        "customer_name" => ["field" => "customerName", "type" => "LIKE"],
        "status"        => ["field" => "status", "type" => "EQUAL"]
    ];

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WarehouseOrder::class);
    }
}
