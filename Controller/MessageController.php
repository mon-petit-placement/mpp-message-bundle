<?php

declare(strict_types=1);

namespace Mpp\MessageBundle\Controller;

use Mpp\Message\Provider\MailerProvider;
use Mpp\MessageBundle\Provider\MessageProvider;
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
     * @Route("/send/{messageIdentifier}", name="mpp_message_send")
     */
    public function send(Request $request, MessageProvider $messageProvider, string $messageIdentifier): JsonResponse
    {
        try {
            $messageProvider->send(
                $messageIdentifier,
                array_merge(
                    ['recipientC' => ['john@doe.com']],
                    $request->query->get('transportOptions', [])
                ),
                $request->query->get('contents', [])
            );

            return new JsonResponse([
                'send' => 'ok',
                'messageIdentifier' => $messageIdentifier,
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'send' => 'ko',
                'messageIdentifier' => $messageIdentifier,
                'message' => $e->getMessage(),
            ]);
        }
    }
}