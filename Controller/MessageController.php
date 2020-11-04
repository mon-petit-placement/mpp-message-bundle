<?php

namespace Mpp\Message\Controller;

use Mpp\Message\Provider\MailerProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/message")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/mailer/{identifier}", name="mpp_message_mailer")
     */
    public function sendMailerMessage(MailerProvider $mailerProvider, string $identifier)
    {
        try {
            $mailerProvider->send($identifier, [], []);

            return new JsonResponse([
                'send' => 'ok',
                'identifier' => $identifier,
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'send' => 'ko',
                'identifier' => $identifier,
            ]);
        }

        // A améliorer !
    }
}