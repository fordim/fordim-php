<?php

declare(strict_types=1);

namespace App\Domain\Telegram\Command\Telegram\Marriage\Messages;

use App\Infrastructure\Doctrine\Entity\TelegramUser;
use Telegram\Bot\Api;

final readonly class SendKrasnodarInfoMessage
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
                <b>Информация по Краснодару:</b>
                
                - Молодожены проживают по адресу ул. Жлобы, дом 141, подьезд 2.
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
