<?php

namespace App\EventSubscriber;

use App\Service\SiteSettingsProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class SiteSettingsRequestSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly SiteSettingsProvider $siteSettingsProvider,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();

        if (str_starts_with($request->getPathInfo(), '/admin')) {
            return;
        }

        $request->attributes->set('site_settings', $this->siteSettingsProvider->getSiteSettings());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
