<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\TelegramTextLog\AddTextLog;
use App\Infrastructure\Doctrine\Entity\TelegramUser;
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

        // Проверяем существование пользователя
        $existUser = $this->telegramUserRepository->findByChatId($chatId);

        // Если пользователь новый и отправил не команду, отправляем кнопку Старт
        if ($existUser === null && !str_starts_with($text, '/')) {
            // Создаем клавиатуру с кнопкой Старт
            $keyboard = [
                'inline_keyboard' => [
                    [
                        ['text' => '🎉 Начать', 'callback_data' => 'start_welcome']
                    ]
                ]
            ];
            
            // Отправляем сообщение с кнопкой
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Добро пожаловать! Нажмите кнопку "Начать", чтобы начать взаимодействие с ботом.',
                'reply_markup' => json_encode($keyboard)
            ]);
            
            return;
        }

        $this->saveTextLog($existUser, $text);

        if (!str_starts_with($text, '/')) {
            $telegram->sendMessage([
                'chat_id' => $chatId,
                'text' => 'Такой команды нет: ' . $text,
            ]);
        }
    }

    private function saveTextLog(?TelegramUser $existUser, string $text): void
    {
        if ($existUser === null) {
            return;
        }

        $this->addTextLog->saveOnlyText($existUser, $text);
    }
}
