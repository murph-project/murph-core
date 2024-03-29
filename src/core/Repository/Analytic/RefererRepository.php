<?php

namespace App\Core\Repository\Analytic;

use App\Core\Entity\Analytic\Referer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|Referer find($id, $lockMode = null, $lockVersion = null)
 * @method null|Referer findOneBy(array $criteria, array $orderBy = null)
 * @method Referer[]    findAll()
 * @method Referer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RefererRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Referer::class);
    }
}
