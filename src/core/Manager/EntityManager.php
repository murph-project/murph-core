<?php

namespace App\Core\Manager;

use App\Core\Entity\EntityInterface;
use App\Core\Event\EntityManager\EntityManagerEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * class EntityManager.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class EntityManager
{
    public function __construct(
        protected EventDispatcherInterface $eventDispatcher,
        protected EntityManagerInterface $entityManager
    ) {
    }

    public function create(EntityInterface $entity, bool $dispatchEvent = true, bool $flush = true): self
    {
        if ($dispatchEvent) {
            $this->eventDispatcher->dispatch(new EntityManagerEvent($entity), EntityManagerEvent::PRE_CREATE_EVENT);
        }

        $this->persist($entity, $flush);

        if ($dispatchEvent) {
            $this->eventDispatcher->dispatch(new EntityManagerEvent($entity), EntityManagerEvent::CREATE_EVENT);
        }

        return $this;
    }

    public function update(EntityInterface $entity, bool $dispatchEvent = true, bool $flush = true): self
    {
        if ($dispatchEvent) {
            $this->eventDispatcher->dispatch(new EntityManagerEvent($entity), EntityManagerEvent::PRE_UPDATE_EVENT);
        }

        $this->persist($entity, $flush);

        if ($dispatchEvent) {
            $this->eventDispatcher->dispatch(new EntityManagerEvent($entity), EntityManagerEvent::UPDATE_EVENT);
        }

        return $this;
    }

    public function delete(EntityInterface $entity, bool $dispatchEvent = true, bool $flush = true): self
    {
        if ($dispatchEvent) {
            $this->eventDispatcher->dispatch(new EntityManagerEvent($entity), EntityManagerEvent::PRE_DELETE_EVENT);
        }

        $this->entityManager->remove($entity);

        if ($flush) {
            $this->flush();
        }

        if ($dispatchEvent) {
            $this->eventDispatcher->dispatch(new EntityManagerEvent($entity), EntityManagerEvent::DELETE_EVENT);
        }

        return $this;
    }

    public function flush(): self
    {
        $this->entityManager->flush();

        return $this;
    }

    public function clear(): self
    {
        $this->entityManager->clear();

        return $this;
    }

    public function getEntityManager(): EntityManagerInterface
    {
        return $this->entityManager;
    }

    protected function persist(EntityInterface $entity, bool $flush = true)
    {
        $this->entityManager->persist($entity);

        if ($flush) {
            $this->flush();
        }
    }
}
