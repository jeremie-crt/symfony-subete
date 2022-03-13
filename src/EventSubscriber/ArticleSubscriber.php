<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;

class ArticleSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.response' => 'onKernelView',
            'kernel.controller' => 'onKernelController',
        ];
    }

    public function onKernelView(ResponseEvent $event)
    {
        dump("onKernelView");
    }

    public function onKernelController(ControllerEvent $event)
    {
        dump("onKernelController");
    }
}
