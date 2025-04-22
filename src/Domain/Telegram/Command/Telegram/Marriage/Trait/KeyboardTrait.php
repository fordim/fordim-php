<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Trait;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;

trait KeyboardTrait
{
    private function sendMessageWithKeyboard(
        Api $telegram,
        TelegramUser $telegramUser,
        string $text,
        array $keyboard
    ): void {
        try {
            $telegram->sendMessage([
                'chat_id' => $telegramUser->getChatId(),
                'text' => $text,
                'reply_markup' => json_encode($keyboard),
                'parse_mode' => 'HTML',
            ]);
        } catch (\Exception $e) {
            $telegram->sendMessage([
                'chat_id' => $telegramUser->getChatId(),
                'text' => 'Произошла ошибка при отправке сообщения. Пожалуйста, попробуйте позже.',
            ]);
        }
    }
} 