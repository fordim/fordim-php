<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\Telegram\Marriage\Trait\KeyboardTrait;
use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;

final readonly class AddMenuButton
{
    use KeyboardTrait;

    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'Меню 📋', 'callback_data' => 'menu'],
                ]
            ]
        ];

        $this->sendMessageWithKeyboard(
            $telegram,
            $telegramUser,
            'Нажмите на кнопку меню для просмотра доступных опций:',
            $keyboard
        );
    }
}
