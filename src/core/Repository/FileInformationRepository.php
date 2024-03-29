<?php

namespace App\Core\Repository;

use App\Core\Entity\FileInformation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|FileInformation find($id, $lockMode = null, $lockVersion = null)
 * @method null|FileInformation findOneBy(array $criteria, array $orderBy = null)
 * @method FileInformation[]    findAll()
 * @method FileInformation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FileInformationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FileInformation::class);
    }
}
