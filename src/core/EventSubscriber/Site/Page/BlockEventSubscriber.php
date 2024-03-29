<?php

namespace App\Core\EventSubscriber\Site\Page;

use App\Core\Entity\EntityInterface;
use App\Core\Entity\Site\Page\FileBlock;
use App\Core\Entity\Site\Page\Page;
use App\Core\Event\EntityManager\EntityManagerEvent;
use App\Core\EventSubscriber\EntityManagerEventSubscriber;
use App\Core\Form\FileUploadHandler;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * class BlockEventSubscriber.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class BlockEventSubscriber extends EntityManagerEventSubscriber
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

        foreach ($event->getEntity()->getBlocks() as $block) {
            if ($block instanceof FileBlock) {
                if ($block->getValue() instanceof UploadedFile) {
                    $directory = 'uploads/page/block';

                    $this->fileUpload->handleForm(
                        $block->getValue(),
                        $directory,
                        function ($filename) use ($block, $directory) {
                            $block->setValue($directory.'/'.$filename);
                        }
                    );
                }
            }
        }
    }

    public function onPreCreate(EntityManagerEvent $event)
    {
        return $this->onPreUpdate($event);
    }
}
