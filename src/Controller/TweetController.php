<?php

namespace App\Controller;

use App\Message\TweetNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;

class TweetController extends AbstractController
{
    /**
     * @Route("/tweet", name="tweet_index")
     */
    public function index(MessageBusInterface $bus, Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('title', TextType::class, [
                'constraints' => new Length(['min' => 3]),
            ])
            ->add('content', TextareaType::class, [
                'constraints' => new Length(['min' => 10]),
            ])
            ->getForm()
        ;

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $source = $data['title'] . '</br>' . $data['content'];
            $bus->dispatch(new TweetNotification($source));
        }

        return $this->render('tweet/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
