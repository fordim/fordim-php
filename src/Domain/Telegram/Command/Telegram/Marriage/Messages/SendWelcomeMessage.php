<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Messages;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;

final readonly class SendWelcomeMessage
{
    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        try {
            $telegram->sendMessage([
                'chat_id' => $telegramUser->getChatId(),
                'text' => sprintf(
                    <<<'TXT'
                    Привет, %s!
                    Рады приветствовать тебя в свадебном боте Светланы и Дмитрия 💍🎉
                    Здесь ты найдёшь всю важную организационную информацию.
                    В этом боте будет постепенно появляться актуальная информация о свадьбе, а также ты будешь получать полезные уведомления и напоминания.
                    TXT,
                    $telegramUser->getUserName(),
                ),
            ]);
        } catch (\Exception $e) {
            $telegram->sendMessage([
                'chat_id' => $telegramUser->getChatId(),
                'text' => 'Отладка: Ошибка при обработке кнопки: ' . $e->getMessage(),
            ]);
        }
    }
}
