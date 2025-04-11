<?php

declare(strict_types=1);

namespace App\Controller\Api\Telegram\Marriage;

use App\Domain\Telegram\Command\Telegram\Marriage\MessageCommand;
use App\Infrastructure\Factory\CommandFactory;
use FOS\RestBundle\Controller\Annotations\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Telegram\Bot\Commands\HelpCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class TelegramController extends AbstractController
{
    public function __construct(
        private readonly Api $telegram,
        private readonly CommandFactory $commandFactory,
        private readonly MessageCommand $textMessageCommand,
    ) {
    }

    #[Route(path: '/api/webhook/marriage', methods: ['POST'])]
    public function handleMarriageWebhook(Request $request): Response
    {
        $input = $request->getContent();
        $data = json_decode($input, true);

        $logFile = __DIR__ . '/marriage_log.txt';

        file_put_contents(
            $logFile,
            sprintf("[%s] %s\n", date('Y-m-d H:i:s'), $input),
            FILE_APPEND | LOCK_EX
        );

        try {
            $this->telegram->addCommands([
                $this->commandFactory->createWelcomeCommand(),
                $this->commandFactory->createRestaurantCommand(),
                $this->commandFactory->createWeddingHallCommand(),
                $this->commandFactory->createContactsCommand(),
                HelpCommand::class,
            ]);

            if (isset($data['message'])) {
                $this->telegram->commandsHandler(true);

                $this->textMessageCommand->handle($this->telegram, $data['message']);
            }
        } catch (TelegramSDKException $exception) {
            file_put_contents(
                $logFile,
                $exception->getMessage(),
                FILE_APPEND | LOCK_EX
            );
        }

        return new Response('Message received');
    }
}
