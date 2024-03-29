<?php

namespace App\Core\EventSubscriber\Site;

use App\Core\Site\SiteRequest;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use function Symfony\Component\String\u;

class ForcedDomainEventSubscriber implements EventSubscriberInterface
{
    public function __construct(protected SiteRequest $siteRequest)
    {
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $navigation = $this->siteRequest->getNavigation();

        if (!$navigation) {
            return;
        }

        if (!$navigation->getForceDomain()) {
            return;
        }

        if ($navigation->getDomain() === $this->siteRequest->getDomain()) {
            return;
        }

        $uri = u($this->siteRequest->getUri())
            ->replace(
                '://'.$this->siteRequest->getDomain(),
                '://'.$navigation->getDomain()
            )
        ;

        $event->getResponse()->headers->set('Location', $uri);
        $event->getResponse()->setStatusCode(Response::HTTP_MOVED_PERMANENTLY);
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => [['onKernelResponse', 20]],
        ];
    }
}
