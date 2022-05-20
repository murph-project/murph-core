<?php

namespace App\Core\EventListener;

use App\Core\Ab\AbContainer;
use App\Core\Ab\AbTest;
use App\Core\Event\Ab\AbTestEvent;
use App\Core\Repository\Site\NodeRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
 * class AbListener.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class AbListener
{
    protected NodeRepository $nodeRepository;
    protected EventDispatcherInterface $eventDispatcher;
    protected AbContainer $container;

    public function __construct(
        NodeRepository $nodeRepository,
        AbContainer $container,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->nodeRepository = $nodeRepository;
        $this->eventDispatcher = $eventDispatcher;
        $this->container = $container;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->attributes->has('_node')) {
            return;
        }

        $node = $this->nodeRepository->findOneBy([
            'id' => $request->attributes->get('_node'),
        ]);

        if (!$node) {
            return;
        }

        if (!$node->getHasAbTest()) {
            return;
        }

        if (!$node->getAbTestCode()) {
            return;
        }

        $cookieName = md5('ab_test_'.$node->getAbTestCode());
        $cookieValue = $request->cookies->get($cookieName);

        $abTest = new AbTest($node->getAbTestCode());
        $event = new AbTestEvent($abTest);
        $this->container->add($abTest);

        $this->eventDispatcher->dispatch($event, AbTestEvent::INIT_EVENT);

        if (!$abTest->isValidVariation($cookieValue)) {
            $abTest->run();
            $result = $abTest->getResult();

            $attributes = array_merge($request->attributes->get('ab_test_cookies', []), [
                $cookieName => ['value' => $result, 'duration' => $abTest->getDuration()],
            ]);

            $request->attributes->set('ab_test_cookies', $attributes);

            $this->eventDispatcher->dispatch($event, AbTestEvent::RUN_EVENT);
        } else {
            $abTest->setResult($cookieValue);
        }
    }

    public function onKernelResponse(ResponseEvent $event)
    {
        $cookies = $event->getRequest()->attributes->get('ab_test_cookies', []);

        foreach ($cookies as $name => $value) {
            $cookie = Cookie::create($name, $value['value'], time() + $value['duration']);
            $event->getResponse()->headers->setCookie($cookie);
        }
    }
}
