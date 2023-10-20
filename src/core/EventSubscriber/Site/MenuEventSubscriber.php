<?php

namespace App\Core\EventSubscriber\Site;

use App\Core\Cache\SymfonyCacheManager;
use App\Core\Entity\EntityInterface;
use App\Core\Entity\Site\Menu;
use App\Core\Event\EntityManager\EntityManagerEvent;
use App\Core\EventSubscriber\EntityManagerEventSubscriber;
use App\Core\Factory\Site\NodeFactory;
use App\Core\Manager\EntityManager;
use App\Core\Repository\Site\NodeRepository;
use App\Core\Slugify\CodeSlugify;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * class MenuEventSubscriber.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class MenuEventSubscriber extends EntityManagerEventSubscriber
{
    public function __construct(
        protected NodeFactory $nodeFactory,
        protected NodeRepository $nodeRepository,
        protected EntityManager $entityManager,
        protected CodeSlugify $slugify,
        protected SymfonyCacheManager $cacheManager,
        protected TranslatorInterface $translator
    ) {
    }

    public function supports(EntityInterface $entity): bool
    {
        return $entity instanceof Menu;
    }

    public function onPreUpdate(EntityManagerEvent $event)
    {
        if (!$this->supports($event->getEntity())) {
            return;
        }

        $menu = $event->getEntity();
        $menu->setCode($this->slugify->slugify($menu->getCode()));
    }

    public function onCreate(EntityManagerEvent $event)
    {
        if (!$this->supports($event->getEntity())) {
            return;
        }

        $menu = $event->getEntity();

        if (count($menu->getNodes()) >= 2) {
            return;
        }

        $rootNode = $menu->getRootNode();

        if (!$rootNode) {
            $rootNode = $this->nodeFactory->create($menu);
        }

        $childNode = $this->nodeFactory->create($menu, '/');
        $childNode
            ->setParent($rootNode)
            ->setLabel($this->translator->trans('First element'))
        ;

        $menu->setRootNode($rootNode);

        foreach ([$rootNode, $childNode, $menu] as $entity) {
            $this->entityManager->getEntityManager()->persist($entity);
        }

        $this->entityManager->flush();

        $this->nodeRepository->persistAsFirstChild($childNode, $rootNode);

        $this->cacheManager->cleanRouting();
    }

    public function onUpdate(EntityManagerEvent $event)
    {
        return $this->onCreate($event);
    }

    public function onPreCreate(EntityManagerEvent $event)
    {
        return $this->onPreUpdate($event);
    }
}
