<?php

namespace App\Core\EventSubscriber\Site;

use App\Core\Entity\EntityInterface;
use App\Core\Entity\Site\Navigation;
use App\Core\Event\EntityManager\EntityManagerEvent;
use App\Core\EventSubscriber\EntityManagerEventSubscriber;
use App\Core\Manager\EntityManager;
use App\Core\Slugify\CodeSlugify;

/**
 * class NavigationEventSubscriber.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class NavigationEventSubscriber extends EntityManagerEventSubscriber
{
    public function __construct(
        protected EntityManager $entityManager,
        protected CodeSlugify $slugify
    ) {
    }

    public function supports(EntityInterface $entity): bool
    {
        return $entity instanceof Navigation;
    }

    public function onPreUpdate(EntityManagerEvent $event)
    {
        if (!$this->supports($event->getEntity())) {
            return;
        }

        $menu = $event->getEntity();
        $menu->setCode($this->slugify->slugify($menu->getCode()));
    }

    public function onPreCreate(EntityManagerEvent $event)
    {
        return $this->onPreUpdate($event);
    }
}
