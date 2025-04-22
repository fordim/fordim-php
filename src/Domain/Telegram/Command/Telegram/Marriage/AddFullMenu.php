<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;

final readonly class AddFullMenu
{
    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'Ресторан 🍽️', 'callback_data' => 'restaurant'],
                ],
                [
                    ['text' => 'Загс ⛪️', 'callback_data' => 'wedding_hall'],
                ],
                [
                    ['text' => 'Контанты 📲', 'callback_data' => 'contacts'],
                ],
                [
                    ['text' => 'Дресс-код 👗', 'callback_data' => 'dress_code'],
                ],
            ]
        ];

        try {
            $telegram->sendMessage([
                'chat_id' => $telegramUser->getChatId(),
                'text' => '📝 Выбирайте интересующие вопросы:',
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
