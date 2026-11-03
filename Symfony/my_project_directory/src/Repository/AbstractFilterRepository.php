<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * @template T of object
 * @extends ServiceEntityRepository<T>
 */
abstract class AbstractFilterRepository extends ServiceEntityRepository
{
    public const FIELDS = [];

    /**
     * @param array $criteria
     * @return array
     */
    public function getItems(array $criteria = []): array
    {
        $queryBuilder = $this->createQueryBuilder('item');

        $queryBuilder = $this->filterData($queryBuilder, $criteria);

        $page = max(1, (int)($criteria['page'] ?? 1));
        $itemsPerPage = max(1, (int)($criteria['itemsPerPage'] ?? 10));

        return $queryBuilder
            ->orderBy('item.id', 'ASC')
            ->setFirstResult(($page - 1) * $itemsPerPage)
            ->setMaxResults($itemsPerPage)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param array $criteria
     * @return QueryBuilder
     */
    public function filterData(QueryBuilder $queryBuilder, array $criteria = []): QueryBuilder
    {
        foreach (static::FIELDS as $key => $options) {
            if (!isset($criteria[$key])) {
                continue;
            }

            $field = 'item.' . $options['field'];

            if ($options['type'] == 'BETWEEN') {
                if (!is_array($criteria[$key])) {
                    continue;
                }

                if (isset($criteria[$key]['gte'])) {
                    $queryBuilder
                        ->andWhere($queryBuilder->expr()->gte($field, ':' . $key . 'Gte'))
                        ->setParameter($key . 'Gte', $criteria[$key]['gte']);
                }

                if (isset($criteria[$key]['lte'])) {
                    $queryBuilder
                        ->andWhere($queryBuilder->expr()->lte($field, ':' . $key . 'Lte'))
                        ->setParameter($key . 'Lte', $criteria[$key]['lte']);
                }

                continue;
            }

            if (is_array($criteria[$key])) {
                continue;
            }

            if ($options['type'] == 'LIKE') {
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->like($field, ':' . $key))
                    ->setParameter($key, '%' . $criteria[$key] . '%');
            }

            if ($options['type'] == 'EQUAL') {
                $queryBuilder
                    ->andWhere($queryBuilder->expr()->eq($field, ':' . $key))
                    ->setParameter($key, $criteria[$key]);
            }
        }

        return $queryBuilder;
    }
}
