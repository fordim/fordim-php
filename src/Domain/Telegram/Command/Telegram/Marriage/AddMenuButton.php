<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;

final readonly class AddMenuButton
{
    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'Меню', 'callback_data' => 'menu'],
                ],
            ]
        ];

        try {
            $telegram->sendMessage([
                'chat_id' => $telegramUser->getChatId(),
                'text' => '📝 Вызвать меню бота:',
                'reply_markup' => json_encode($keyboard)
            ]);
        } catch (\Exception $e) {
            $telegram->sendMessage([
                'chat_id' => $telegramUser->getChatId(),
                'text' => 'Отладка: Ошибка при обработке кнопки: ' . $e->getMessage(),
            ]);
        }
    }
}
