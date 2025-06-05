<?php

namespace Acme\IpBlockerBundle\EventSubscriber;

use Acme\IpBlockerBundle\Service\IpBlockerService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class IpBlockerSubscriber implements EventSubscriberInterface
{
    private IpBlockerService $ipBlocker;

    public function __construct(IpBlockerService $ipBlocker)
    {
        $this->ipBlocker = $ipBlocker;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $ip = $event->getRequest()->getClientIp();

        if ($ip && $this->ipBlocker->isBlocked($ip)) {
            $response = new Response('Access denied for your IP.', Response::HTTP_FORBIDDEN);
            $event->setResponse($response);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 100],
        ];
    }
}
