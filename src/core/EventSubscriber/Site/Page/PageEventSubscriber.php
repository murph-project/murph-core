<?php

namespace App\Core\EventSubscriber\Site\Page;

use App\Core\Entity\EntityInterface;
use App\Core\Entity\Site\Page\Page;
use App\Core\Event\EntityManager\EntityManagerEvent;
use App\Core\EventSubscriber\EntityManagerEventSubscriber;
use App\Core\Form\FileUploadHandler;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * class PageEventSubscriber.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class PageEventSubscriber extends EntityManagerEventSubscriber
{
    public function __construct(protected FileUploadHandler $fileUpload)
    {
    }

    public function supports(EntityInterface $entity): bool
    {
        return $entity instanceof Page;
    }

    public function onPreUpdate(EntityManagerEvent $event)
    {
        if (!$this->supports($event->getEntity())) {
            return;
        }

        $page = $event->getEntity();

        if ($page->getOgImage() instanceof UploadedFile) {
            $directory = 'uploads/page/ogImage';

            $this->fileUpload->handleForm(
                $page->getOgImage(),
                $directory,
                function ($filename) use ($page, $directory) {
                    $page->setOgImage($directory.'/'.$filename);
                }
            );
        }
    }

    public function onPreCreate(EntityManagerEvent $event)
    {
        return $this->onPreUpdate($event);
    }
}
