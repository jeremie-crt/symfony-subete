<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ArticleListener
{
    public function onTryListener(ResponseEvent $event)
    {
        dump("ArticleListener");
    }
}
