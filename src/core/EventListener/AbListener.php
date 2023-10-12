<?php

namespace App\Core\EventListener;

use App\Core\Ab\AbContainer;
use App\Core\Ab\AbTest;
use App\Core\Entity\Site\Node;
use App\Core\Event\Ab\AbTestEvent;
use App\Core\Site\SiteRequest;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

/**
 * class AbListener.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class AbListener
{
    protected EventDispatcherInterface $eventDispatcher;
    protected AbContainer $container;
    protected SiteRequest $siteRequest;
    protected ?Node $node;

    public function __construct(
        AbContainer $container,
        EventDispatcherInterface $eventDispatcher,
        SiteRequest $siteRequest
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->container = $container;
        $this->siteRequest = $siteRequest;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $this->node = $this->siteRequest->getNode();

        if (!$this->supports($event->getRequest())) {
            return;
        }

        $request = $event->getRequest();
        $cookieName = md5($this->getCookieName());
        $cookieValue = $event->getRequest()->cookies->get($cookieName);

        $abTest = new AbTest($this->getAbTestCode());
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

    protected function getCookieName(): string
    {
        return 'ab_test_'.$this->getAbTestCode();
    }

    protected function getAbTestCode(): string
    {
        return $this->node->getAbTestCode();
    }

    protected function supports(Request $request): bool
    {
        if (!$this->node) {
            return false;
        }

        if (!$this->node->getHasAbTest()) {
            return false;
        }

        if (!$this->node->getAbTestCode()) {
            return false;
        }

        return true;
    }
}
