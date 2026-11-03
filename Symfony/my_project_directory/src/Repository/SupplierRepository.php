<?php

namespace App\Repository;

use App\Entity\Supplier;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractFilterRepository<Supplier>
 */
class SupplierRepository extends AbstractFilterRepository
{
    public const FIELDS = [
        "id"            => ["field" => "id", "type" => "EQUAL"],
        "name"          => ["field" => "name", "type" => "LIKE"],
        "contact_email" => ["field" => "contactEmail", "type" => "LIKE"],
        "phone"         => ["field" => "phone", "type" => "LIKE"]
    ];

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Supplier::class);
    }
}
