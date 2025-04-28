<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\Telegram\Marriage\Trait\KeyboardTrait;
use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;

final readonly class AddKeyboardMenu
{
    use KeyboardTrait;

    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        $keyboard = [
            'keyboard' => [
                [
                    ['text' => '🍽️ Ресторан'],
                    ['text' => '⛪️ Загс'],
                ],
                [
                    ['text' => '📲 Контанты'],
                    ['text' => '👗 Дресс-код'],
                ],
                [
                    ['text' => '🌴 Краснодар'],
                ],
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
        ];

        $telegram->sendMessage([
            'chat_id' => $telegramUser->getChatId(),
            'text' => 'Если возникли сложности с Ботом, пишите в личные сообщения.',
            'reply_markup' => json_encode($keyboard),
        ]);
    }
}
