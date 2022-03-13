<?php

namespace App\MessageHandler;

use App\Message\TweetNotification;
use App\Service\Mailer\MailJetService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class TweetNotificationHandler implements MessageHandlerInterface
{
    public function __invoke(TweetNotification $tweetNotification, MailJetService $mailJetService)
    {
        $date = (new \DateTimeImmutable('now'))->format('d/m/Y');
        $data = [
            'subject'=> "New message is delivered (" . $date . ")",
            'contentHtml'=> $tweetNotification->getContent(),
            'senderName'=> 'Admin Subete',
            'recipName'=> 'bob is the username',
        ];

        $mailJetService->sendMailToSingle('certain.j@live.fr', 'certain.jr@lgmail.com', $data);
    }
}