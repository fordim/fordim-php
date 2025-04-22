<?php

declare(strict_types=1);

namespace App\Controller\Api\Telegram\Marriage\Message;

use App\Controller\Api\Telegram\Marriage\Message\Request\SendMessageRequest;
use App\Domain\Telegram\Command\Telegram\Marriage\Manual\SendManualMessage;
use App\Domain\Telegram\Type\MessageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Telegram\Bot\Api;

class SendMessageController extends AbstractController
{
    public function __construct(
        private readonly Api $telegram,
        private readonly SendManualMessage $sendManualMessage,
    ) {
    }

    #[Route(path: '/api/telegram/wedding/send-message', methods: ['POST'])]
    #[ParamConverter('request', converter: 'fos_rest.request_body')]
    public function sendMessage(SendMessageRequest $request): Response
    {
        $messageType = MessageType::tryFrom($request->type);

        if ($messageType === null) {
            return new JsonResponse([
                'status' => 'failed',
                'error' => 'Invalid message type. Available types: ' . implode(', ', MessageType::values()),
            ], Response::HTTP_BAD_REQUEST);
        }

        $results = $this->sendManualMessage->handle($this->telegram, $messageType);

        return new JsonResponse([
            'status' => 'completed',
            'type' => $messageType->value,
            'results' => $results,
        ]);
    }
} 