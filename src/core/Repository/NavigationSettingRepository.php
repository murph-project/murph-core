<?php

namespace App\Core\Repository;

use App\Core\Entity\NavigationSetting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|NavigationSetting find($id, $lockMode = null, $lockVersion = null)
 * @method null|NavigationSetting findOneBy(array $criteria, array $orderBy = null)
 * @method NavigationSetting[]    findAll()
 * @method NavigationSetting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NavigationSettingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NavigationSetting::class);
    }
}
