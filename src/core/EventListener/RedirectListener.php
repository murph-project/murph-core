<?php

namespace App\Core\EventListener;

use App\Core\Repository\RedirectRepositoryQuery;
use App\Core\Router\RedirectBuilder;
use App\Core\Router\RedirectMatcher;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * class RedirectListener.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class RedirectListener
{
    public function __construct(
        protected RedirectMatcher $matcher,
        protected RedirectBuilder $builder,
        protected RedirectRepositoryQuery $repository
    ) {
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $request = $event->getRequest();

        if (!$event->getThrowable() instanceof NotFoundHttpException) {
            return;
        }

        $redirects = $this->repository
            ->orderBy('.sortOrder')
            ->where('.isEnabled=true')
            ->find()
        ;

        foreach ($redirects as $redirect) {
            if ($this->matcher->match($redirect, $event->getRequest()->getUri())) {
                $event->setResponse($this->builder->buildResponse($redirect, $event->getRequest()));
            }
        }
    }
}
