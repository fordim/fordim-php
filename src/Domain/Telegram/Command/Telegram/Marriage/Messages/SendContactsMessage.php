<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Messages;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;

final readonly class SendContactsMessage
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
                <b>Контакты:</b>
                
                - <b>Невеста</b> - тел. +7989-532-47-91
                - <b>Жених</b> - тел. +7989-795-46-78
                - <b>Координатор Юлия</b> - тел. +7918-996-25-28 (все вопросы связанные с непосредсвенно мероприятием в ресторане: как доехать, как пройти, куда садиться и тд.)
                - <b>Ведущий Ростислав</b> - +7989-294-13-58 (все вопросы связанные с программой, возможно что-то свое хотите добавить, можно согласовать с ведущим, он поможет организовать)
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
