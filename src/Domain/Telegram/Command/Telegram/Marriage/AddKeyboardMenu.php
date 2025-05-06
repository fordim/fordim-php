<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage;

use App\Domain\Telegram\Command\Telegram\Marriage\Trait\KeyboardTrait;
use App\Domain\Telegram\Type\CommandMessageType;
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
                    ['text' => CommandMessageType::restaurant->value],
                    ['text' => CommandMessageType::weddingHall->value],
                ],
                [
                    ['text' => CommandMessageType::contacts->value],
                    ['text' => CommandMessageType::dressCode->value],
                ],
                [
                    ['text' => CommandMessageType::krasnodar->value],
                ],
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
        ];

        $telegram->sendMessage([
            'chat_id' => $telegramUser->getChatId(),
            'text' => 'Если возникли сложности с ботом, пишите в личные сообщения.',
            'reply_markup' => json_encode($keyboard),
        ]);
    }
}
