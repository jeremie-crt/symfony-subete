<?php

namespace App\Service\Mailer;

use Mailjet\Resources;
use Mailjet\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailJetService
{
    private ParameterBagInterface $parameterBag;
    private Client $maijetService;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        self::initiateService();
    }

    public function initiateService() {
        $publicKey = $this->parameterBag->get('mailjet_apikey_public');
        $secretKey = $this->parameterBag->get('mailjet_apikey_secret');

        $mailjet = new Client($publicKey, $secretKey,true,['version' => 'v3.1']);
        $this->maijetService = $mailjet;
    }

    /**
     * @param $sender string @example 'senderadddress@live.fr'
     * @param $recipient string @example 'toclientadddress@live.fr'
     * @param array $options @example ['subject'=> '', 'contentHtml'=> '', 'senderName'=>'', 'recipName'=>'']
     */
    public function sendMailToSingle(string $sender, string $recipient, array $options = []): object
    {
        $subject = $options['subject'];
        $contentHtml = $options['contentHtml'];
        $senderName = $options['senderName'] ?? 'Admin';
        $recipName = $options['recipName'] ?? 'To you';

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $sender,
                        'Name' => $senderName
                    ],
                    'To' => [
                        [
                            'Email' => $recipient,
                            'Name' => $recipName
                        ]
                    ],
                    'Subject' => $subject,
                    'TextPart' => "Greetings from Mailjet!",
                    'HTMLPart' => $contentHtml
                ]
            ]
        ];

        return $this->maijetService->post(Resources::$Email, ['body' => $body]);
    }
}