<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\Telegram\Marriage\Trait\KeyboardTrait;
use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;

final readonly class AddFullMenu
{
    use KeyboardTrait;

    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => '🍽️ Ресторан', 'callback_data' => 'restaurant'],
                ],
                [
                    ['text' => '⛪️ Загс', 'callback_data' => 'wedding-hall'],
                ],
                [
                    ['text' => '📲 Контанты', 'callback_data' => 'contacts'],
                ],
                [
                    ['text' => '👗 Дресс-код', 'callback_data' => 'dress-code'],
                ],
                [
                    ['text' => '🌴 Краснодар', 'callback_data' => 'krasnodar'],
                ],
            ]
        ];

        $this->sendMessageWithKeyboard(
            $telegram,
            $telegramUser,
            '📝 Выбирайте интересующие вопросы:',
            $keyboard
        );
    }
}
