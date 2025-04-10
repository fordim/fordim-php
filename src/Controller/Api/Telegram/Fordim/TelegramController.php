<?php

declare(strict_types=1);

namespace App\Controller\Api\Telegram\Fordim;

use App\Infrastructure\Factory\CommandFactory;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Telegram\Bot\Commands\HelpCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Telegram\Bot\Api;

class TelegramController extends AbstractController
{
    public function __construct(
        private readonly Api $telegram,
        private readonly CommandFactory $commandFactory,
    ) {
    }

    #[Route(path: '/api/webhook/fordim', methods: ['POST'])]
    public function handleFordimWebhook(Request $request): Response
    {
        $input = $request->getContent();
        $data = json_decode($input, true);

        $logFile = __DIR__ . '/webhook_log.txt';

        file_put_contents(
            $logFile,
            sprintf("[%s] %s\n", date('Y-m-d H:i:s'), $input),
            FILE_APPEND | LOCK_EX
        );

        $this->telegram->addCommands([
            $this->commandFactory->createStartCommand(),
            $this->commandFactory->createFinishCommand(),
            HelpCommand::class,
        ]);

        if (isset($data['message'])) {
            $message = $data['message'];
            $chatId = $message['chat']['id'];
            $text = $message['text'];

            $this->telegram->commandsHandler(true);

            if (!str_starts_with($text, '/')) {
                $this->telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => 'Вы сказали: ' . $text,
                ]);
            }
        }

        return new Response('Message received');
    }
}
