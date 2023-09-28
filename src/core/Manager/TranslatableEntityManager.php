<?php

namespace App\Core\Manager;

use App\Core\Entity\EntityInterface;

/**
 * class TranslatableEntityManager.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class TranslatableEntityManager extends EntityManager
{
    protected function persist(EntityInterface $entity, bool $flush = true)
    {
        $this->entityManager->persist($entity, $flush);
        $entity->mergeNewTranslations();

        if ($flush) {
            $this->flush();
        }
    }
}
