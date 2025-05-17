<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Manual;

use App\Domain\Telegram\Command\Telegram\Marriage\Trait\KeyboardTrait;
use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;

final readonly class SendSecondDayNotificationMessage
{
    use KeyboardTrait;

    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        $message = sprintf(
            <<<'TXT'
            Привет! 
            Мы добавили в бота информацию о втором две свадьбы <b>(05.06.25)</b>
            TXT,
        );

        $telegram->sendMessage([
            'chat_id' => $telegramUser->getChatId(),
            'parse_mode' => 'HTML',
            'text' => $message,
        ]);

        $keyboard = [
            'inline_keyboard' => [
                [
                    ['text' => 'Да, буду 🕺🏻💃🏼', 'callback_data' => 'second-day-yes'],
                ],
                [
                    ['text' => 'Нет, не получится 😔', 'callback_data' => 'second-day-no'],
                ]
            ]
        ];

        $this->sendMessageWithKeyboard(
            $telegram,
            $telegramUser,
            'Планируешь ли ты участвовать во втором дне?',
            $keyboard
        );
    }
}

