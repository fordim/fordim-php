<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Manual;

use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendNotificationMessage;
use App\Domain\Telegram\Command\Telegram\Marriage\Messages\SendVideoMessage;
use App\Domain\Telegram\Type\MessageType;
use App\Infrastructure\Repository\Doctrine\TelegramUser\TelegramUserRepository;
use Telegram\Bot\Api;

final readonly class SendManualMessage
{
    public function __construct(
        private TelegramUserRepository $telegramUserRepository,
        private SendNotificationMessage $sendNotificationMessage,
        private SendVideoMessage $sendVideoMessage,
    )
    {
    }

    /**
     * @return array<array{user: string, status: string, error?: string}>
     */
    public function handle(Api $telegram, MessageType $messageType): array
    {
        $telegramUsers = $this->telegramUserRepository->findAll();
        $results = [];

        foreach ($telegramUsers as $telegramUser) {
            //Пока отправляю только себе
            if ($telegramUser->getChatId() !== 576623234) {
                continue;
            }

            try {
                match ($messageType) {
                    MessageType::notification => $this->sendNotificationMessage->handleDirectly($telegram, $telegramUser),
                    MessageType::video => $this->sendVideoMessage->handleDirectly($telegram, $telegramUser),
                };

                $results[] = [
                    'user' => $telegramUser->getUserName(),
                    'status' => 'success',
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'user' => $telegramUser->getUserName(),
                    'status' => 'error',
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}

