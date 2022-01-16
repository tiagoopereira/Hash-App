<?php

namespace App\Repository;

use App\Entity\Hash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Hash|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hash|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hash[]    findAll()
 * @method Hash[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HashRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hash::class);
    }

    /**
     * @return Hash[]
     */
    public function getAll(int $limit, int  $offset, array $filter =  null): array
    {
        $queryBuilder = $this->createQueryBuilder('h')
            ->orderBy('h.block', 'ASC')
            ->orderBy('h.batch', 'DESC')
            ->setFirstResult(($offset - 1) * $limit)
            ->setMaxResults($limit);

        if (is_null($filter)) {
            return $queryBuilder->getQuery()->getResult();
        }

        $exp = $filter['exp'];
        $key = $filter['key'];
        $value = $filter['value'];

        $where = $queryBuilder->expr()->$exp("h.{$key}", $value);
        $queryBuilder->where($where);

        return $queryBuilder->getQuery()->getResult();
    }
}
