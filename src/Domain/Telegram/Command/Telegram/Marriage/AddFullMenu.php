<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\Telegram\Marriage\Trait\KeyboardTrait;
use App\Domain\Telegram\Type\CommandMessageType;
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
                    ['text' => CommandMessageType::restaurant->value, 'callback_data' => 'restaurant'],
                ],
                [
                    ['text' => CommandMessageType::weddingHall->value, 'callback_data' => 'wedding-hall'],
                ],
                [
                    ['text' => CommandMessageType::contacts->value, 'callback_data' => 'contacts'],
                ],
                [
                    ['text' => CommandMessageType::dressCode->value, 'callback_data' => 'dress-code'],
                ],
                [
                    ['text' => CommandMessageType::krasnodar->value, 'callback_data' => 'krasnodar'],
                ],
                [
                    ['text' => CommandMessageType::secondDay->value, 'callback_data' => 'second-day'],
                ],
            ]
        ];

        $this->sendMessageWithKeyboard(
            $telegram,
            $telegramUser,
            '📝 Выбирай интересующие тебя вопросы:',
            $keyboard
        );
    }
}
