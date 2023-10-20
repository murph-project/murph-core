<?php

namespace App\Core\EventSubscriber\Site;

use App\Core\Cache\SymfonyCacheManager;
use App\Core\Entity\EntityInterface;
use App\Core\Entity\Site\Menu;
use App\Core\Entity\Site\Navigation;
use App\Core\Entity\Site\Node;
use App\Core\Event\EntityManager\EntityManagerEvent;
use App\Core\EventSubscriber\EntityManagerEventSubscriber;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * class SiteEventSubscriber.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class SiteEventSubscriber extends EntityManagerEventSubscriber
{
    public function __construct(
        protected KernelInterface $kernel,
        protected SymfonyCacheManager $cacheManager
    ) {
    }

    public function supports(EntityInterface $entity): bool
    {
        return $entity instanceof Node || $entity instanceof Menu || $entity instanceof Navigation;
    }

    public function onUpdate(EntityManagerEvent $event)
    {
        if (!$this->supports($event->getEntity())) {
            return;
        }

        $this->cacheManager->cleanRouting();
    }

    public function onCreate(EntityManagerEvent $event)
    {
        return $this->onUpdate($event);
    }

    public function onDelete(EntityManagerEvent $event)
    {
        return $this->onUpdate($event);
    }
}
