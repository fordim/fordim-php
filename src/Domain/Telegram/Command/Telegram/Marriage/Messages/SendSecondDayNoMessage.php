<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Messages;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;

final readonly class SendSecondDayNoMessage
{
    public function __construct() {}

    public function handleDirectly(Api $telegram, TelegramUser $telegramUser): void
    {
        try {
            $telegram->sendMessage([
                'chat_id' => $telegramUser->getChatId(),
                'parse_mode' => 'HTML',
                'text' => sprintf(
                    <<<'TXT'
                    Очень жаль, но мы всё понимаем. Если твои планы поменяются - дай нам знать в личные сообщения не позднее <b>01.06.25</b> 🙏🏼
                    TXT,
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
