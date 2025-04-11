<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\TelegramTextLog\AddTextLog;
use App\Infrastructure\Repository\Doctrine\TelegramUser\TelegramUserRepository;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

final readonly class MessageCommand
{
    public function __construct(
        private AddTextLog $addTextLog,
        private TelegramUserRepository $telegramUserRepository,
    ) {
    }

    /**
     * @throws TelegramSDKException
     */
    public function handle(Api $telegram, array $message): void
    {
        $chatId = $message['chat']['id'];
        $text = $message['text'] ?? null;

        if ($text === null) {
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Не отправляйте картинку',
            ]);

            return;
        }

        $this->saveLog($chatId, $text);

        if (!str_starts_with($text, '/')) {
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Такой команды нет: ' . $text,
            ]);
        }
    }

    private function saveLog(int $chatId, string $text): void
    {
        $existUser = $this->telegramUserRepository->findByChatId($chatId);

        if ($existUser === null) {
            return;
        }

        $this->addTextLog->saveOnlyText($existUser, $text);
    }
}
