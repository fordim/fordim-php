<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Manual;

use App\Domain\Telegram\Type\MessageType;
use App\Infrastructure\Repository\Doctrine\TelegramUser\TelegramUserRepository;
use Telegram\Bot\Api;

final readonly class SendManualMessage
{
    public function __construct(
        private TelegramUserRepository $telegramUserRepository,
        private SendNotificationMessage $sendNotificationMessage,
        private SendVideoMessage $sendVideoMessage,
        private SendSecondDayNotificationMessage $sendSecondDayNotificationMessage,
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

//        $telegramUser = $this->telegramUserRepository->findOneBy(["chatId" => 576623234]);
//        $telegramUser = $this->telegramUserRepository->findOneBy(["chatId" => 1328497194]);

        foreach ($telegramUsers as $telegramUser) {
            //Пока отправляю только себе
//            if ($telegramUser->getChatId() !== 576623234) {
//                continue;
//            }

            try {
                match ($messageType) {
//                    MessageType::notification => $this->sendNotificationMessage->handleDirectly($telegram, $telegramUser),
//                    MessageType::video => $this->sendVideoMessage->handleDirectly($telegram, $telegramUser),
                    MessageType::secondDay => $this->sendSecondDayNotificationMessage->handleDirectly(
                        $telegram,
                        $telegramUser,
                    ),
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

