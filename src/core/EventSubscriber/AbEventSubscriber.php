<?php

namespace App\Core\EventSubscriber;

use App\Core\Event\Ab\AbTestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * class AbEventSubscriber.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
abstract class AbEventSubscriber implements EventSubscriberInterface
{
    protected static int $priority = 0;

    public static function getSubscribedEvents()
    {
        return [
            AbTestEvent::INIT_EVENT => ['onInit', self::$priority],
            AbTestEvent::RUN_EVENT => ['onRun', self::$priority],
        ];
    }

    public function onInit(AbTestEvent $event)
    {
    }

    public function onRun(AbTestEvent $event)
    {
    }
}
